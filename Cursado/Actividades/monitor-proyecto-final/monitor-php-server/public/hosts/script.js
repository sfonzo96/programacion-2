import { api, auth, toast } from "/scripts/util.js";

async function init() {
    document.getElementById('logoutBtn').addEventListener('click', async () => { await auth.logout(); location.href = '/login/'; });
    await fillNetworks();
    await loadHosts();
    document.getElementById('networkSelect').addEventListener('change', loadHosts);
}

async function fillNetworks() {
    const selector = document.getElementById('networkSelect');
    const params = new URLSearchParams(location.search);
    const selected = params.get('networkId');
    try {
        const res = await api.get('/api/networks');
        selector.innerHTML = '<option value="">All</option>' + (res.data || []).map(n => `<option value="${n.id}" ${String(n.id) === selected ? 'selected' : ''}>${n.ipAddress}/${n.cidrMask}</option>`).join('');
    } catch { selector.innerHTML = '<option value="">All</option>'; }
}

async function loadHosts() {
    const tbody = document.querySelector('#hostsTable tbody');
    const selector = document.getElementById('networkSelect');
    const url = selector.value ? `/api/hosts/network/${selector.value}` : '/api/hosts';
    tbody.innerHTML = '<tr><td class="small" colspan="5">Loading…</td></tr>';
    try {
        const res = await api.get(url);
        const rows = (res.data || []).map(h =>
            `<tr>
        <td style="text-align: center;">${h.id}</td>
        <td style="text-align: center;">${h.hostname || ''}</td>
        <td style="text-align: center;">${h.ipAddress}</td>
        <td style="text-align: center;">${h.macAddress}</td>
        <td style="text-align: center;">${h.network?.ipAddress || ''}/${h.network?.cidrMask || ''}</td>
        <td style="text-align: center; color: ${h.isOnline ? 'green' : 'red'}">*</td>
      </tr>`
        ).join('');
        tbody.innerHTML = rows || '<tr><td class="small" colspan="5">No records</td></tr>';
    } catch (e) { tbody.innerHTML = `<tr><td colspan="5" class="small">${e.message}</td></tr>`; }
}

init();
