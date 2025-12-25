import { auth, toast } from "/scripts/util.js";

async function init() {
    document.getElementById('loginBtn').addEventListener('click', onLogin);

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('out')) {
        toast.show('Logged out.');
    }

    const username = localStorage.getItem("user");
    if (username) {
        document.getElementById('username').value = username;
    }
}

async function onLogin() {
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();
    const remember = document.getElementById('remember').checked;
    if (!username || !password) return toast.show('Username and password required');
    try {
        await auth.loginBasic(username, password);
        if (remember) {
            const t = auth.getToken();
            localStorage.setItem('monitor.accessToken', t);
            sessionStorage.removeItem('monitor.accessToken');
            localStorage.setItem("user", username);
        }

        location.href = '/dashboard/';
    } catch (e) {
        toast.show(e.message);
    }
}

init(); 