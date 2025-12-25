import { api, auth, toast } from "/scripts/util.js";

async function init() {
  document.getElementById('logoutBtn').addEventListener('click', async () => { await auth.logout(); location.href = '/login/'; });
  await loadMetrics();
}

async function loadMetrics() {
  const tbody = document.querySelector('#metricsTable tbody');
  tbody.innerHTML = '<tr><td class="small" colspan="5">Loading…</td></tr>';
  try {
    const res = await api.get('/api/metrics/');
    const rows = (res.data || []).map(m =>
      `<tr>
        <td>${m.id}</td>
        <td>${m.name}</td>
        <td>${m.description || ''}</td>
        <td>${m.unit || ''}</td>
        <td><a class="btn" href="/charts?id=${m.id}">View chart</a></td>
      </tr>`
    ).join('');
    tbody.innerHTML = rows || '<tr><td class="small" colspan="5">No records</td></tr>';
  } catch (e) { tbody.innerHTML = `<tr><td colspan="5" class="small">${e.message}</td></tr>`; }
}

init();
