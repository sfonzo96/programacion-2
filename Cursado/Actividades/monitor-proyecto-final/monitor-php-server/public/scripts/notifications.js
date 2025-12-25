import { toast, auth } from './util.js';

let eventSource;

async function init() {
	if (auth.isJWTexpired()) {
		console.log("Token expired, skipping SSE connection");
		return;
	}

	const token = localStorage.getItem('monitor.accessToken') ?? "";
	if (!token) {
		console.log("No token found, skipping SSE connection");
		return;
	}

	if (eventSource) {
		eventSource.close();
	}

	try {
		eventSource = new EventSource(`/api/sse/stream?token=${encodeURIComponent(token)}`);
	} catch (error) {
		console.error("Failed to connect to SSE", error);
		return;
	}

	console.log("Connecting to SSE...");

	eventSource.addEventListener("error", function (event) {
		if (event.eventPhase == EventSource.CLOSED) {
			console.log("SSE connection closed");
		}

		if (event.target.readyState === EventSource.CLOSED) {
			console.log("SSE connection failed, not retrying");
		}
	});

	eventSource.addEventListener("open", function (event) {
		console.log("SSE connection opened");
	});

	eventSource.addEventListener("notification", function (event) {
		const data = JSON.parse(event.data);
		toast.show(data.message);
	});
}

window.addEventListener('beforeunload', (e) => {
	eventSource.close();
});

init();




