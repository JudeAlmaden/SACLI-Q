@props([
    'analytics' => [],
    'allUsers' => []
])

<div class="bg-white p-6 rounded-lg shadow-lg mt-6 border">
    <div class="flex flex-wrap justify-between items-center gap-4 mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Tickets Handled Per User</h2>

        <div class="flex items-center gap-2">
            <label for="scaleSelect" class="font-semibold">Time Scale:</label>
            <select id="scaleSelect" class="border px-2 py-1 rounded">
                <option value="day">Daily</option>
                <option value="week">Weekly</option>
                <option value="month">Monthly</option>
                <option value="year">Yearly</option>
            </select>
        </div>

        <div class="flex items-center space-x-2">
            <label class="font-semibold text-gray-700">Date Range:</label>
            <input type="text" id="dateRange" class="border border-gray-300 rounded px-2 py-1" />
        </div>

        <button id="exportUserCSVBtn" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            Export CSV
        </button>
    </div>

    <div id="ticketsPerUserOverTimeChart" style="height: 400px;"></div>

    <div class="mt-4 text-gray-600">
        <p><strong>Average Queue Time (Overall):</strong> <span>{{ $analytics['averageQueueTime']['formatted'] ?? 'N/A' }}</span></p>
        <p><strong>Average Handle Time (Overall):</strong> <span>{{ $analytics['averageHandleTime']['formatted'] ?? 'N/A' }}</span></p>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    const analyticsData = @json($analytics);
    const allUsersArray = @json($allUsers);
    const allUsers = {};
    allUsersArray.forEach(user => allUsers[user.id] = user.name);

    document.addEventListener('DOMContentLoaded', () => {
        const scaleSelect = document.getElementById('scaleSelect');
        const chartEl = document.getElementById('ticketsPerUserOverTimeChart');
        const chart = echarts.init(chartEl);
        let currentSeries = [];
        let currentDates = [];

        const flatpickrRange = flatpickr("#dateRange", {
            mode: "range",
            dateFormat: "Y-m-d",
            defaultDate: [
                new Date(new Date().setDate(new Date().getDate() - 6)),
                new Date()
            ]
        });

        document.getElementById('dateRange').addEventListener('change', renderChart);
        scaleSelect.addEventListener('change', renderChart);

        function getDateRange() {
            const dates = flatpickrRange.selectedDates;
            if (dates.length === 2) {
                return {
                    start: dates[0].toISOString().split('T')[0],
                    end: dates[1].toISOString().split('T')[0]
                };
            }
            return null;
        }

        function getAggregatedData(dayData, start, end, scale) {
            const startDate = new Date(start);
            const endDate = new Date(end);
            const aggregated = {};

            const getLabel = (dateStr) => {
                const d = new Date(dateStr);
                if (isNaN(d)) return null;

                if (scale === 'day') {
                    return d.toISOString().split('T')[0];
                } else if (scale === 'week') {
                    const first = new Date(d.getFullYear(), 0, 1);
                    const days = Math.floor((d - first) / 86400000);
                    const week = Math.ceil((days + first.getDay() + 1) / 7);
                    return `${d.getFullYear()}-W${week.toString().padStart(2, '0')}`;
                } else if (scale === 'month') {
                    return `${d.getFullYear()}-${(d.getMonth() + 1).toString().padStart(2, '0')}`;
                } else if (scale === 'year') {
                    return `${d.getFullYear()}`;
                }
                return null;
            };

            for (const [userId, dateCounts] of Object.entries(dayData)) {
                aggregated[userId] = {};
                for (const [dateStr, count] of Object.entries(dateCounts)) {
                    const d = new Date(dateStr);
                    if (d < startDate || d > endDate) continue;

                    const label = getLabel(dateStr);
                    if (!label) continue;

                    aggregated[userId][label] = (aggregated[userId][label] || 0) + count;
                }
            }

            return aggregated;
        }

        function formatLabel(label, scale) {
            if (scale === 'day') {
                const d = new Date(label);
                return isNaN(d) ? label : d.toLocaleDateString();
            } else if (scale === 'week') {
                const [year, week] = label.split('-W');
                return `Week ${week}, ${year}`;
            } else if (scale === 'month') {
                const [year, month] = label.split('-');
                const d = new Date(`${year}-${month}-01`);
                return isNaN(d) ? label : d.toLocaleDateString(undefined, { year: 'numeric', month: 'long' });
            } else if (scale === 'year') {
                return label;
            }
            return label;
        }

        function buildSeries(aggregatedData, scale) {
            const allLabels = new Set();
            Object.values(aggregatedData).forEach(userData => {
                Object.keys(userData).forEach(label => allLabels.add(label));
            });

            const sortedLabels = Array.from(allLabels).sort();
            const formattedLabels = sortedLabels.map(label => formatLabel(label, scale));

            const series = Object.entries(aggregatedData).map(([userId, userCounts]) => {
                const data = sortedLabels.map(label => userCounts[label] || 0);
                return {
                    name: allUsers[userId] || `User #${userId}`,
                    type: 'line',
                    smooth: true,
                    showSymbol: false,
                    data
                };
            });

            return { series, sortedLabels, formattedLabels };
        }

        function renderChart() {
            const scale = scaleSelect.value;
            const range = getDateRange();
            if (!range) return;

            const { start, end } = range;
            const dayData = analyticsData.ticketsByUser?.day || {};
            const aggregatedData = getAggregatedData(dayData, start, end, scale);
            const { series, sortedLabels, formattedLabels } = buildSeries(aggregatedData, scale);

            currentSeries = series;
            currentDates = formattedLabels;

            chart.setOption({
                title: {
                    text: `Tickets Per User (${scale.toUpperCase()})`,
                    left: 'center'
                },
                tooltip: { trigger: 'axis' },
                legend: { top: 30 },
                xAxis: {
                    type: 'category',
                    data: formattedLabels,
                    axisLabel: { rotate: 45, fontSize: 10 }
                },
                yAxis: {
                    type: 'value',
                    minInterval: 1
                },
                series
            });
        }

        // CSV export
        document.getElementById('exportUserCSVBtn').addEventListener('click', () => {
            if (!currentSeries.length || !currentDates.length) {
                alert("Chart not loaded yet.");
                return;
            }

            const scale = scaleSelect.value;
            const range = getDateRange();
            const rangeText = range ? `From ${range.start} to ${range.end}` : 'All time';

            const header = ['User', ...currentDates, `Average per ${scale}`];
            const rows = currentSeries.map(series => {
                const sum = series.data.reduce((a, b) => a + b, 0);
                const avg = (sum / series.data.length).toFixed(2);
                return [series.name, ...series.data, avg];
            });

            const metadata = [
                [`Tickets Per User Export`],
                [`Time Scale: ${scale.charAt(0).toUpperCase() + scale.slice(1)}`],
                [`Date Range: ${rangeText}`],
                []
            ];

            const csvContent = [...metadata, header, ...rows]
                .map(row => row.map(value => `"${value}"`).join(','))
                .join('\n');

            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);

            const link = document.createElement("a");
            link.setAttribute("href", url);
            link.setAttribute("download", `tickets_per_user_${scale}.csv`);
            link.style.display = "none";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });

        renderChart();
    });
</script>
