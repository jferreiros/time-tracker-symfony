export function highlightElement(element) {
    element.classList.add('highlight');
    setTimeout(() => {
        element.classList.remove('highlight');
    }, 2000);
}
export function addTaskToList(task, taskList, parseDuration, formatDuration) {
    let found = false;
    document.querySelectorAll('.task-item').forEach(item => {
        const nameSpan = item.querySelector('span:first-child');
        if (nameSpan && nameSpan.textContent.toLowerCase() === task.name.toLowerCase()) {
            const durationSpan = item.querySelector('span:last-child');
            const existingDurationSeconds = parseDuration(durationSpan.textContent);
            const totalDurationSeconds = existingDurationSeconds + task.duration;
            durationSpan.textContent = formatDuration(totalDurationSeconds);
            highlightElement(item);
            found = true;
        }
    });

    if (!found) {
        const div = document.createElement('div');
        div.className = 'task-item';
        div.innerHTML = `<span>${task.name}</span>
                         <span>${formatDuration(task.duration)}</span>`;
        taskList.appendChild(div);
        highlightElement(div);
    }

}



export function updateTimerDisplay(timerDisplay, elapsedTime) {
    let seconds = elapsedTime % 60;
    let minutes = Math.floor(elapsedTime / 60) % 60;
    let hours = Math.floor(elapsedTime / 3600);
    timerDisplay.textContent = `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
}

function pad(number) {
    return number < 10 ? '0' + number : number;
}
