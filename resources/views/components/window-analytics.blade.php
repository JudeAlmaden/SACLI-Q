@props([
    'analytics' => [],
    'allUsers' => []
])

<div class="bg-white p-6 rounded-lg shadow-md mt-6">
    <div class="flex justify-between items-center mb-4">
        
        <h2 class="text-xl font-semibold text-gray-800">Tickets Handled Per User</h2>
        <select id="scaleSelect" class="border px-2 py-1 rounded">
            <option value="day">Daily</option>
            <option value="week">Weekly</option>
        <option value="year">Yearly</option>
        </select>
    </div>
    
    <div id="ticketsPerUserOverTimeChart" style="height: 400px;"></div>
            

    <div class="mt-4 text-gray-600">
        <p>
            <strong>Average Queue Time (Overall):</strong> 
            <span title="The average duration users waited in the queue before their ticket was called, calculated across all time periods.">
                <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                {{ $analytics['averageQueueTime']['formatted'] }}
            </span>
        </p>
        <p>
            <strong>Average Handle Time (Overall):</strong> 
            <span title="The average time taken to handle (complete) a ticket after it was called, calculated across all time periods.">
                <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                {{ $analytics['averageHandleTime']['formatted'] }}
            </span>
        </p>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
<script>
    
document.addEventListener('DOMContentLoaded', function () {
    const chartDom = document.getElementById('ticketsPerUserOverTimeChart');
    const userChart = echarts.init(chartDom);
    const scaleSelect = document.getElementById('scaleSelect');

    const rawData = @json($analytics['ticketsByUser']);
    const allUsers = @json($allUsers->mapWithKeys(fn($u) => [$u->id => $u->name]));

    function buildSeries(dataForScale) {
        const allDatesSet = new Set();
        const series = [];

        for (const [userId, dateCounts] of Object.entries(dataForScale)) {
            for (const date in dateCounts) {
                allDatesSet.add(date);
            }
        }

        const sortedDates = Array.from(allDatesSet).sort();

        for (const [userId, dateCounts] of Object.entries(dataForScale)) {
            const name = allUsers[userId] || 'Unknown';
            const seriesData = sortedDates.map(date => dateCounts[date] || 0);

            series.push({
                name: name,
                type: 'line',
                smooth: true,
                showSymbol: false,
                data: seriesData
            });
        }

        return { series, sortedDates };
    }

    function renderChart(scale) {
        const { series, sortedDates } = buildSeries(rawData[scale] || {});

        userChart.setOption({
            title: {
                text: `Tickets Per User (${scale.charAt(0).toUpperCase() + scale.slice(1)})`,
                left: 'center'
            },
            tooltip: { trigger: 'axis' },
            legend: { top: 30 },
            xAxis: {
                type: 'category',
                data: sortedDates,
                axisLabel: { rotate: 45, fontSize: 10 }
            },
            yAxis: {
                type: 'value',
                minInterval: 1
            },
            series: series
        });
    }

    scaleSelect.addEventListener('change', () => {
        renderChart(scaleSelect.value);
    });

    renderChart('day');
});
</script>
