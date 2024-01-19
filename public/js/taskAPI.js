export function startTaskApi(taskName) {
    return fetch('/task/start', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name: taskName })
    }).then(response => response.json());
}

export function stopTaskApi(taskId) {
    return fetch(`/task/stop/${taskId}`, { method: 'POST' })
        .then(response => response.json());
}
