import { api, auth, toast } from "/scripts/util.js";

let allNetworks = [];

async function init() {
    document.getElementById('logoutBtn').addEventListener('click', async () => { await auth.logout(); location.href = '/login/'; });
    document.getElementById('addBtn').addEventListener('click', addNetwork);
    document.getElementById('searchNetworks').addEventListener('input', filterNetworks);
    await loadNetworks();
}

async function loadNetworks() {
    const tbody = document.querySelector('#networksTable tbody');
    tbody.innerHTML = '<tr><td class="small" colspan="3"><div class="skeleton" style="height:20px;width:100%"></div></td></tr>';
    try {
        const res = await api.get('/api/networks');
        allNetworks = res.data || [];
        renderNetworks(allNetworks);
    } catch (e) {
        tbody.innerHTML = `<tr><td colspan="3" class="small">${e.message}</td></tr>`;
    }
}

function renderNetworks(networks) {
    const tbody = document.querySelector('#networksTable tbody');
    const rows = networks.map(n =>
        `<tr>
      <td style="text-align: center;">${n.id}</td>
      <td style="text-align: center;">${n.ipAddress}/${n.cidrMask}</td>
      <td class="hstack" style="justify-content:center;">
        <a class="btn" href="/hosts/?networkId=${n.id}">Hosts</a>
        <button class="btn ghost" data-id="${n.id}">Delete</button>
      </td>
    </tr>`
    ).join('');
    tbody.innerHTML = rows || '<tr><td class="small" colspan="3">No records</td></tr>';
    tbody.querySelectorAll('button[data-id]').forEach(btn => btn.addEventListener('click', () => removeNetwork(btn.dataset.id)));
}

function filterNetworks() {
    const query = document.getElementById('searchNetworks').value.toLowerCase();
    const filtered = allNetworks.filter(n =>
        n.ipAddress.toLowerCase().includes(query) ||
        n.CIDRMask.toString().includes(query) ||
        n.id.toString().includes(query)
    );
    renderNetworks(filtered);
}

async function addNetwork() {
    const ip = document.getElementById('ip').value.trim();
    const mask = document.getElementById('cidr').value.trim();
    if (!ip || !mask) return toast.show('IP and mask required');
    try {
        await api.post('/api/networks', { ipAddress: ip, CIDRMask: Number(mask) });
        toast.show('Network created');
        document.getElementById('ip').value = '';
        document.getElementById('cidr').value = '';
        await loadNetworks();
    } catch (e) { toast.show(e.message); }
}

async function removeNetwork(id) {
    if (!confirm('Delete network #' + id + '?')) return;
    try { await api.del(`/api/networks/${id}`); toast.show('Deleted'); await loadNetworks(); }
    catch (e) { toast.show(e.message); }
}

init();
