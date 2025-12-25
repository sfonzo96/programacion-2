import { api, auth, toast } from "/scripts/util.js";

async function init(){
  document.getElementById('logoutBtn').addEventListener('click', async()=>{ await auth.logout(); location.href='/public/login/'; });
  document.getElementById('loadBtn').addEventListener('click', loadMetric);
  const id = new URLSearchParams(location.search).get('id');
  if(id){ document.getElementById('metricId').value = id; await loadMetric(); }
}

async function loadMetric(){
  const id = document.getElementById('metricId').value.trim();
  if(!id) return toast.show('Metric id required');
  try{
    const res = await api.get(`/api/metrics/${id}`);
    const m = res.data || {};
    document.getElementById('mName').textContent = m.name || '-';
    document.getElementById('mDesc').textContent = m.description || '-';
    document.getElementById('mUnit').textContent = m.unit || '-';
    const tbody = document.querySelector('#recordsTable tbody');
    const rows = (m.records||[]).map(r=>`<tr><td>${r.id||''}</td><td>${r.value}</td><td>${r.timestamp||''}</td></tr>`).join('');
    tbody.innerHTML = rows || '<tr><td class="small" colspan="3">No records</td></tr>';
  }catch(e){ toast.show(e.message); }
}

init();
