@props([
    'analytics' => [],
    'uniqueUsers' => collect(),
    'queue' => null,
    // Aggregated tickets by different scales, example format:
    // [
    //   'day' => [windowId => ['2025-06-01' => 3, '2025-06-02' => 5, ...], ...],
    //   'week' => [windowId => ['2025-W22' => 10, '2025-W23' => 15, ...], ...],
    //   'month' => [windowId => ['2025-05' => 30, '2025-06' => 45, ...], ...],
    //   'year' => [windowId => ['2024' => 300, '2025' => 400, ...], ...],
    // ]
    'aggregatedTickets' => [],
    
])

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
                <li><strong>Average Queue Time:</strong>
                    {{ $analytics['averageQueueTime'] ? gmdate('H:i:s', $analytics['averageQueueTime']) : 'N/A' }}</li>
                <li><strong>Tickets Today:</strong> {{ $analytics['ticketsToday'] }}</li>
                <li><strong>Served Today:</strong> {{ $analytics['servedToday'] }}</li>
                <li><strong>Average Tickets Per Day:</strong> {{ $analytics['averageTicketsPerDay'] }}</li>
                <li><strong>Peak Day:</strong> {{ $analytics['peakDay'] ?? 'N/A' }} ({{ $analytics['peakDayCount'] ?? 0 }} tickets)</li>
            </ul>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md flex-1">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Tickets Per User</h2>
            <div id="ticketsPerUserChart" style="height: 300px;"></div>
        </div>
    </div>

    <!-- Time Scale Selector -->
    <div class="flex items-center mb-4 space-x-4">
        <label for="timeScale" class="font-semibold text-gray-700">Time Scale:</label>
        <select id="timeScale" class="border border-gray-300 rounded px-2 py-1">
            <option value="day" selected>Day</option>
            <option value="week">Week</option>
            <option value="month">Month</option>
            <option value="year">Year</option>
        </select>
    </div>

    <!-- Window Tickets Chart -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Tickets Per Window</h2>
        <div id="ticketsPerWindowChart" style="height: 400px;"></div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Tickets Per User Chart setup (same as your existing code)
        var userChartDom = document.getElementById('ticketsPerUserChart');
        var userChart = echarts.init(userChartDom);

        var userNames = [
            @foreach ($analytics['ticketsPerUser'] as $userId => $count)
                "{{ $uniqueUsers->firstWhere('id', $userId)->name ?? 'Unknown' }}",
            @endforeach
        ];
        var userCounts = [
            @foreach ($analytics['ticketsPerUser'] as $count)
                {{ $count }},
            @endforeach
        ];

        var userOption = {
            title: {
                text: 'Tickets Per User',
                left: 'center',
                textStyle: { fontWeight: 'bold' },
            },
            tooltip: {},
            xAxis: {
                type: 'category',
                data: userNames,
                axisLabel: { rotate: 30, interval: 0 }
            },
            yAxis: { type: 'value', minInterval: 1 },
            series: [{
                data: userCounts,
                type: 'bar',
                itemStyle: { color: '#4F46E5' },
                barMaxWidth: '40',
            }]
        };
        userChart.setOption(userOption);


        // Tickets Per Window Chart setup
        var windowChartDom = document.getElementById('ticketsPerWindowChart');
        var windowChart = echarts.init(windowChartDom);

        // Data from backend - aggregated by different time scales
        var aggregatedTickets = @json($aggregatedTickets);
        var windows = @json($queue->windows->toArray());

        var currentScale = 'day';

        function formatLabels(labels) {
            if (currentScale === 'week') {
                // Convert ISO week '2025-W23' to 'W23 2025'
                return labels.map(w => {
                    let [year, week] = w.split('-W');
                    return `W${week} ${year}`;
                });
            } else if (currentScale === 'month') {
                // Convert 'YYYY-MM' to 'MM/YYYY'
                return labels.map(m => {
                    let [year, month] = m.split('-');
                    return `${month}/${year}`;
                });
            }
            // day and year labels as-is
            return labels;
        }

        function parseLabel(label) {
            if (currentScale === 'week') {
                let parts = label.split(' ');
                return `${parts[1]}-W${parts[0].slice(1)}`;  // '2025-W23'
            } else if (currentScale === 'month') {
                let parts = label.split('/');
                return `${parts[1]}-${parts[0].padStart(2, '0')}`;  // '2025-06'
            }
            return label;  // day or year
        }

        // Extract all unique dates/weeks/months/years across windows
        function getAllLabels(scaleData) {
            let labelsSet = new Set();
            windows.forEach(window => {
                let dataForWindow = scaleData[window.id] || {};
                Object.keys(dataForWindow).forEach(label => labelsSet.add(label));
            });
            let labels = Array.from(labelsSet);
            labels.sort();
            return labels;
        }

        // Prepare series data for echarts
        function getSeriesData(scaleData, formattedLabels) {
            return windows.map(window => {
                let dataForWindow = scaleData[window.id] || {};
                let data = formattedLabels.map(fl => {
                    let key = parseLabel(fl);
                    return dataForWindow[key] || 0;
                });
                return {
                    name: window.name,
                    type: 'line',
                    data: data,
                    smooth: true,
                    showSymbol: false,
                };
            });
        }

        function renderChart() {
            let scaleData = aggregatedTickets[currentScale];
            if (!scaleData) {
                windowChart.clear();
                windowChart.setOption({
                    title: { text: 'No data available for scale: ' + currentScale, left: 'center' },
                });
                return;
            }
            let labels = getAllLabels(scaleData);
            let formattedLabels = formatLabels(labels);
            let series = getSeriesData(scaleData, formattedLabels);

            windowChart.setOption({
                title: {
                    text: 'Tickets Per Window (' + currentScale.charAt(0).toUpperCase() + currentScale.slice(1) + ')',
                    left: 'center',
                    textStyle: { fontWeight: 'bold' },
                },
                tooltip: { trigger: 'axis' },
                legend: { data: windows.map(w => w.name), top: 30, type: 'scroll', orient: 'horizontal' },
                xAxis: {
                    type: 'category',
                    data: formattedLabels,
                    boundaryGap: false,
                    axisLabel: { rotate: 45, fontSize: 10 },
                },
                yAxis: {
                    type: 'value',
                    minInterval: 1,
                },
                series: series,
            });
        }

        renderChart();

        document.getElementById('timeScale').addEventListener('change', function (e) {
            currentScale = e.target.value;
            renderChart();
        });
    });
</script>
