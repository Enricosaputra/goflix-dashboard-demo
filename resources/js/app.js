import Chart from 'chart.js/auto';

// The dashboard passes its numbers in a data attribute, so the chart needs no extra request.
const canvas = document.getElementById('revenue-chart');

if (canvas) {
    const { labels, series, subscriptions, rentals } = JSON.parse(canvas.dataset.chart);

    new Chart(canvas, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                { label: series[0], data: subscriptions, backgroundColor: '#0f172a' },
                { label: series[1], data: rentals, backgroundColor: '#e11d48' },
            ],
        },
        options: {
            maintainAspectRatio: false,
            scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true } },
            plugins: { legend: { position: 'bottom' } },
        },
    });
}
