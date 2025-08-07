@props([
    'analytics' => [],
    'uniqueUsers' => collect(),
    'queue' => null,
    'aggregatedTickets' => [],
])

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<div class="space-y-8">
    <!-- Tickets by Period Summary -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 text-center">
        @foreach (['today', 'this_week', 'this_month', 'this_year', 'lifetime'] as $period)
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h3 class="text-gray-700 font-semibold capitalize">{{ str_replace('_', ' ', $period) }}</h3>
                <p class="text-3xl font-bold text-indigo-600">
                    {{ $analytics['ticketsByPeriod'][$period] ?? 0 }}
                </p>
            </div>
        @endforeach
    </div>

    <!-- Queue Overview + Tickets per User -->
    <div class="flex flex-col md:flex-row gap-6">
        <div class="bg-white p-6 rounded-lg shadow-md flex-1">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Queue Overview</h2>
            <ul class="space-y-2 text-gray-700">
                <li><strong>Total Tickets:</strong> {{ $analytics['totalTickets'] }}</li>
                <li><strong>Served Tickets:</strong> {{ $analytics['servedCount'] }}</li>
                <li><strong>Average Queue Time:</strong> {{ $analytics['averageQueueTime'] ? gmdate('H:i:s', $analytics['averageQueueTime']) : 'N/A' }}</li>
                <li><strong>Tickets Today:</strong> {{ $analytics['ticketsToday'] }}</li>
                <li><strong>Served Today:</strong> {{ $analytics['servedToday'] }}</li>
                <li><strong>Average Tickets Per Day:</strong> {{ $analytics['averageTicketsPerDay'] }}</li>
                <li><strong>Peak Day:</strong> {{ $analytics['peakDay'] ?? 'N/A' }} ({{ $analytics['peakDayCount'] ?? 0 }} tickets)</li>
            </ul>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md flex-1">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Tickets Per User</h2>
            <div id="ticketsPerUserChart" style="height: 300px;" class="w-full"></div>
        </div>
    </div>

    <!-- Window Tickets Chart -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex flex-wrap items-center mb-4 gap-4">
            <div class="flex items-center space-x-2">
                <label class="font-semibold text-gray-700">Time Scale:</label>
                <select id="timeScale" class="border border-gray-300 rounded px-2 py-1">
                    <option value="day" selected>Day</option>
                    <option value="week">Week</option>
                    <option value="month">Month</option>
                    <option value="year">Year</option>
                </select>
            </div>

            <div class="flex items-center space-x-2">
                <label class="font-semibold text-gray-700">Date Range:</label>
                <input type="text" id="dateRange" class="border border-gray-300 rounded px-2 py-1" />
            </div>

            <button id="exportCsvBtn" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
                Export CSV
            </button>
        </div>

        <h2 class="text-xl font-semibold mb-4 text-gray-800">Tickets Per Window</h2>
        <div id="ticketsPerWindowChart" style="height: 400px;"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userChartDom = document.getElementById('ticketsPerUserChart');
        const userChart = echarts.init(userChartDom);

        const userNames = [
            @foreach ($analytics['ticketsPerUser'] as $userId => $count)
                "{{ $uniqueUsers->firstWhere('id', $userId)->name ?? 'Unknown' }}",
            @endforeach
        ];
        const userCounts = [
            @foreach ($analytics['ticketsPerUser'] as $count)
                {{ $count }},
            @endforeach
        ];

        userChart.setOption({
            title: { text: 'Tickets Per User', left: 'center', textStyle: { fontWeight: 'bold' } },
            tooltip: {},
            xAxis: { type: 'category', data: userNames, axisLabel: { rotate: 30, interval: 0 } },
            yAxis: { type: 'value', minInterval: 1 },
            series: [{ data: userCounts, type: 'bar', itemStyle: { color: '#4F46E5' }, barMaxWidth: '40' }]
        });

        const windowChartDom = document.getElementById('ticketsPerWindowChart');
        const windowChart = echarts.init(windowChartDom);

        const aggregatedTickets = @json($aggregatedTickets);
        const windows = @json($queue->windows->toArray());
        let currentScale = 'day';

        const dateRangePicker = flatpickr("#dateRange", {
            mode: "range",
            dateFormat: "Y-m-d",
            defaultDate: [new Date(new Date().setDate(new Date().getDate() - 6)), new Date()]
        });

        function getDateRangeFilter() {
            const range = dateRangePicker.selectedDates;
            if (range.length < 2) return null;
            const [start, end] = range;
            return { start: start.toISOString().slice(0, 10), end: end.toISOString().slice(0, 10) };
        }

        function extractDateFromLabel(label) {
            if (currentScale === 'day') return label;
            if (currentScale === 'week') {
                const [y, w] = label.split('-W');
                const d = new Date(`${y}-01-01`);
                d.setDate(d.getDate() + ((parseInt(w) - 1) * 7));
                return d.toISOString().slice(0, 10);
            }
            if (currentScale === 'month') return label + '-01';
            if (currentScale === 'year') return label + '-01-01';
            return label;
        }

        function filterScaleDataByDateRange(scaleData, dateRange) {
            if (!dateRange) return scaleData;
            const { start, end } = dateRange;
            const filtered = {};
            Object.entries(scaleData).forEach(([windowId, entries]) => {
                filtered[windowId] = {};
                Object.entries(entries).forEach(([label, value]) => {
                    const keyDate = extractDateFromLabel(label);
                    if (keyDate >= start && keyDate <= end) {
                        filtered[windowId][label] = value;
                    }
                });
            });
            return filtered;
        }

        function getAllLabels(scaleData) {
            const set = new Set();
            windows.forEach(w => {
                const data = scaleData[w.id] || {};
                Object.keys(data).forEach(label => set.add(label));
            });
            return Array.from(set).sort();
        }

        function formatLabels(labels) {
            if (currentScale === 'week') return labels.map(l => `W${l.split('-W')[1]} ${l.split('-W')[0]}`);
            if (currentScale === 'month') return labels.map(m => `${m.split('-')[1]}/${m.split('-')[0]}`);
            return labels;
        }

        function parseLabel(label) {
            if (currentScale === 'week') {
                const [week, year] = label.split(' ');
                return `${year}-W${week.slice(1)}`;
            }
            if (currentScale === 'month') {
                const [month, year] = label.split('/');
                return `${year}-${month.padStart(2, '0')}`;
            }
            return label;
        }

        function getSeriesData(scaleData, formattedLabels) {
            return windows.map(w => {
                const entries = scaleData[w.id] || {};
                const data = formattedLabels.map(lbl => entries[parseLabel(lbl)] || 0);
                return { name: w.name, type: 'line', data: data, smooth: true, showSymbol: false };
            });
        }

        function renderChart() {
            const scaleData = aggregatedTickets[currentScale];
            if (!scaleData) {
                windowChart.clear();
                windowChart.setOption({ title: { text: 'No data available', left: 'center' } });
                return;
            }
            const dateRange = getDateRangeFilter();
            const filteredData = filterScaleDataByDateRange(scaleData, dateRange);
            const labels = getAllLabels(filteredData);
            const formattedLabels = formatLabels(labels);
            const series = getSeriesData(filteredData, formattedLabels);

            windowChart.clear();
            windowChart.setOption({
                title: { text: `Tickets Per Window (${currentScale})`, left: 'center', textStyle: { fontWeight: 'bold' } },
                tooltip: { trigger: 'axis' },
                legend: { data: windows.map(w => w.name), top: 30, type: 'scroll' },
                xAxis: { type: 'category', data: formattedLabels, boundaryGap: false, axisLabel: { rotate: 45, fontSize: 10 } },
                yAxis: { type: 'value', minInterval: 1 },
                series: series
            });
        }

        document.getElementById('timeScale').addEventListener('change', e => {
            currentScale = e.target.value;
            renderChart();
        });

        document.getElementById('dateRange').addEventListener('change', renderChart);

       document.getElementById('exportCsvBtn').addEventListener('click', () => {
    const scaleData = aggregatedTickets[currentScale];
    if (!scaleData) return alert("No data");

    const dateRange = getDateRangeFilter();
    const filteredData = filterScaleDataByDateRange(scaleData, dateRange);
    const labels = getAllLabels(filteredData);

    // Format date range header
    const rangeLabel = dateRange
        ? `Date Range: ${dateRange.start} to ${dateRange.end}`
        : `All Dates`;

    const headerDecor = [
        '==== TICKET ANALYTICS REPORT ====\n',
        `Scale: ${currentScale.toUpperCase()}\n`,
        `${rangeLabel}\n`,
        'Generated: ' + new Date().toLocaleString(),
        '\n\n'
    ];

    const rows = [];

    // Column header
    rows.push(['Window Name', ...labels, 'Total', 'Average'].join(','));

    // Per window data
    windows.forEach(w => {
        const data = filteredData[w.id] || {};
        let total = 0;
        const row = [w.name];
        labels.forEach(l => {
            const val = data[l] || 0;
            total += val;
            row.push(val);
        });
        const avg = labels.length ? (total / labels.length).toFixed(2) : 0;
        row.push(total, avg);
        rows.push(row.join(','));
    });

    // Totals row
    const totalRow = ['TOTAL'];
    labels.forEach(label => {
        let sum = 0;
        windows.forEach(w => { sum += (filteredData[w.id]?.[label] || 0); });
        totalRow.push(sum);
    });
    totalRow.push('', '');
    rows.push(totalRow.join(','));

    // Combine with decorated header
    const csvContent = headerDecor.join('') + rows.join('\n');

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = `tickets_${currentScale}_${Date.now()}.csv`;
    a.click();
    URL.revokeObjectURL(url);
});


        renderChart();
    });
</script>
