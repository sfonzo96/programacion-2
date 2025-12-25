const storageKey = "monitor.accessToken";

function getToken() {
    return sessionStorage.getItem(storageKey) || localStorage.getItem(storageKey) || null;
}

function setToken(token, remember = false) {
    localStorage.setItem(storageKey, token);
    sessionStorage.setItem(storageKey, token);
}

function clearToken() {
    sessionStorage.removeItem(storageKey);
    localStorage.removeItem(storageKey);
}

const baseHeaders = () => {
    const headers = { 'Content-Type': 'application/json' };
    const jwtoken = getToken();
    if (jwtoken) headers['Authorization'] = `Bearer ${jwtoken}`;
    return headers;
};

async function handle(res) {
    try {
        const json = await res.json();
        if (!res.ok || json.isSuccess === false) {
            // Comment: If unauthorized, try token refresh
            if (res.status === 401) {
                const refreshed = await auth.refresh();
                if (refreshed) { return await lastRequest.retry(); }
                throw new Error(json.message || 'Unauthorized');
            }
            throw new Error(json.message || `Error ${res.status}`);
        }

        return json;
    } catch (error) {
        return { isSuccess: false, message: "Invalid JSON" };
    }
}

let lastRequest = { retry: async () => ({}) };

function isJWTexpired() {
    const token = getToken();
    if (!token) return true;

    const parts = token.split('.');
    if (parts.length !== 3) return false;

    try {
        const payload = JSON.parse(atob(parts[1]));
        const exp = payload.exp;
        const now = Math.floor(Date.now() / 1000);

        const expired = exp < now;

        if (expired) clearToken();

        // TODO: Call for a new one if refresh token is alive.

        return expired;
    } catch {
        return true;
    }
}

export const api = {
    async get(url) {
        if (isJWTexpired()) window.location.href = '/login?out=1';
        const req = async () => fetch(url, { headers: baseHeaders() });
        lastRequest.retry = async () => handle(await req());
        return handle(await req());
    },
    async post(url, body) {
        if (isJWTexpired) window.location.href = '/login?out=1';
        const req = async () => fetch(url, { method: 'POST', headers: baseHeaders(), body: JSON.stringify(body) });
        lastRequest.retry = async () => handle(await req());
        return handle(await req());
    },
    async del(url) {
        if (isJWTexpired()) window.location.href = '/login?out=1';
        const req = async () => fetch(url, { method: 'DELETE', headers: baseHeaders() });
        lastRequest.retry = async () => handle(await req());
        return handle(await req());
    }
};

export const auth = {
    setToken,
    getToken,
    clearToken,
    isJWTexpired,
    async loginBasic(username, password) {
        const cred = btoa(`${username}:${password}`);
        const res = await fetch('/api/auth/login', { method: 'POST', headers: { 'Authorization': `Basic ${cred}` } });
        const json = await res.json();
        if (!res.ok || json.isSuccess === false) {
            throw new Error(json.message || 'Login failed');
        }

        const token = json.data?.accessToken; if (!token) throw new Error('Missing token');
        setToken(token);

        return true;
    },
    async refresh() {
        // Comment: backend exposes refresh in AuthController but not routed yet; if missing, return false
        try {
            const res = await fetch('/api/auth/refresh', { method: 'POST' });
            if (!res.ok) return false;
            const json = await res.json();
            const token = json.data?.accessToken; if (!token) return false;
            setToken(token);
            return true;
        } catch { return false; }
    },
    async logout() {
        try {
            await fetch('/api/auth/logout', { method: 'POST', headers: baseHeaders() });
            clearToken();
            window.location.href = '/login?out=1';
        } catch (err) {
            console.error('Logout error', err);
        }
    }
};

export const toast = {
    show(msg, options = {}) {
        if (window.Toastify) {
            window.Toastify({
                text: msg,
                duration: options.duration || 2000,
                close: options.close || true,
                gravity: options.gravity || "top",
                position: options.position || "right",
                stopOnFocus: options.stopOnFocus || true,
                ...options
            }).showToast();
        }
    }
};
