import { api, auth, toast } from "/scripts/util.js";

async function init(){
  document.getElementById('logoutBtn').addEventListener('click', async()=>{ await auth.logout(); location.href='/login/'; });
  await loadInfo();
  await loadProcesses();
  document.getElementById('refreshProc').addEventListener('click', loadProcesses);
}

async function loadInfo(){
  try{
    const res = await api.get('/api/system/info');
    const d = res.data||{};
    document.getElementById('hostname').textContent = d.hostname||'-';
    document.getElementById('os').textContent = d.os||'-';
    document.getElementById('uptime').textContent = d.uptime||'-';
    document.getElementById('cpu').textContent = `${d.cpuModel??'-'} (${d.cpuCores??'-'} cores)`;
  }catch(e){ toast.show('Failed to load system info'); }
}

async function loadProcesses(){
  const pre = document.getElementById('processes');
  pre.textContent = 'Loading...';
  try{
    const res = await api.get('/api/system/processes');
    pre.textContent = res.data?.processes?.output || 'No data';
  }catch(e){ pre.textContent = 'Failed to load'; }
}

init();
