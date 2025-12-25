import { api, auth, toast } from "/scripts/util.js";

let chart = null;
let currentMetric = null;
let lastPull = null;

let refreshInterval;

async function init() {
    document.getElementById('logoutBtn').addEventListener('click', async () => { await auth.logout(); location.href = '/login/'; });
    document.getElementById('metricSelect').addEventListener('change', () => {
        lastPull = null; // Comment: Reset timestamp when changing metrics
        loadMetricChart();
    });
    document.getElementById('refreshData').addEventListener('click', refreshCurrentChart);

    await loadMetricsList();

    const urlParams = new URLSearchParams(window.location.search);
    const metricId = urlParams.get('id');
    if (metricId) {
        document.getElementById('metricSelect').value = metricId;
    } else {
        document.getElementById('metricSelect').selectedIndex = 0;
    }

    await loadMetricChart();

    refreshInterval = setInterval(loadMetricChart, 1.5 * 1000);
}

async function loadMetricsList() {
    const select = document.getElementById('metricSelect');
    try {
        const res = await api.get('/api/metrics/');
        const options = (res.data || []).map(m =>
            `<option value="${m.id}">${m.name} (${m.unit || 'no unit'})</option>`
        ).join('');
        select.innerHTML = '<option value="">Select a metric...</option>' + options;
    } catch (e) {
        select.innerHTML = '<option value="">Failed to load metrics</option>';
        toast.show('Failed to load metrics');
    }
}

async function loadMetricChart() {
    const select = document.getElementById('metricSelect');
    if (!select.value) select.value = 1;

    try {
        if (lastPull === null) {
            const res = await api.get(`/api/metrics/${select.value}`); // Comment: Fetches only metric data
            const metric = res.data || {};

            currentMetric = metric;

            document.getElementById('chartTitle').textContent = `${metric.name || 'Unknown'} (${metric.unit || 'no unit'})`;
            document.getElementById('lastUpdate').textContent = `Updated: ${new Date().toLocaleTimeString()}`;

            createEmptyChart(metric);
            showNoData(false);
            updateStats([]); // Comment: Starts empty graph
        } else {
            const url = `/api/metrics/${select.value}?since=${lastPull}`;
            const res = await api.get(url);
            const metric = res.data || {};

            document.getElementById('lastUpdate').textContent = `Updated: ${new Date().toLocaleTimeString()}`;

            if (!metric.records || metric.records.length === 0) {
                return;
            }

            metric.records.forEach(record => {
                addData(chart,
                    new Date(record.createdAt.date || Date.now()).toLocaleTimeString(),
                    parseFloat(record.value) || 0
                );
            });

            // Update stats with all current chart data
            const allCurrentValues = chart.data.datasets[0].data;
            updateStats(allCurrentValues.map(value => ({ value })));
        }

        lastPull = Date.now();

    } catch (e) {
        toast.show('Failed to load metric data');
    }
}

async function refreshCurrentChart() {
    if (!currentMetric) return toast.show('No metric selected');
    await loadMetricChart();
}

function addData(chart, label, newData) {
    chart.data.labels.push(label);
    chart.data.datasets.forEach((dataset) => {
        dataset.data.push(newData);
    });

    // Comment: Limits to last 100 data points for spacing and sliding effect
    const maxDataPoints = 100;
    if (chart.data.labels.length > maxDataPoints) {
        chart.data.labels.shift();
        chart.data.datasets.forEach((dataset) => {
            dataset.data.shift();
        });
    }

    chart.update('none'); // Comment: Updates without animation
}

function createEmptyChart(metric) {
    const ctx = document.getElementById('metricChart').getContext('2d');

    if (chart) {
        chart.destroy();
    }

    chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [], // X 
            datasets: [{ // Y
                label: metric.name || 'Metric',
                data: [],
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#e5e7eb'
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: '#9ca3af'
                    },
                    grid: {
                        color: '#374151'
                    }
                },
                y: {
                    ticks: {
                        color: '#9ca3af'
                    },
                    grid: {
                        color: '#374151'
                    }
                }
            }
        }
    });
}

function updateStats(records) {
    if (!records || records.length === 0) {
        document.getElementById('latestValue').textContent = '-';
        document.getElementById('avgValue').textContent = '-';
        document.getElementById('recordCount').textContent = '0';
        return;
    }

    const values = records.map(r => parseFloat(r.value) || 0);
    const latest = values[values.length - 1] || 0;
    const avg = values.length > 0 ? (values.reduce((a, b) => a + b, 0) / values.length) : 0;

    document.getElementById('latestValue').textContent = latest.toFixed(2);
    document.getElementById('avgValue').textContent = avg.toFixed(2);
    document.getElementById('recordCount').textContent = records.length;
}

function showNoData(show) {
    document.getElementById('noData').style.display = show ? 'flex' : 'none';
    document.querySelector('.chart-container').style.display = show ? 'none' : 'block';
}

window.addEventListener('beforeunload', () => {
    if (refreshInterval) clearInterval(refreshInterval);
});

init();