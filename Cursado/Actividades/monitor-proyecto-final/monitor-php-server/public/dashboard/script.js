import { api, auth, toast } from "/scripts/util.js";

let refreshInterval;

async function init() {
    await loadDashboardData();

    refreshInterval = setInterval(loadDashboardData, 30 * 1000);

    document.getElementById("refreshProc").addEventListener("click", loadProcesses);

    document.getElementById("logoutBtn").addEventListener("click", async () => {
        clearInterval(refreshInterval);
        try { await auth.logout(); toast.show("Logged out"); setTimeout(() => location.href = "/login/", 600); } catch { }
    });
}

async function loadDashboardData() {
    setLoadingState(true);

    try {
        await loadCounts();
        await loadSystemInfo();
        await loadProcesses();
    } catch (e) {
        console.error('Dashboard load error:', e);
        toast.show('Failed to refresh data');
    } finally {
        setLoadingState(false);
    }
}

async function loadCounts() {
    try {
        const [networks, hosts, metrics] = await Promise.all([
            api.get("/api/networks"),
            api.get("/api/hosts"),
            api.get("/api/metrics/")
        ]);

        animateCountUpdate("networksCount", networks.data?.length ?? 0);
        animateCountUpdate("hostsCount", hosts.data?.length ?? 0);
        animateCountUpdate("metricsCount", metrics.data?.length ?? 0);
    } catch (e) {
        console.error('Count load error:', e);
        document.getElementById("networksCount").textContent = "?";
        document.getElementById("hostsCount").textContent = "?";
        document.getElementById("metricsCount").textContent = "?";
    }
}

async function loadSystemInfo() {
    try {
        const res = await api.get("/api/system/info");
        const d = res.data ?? {};
        document.getElementById("hostname").textContent = d.hostname || "-";
        document.getElementById("os").textContent = d.os || "-";
        document.getElementById("uptime").textContent = d.uptime || "-";
        document.getElementById("cpu").textContent = `${d.cpuModel ?? "-"} (${d.cpuCores ?? "-"} cores)`;
    } catch (e) {
        console.error('System info error:', e);
        document.getElementById("hostname").textContent = "Error";
        document.getElementById("os").textContent = "Error";
        document.getElementById("uptime").textContent = "Error";
        document.getElementById("cpu").textContent = "Error";
    }
}

async function loadProcesses() {
    const pre = document.getElementById("processes");
    const btn = document.getElementById("refreshProc");

    btn.disabled = true;
    btn.textContent = "Loading...";
    pre.textContent = "Loading...";

    try {
        const res = await api.get("/api/system/processes");
        const out = res.data?.processes?.output ?? "";
        pre.textContent = out || "No data";
    } catch (e) {
        pre.textContent = "Failed to load";
        toast.show("Failed to refresh processes");
    } finally {
        btn.disabled = false;
        btn.textContent = "Refresh";
    }
}

function animateCountUpdate(elementId, newValue) {
    const el = document.getElementById(elementId);
    const currentValue = parseInt(el.textContent) || 0;

    if (currentValue !== newValue) {
        el.style.transform = 'scale(1.1)';
        el.style.color = 'var(--primary)';
        setTimeout(() => {
            el.textContent = newValue;
            el.style.transform = 'scale(1)';
            el.style.color = 'var(--text)';
        }, 150);
    } else {
        el.textContent = newValue;
    }
}

function setLoadingState(isLoading) {
    const refreshBtn = document.getElementById("refreshProc");
    if (isLoading) {
        document.body.classList.add('loading');
    } else {
        document.body.classList.remove('loading');
    }
}

window.addEventListener('beforeunload', () => {
    if (refreshInterval) clearInterval(refreshInterval);
});

init();
