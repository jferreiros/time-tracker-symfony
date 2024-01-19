import { startTimer, stopTimer } from './timer.js';
import { startTaskApi, stopTaskApi } from './taskAPI.js';
import { addTaskToList, updateTimerDisplay } from './domUtils.js';

document.addEventListener('DOMContentLoaded', function() {
    let timerId, taskId;
    const startButton = document.getElementById('startButton');
    const stopButton = document.getElementById('stopButton');
    const taskNameInput = document.getElementById('taskName');
    const timerDisplay = document.getElementById('timer');
    const taskList = document.getElementById('taskList');

    startButton.addEventListener('click', function() {
        const taskName = taskNameInput.value.trim();
        if (taskName) {
            startButton.style.display = 'none';
            stopButton.style.display = 'block';
            timerId = startTimer((elapsedTime) => {
                updateTimerDisplay(timerDisplay, elapsedTime);
            });
            startTaskApi(taskName).then(data => {
                taskId = data.id;
            }).catch(error => console.error('Error:', error));
        } else {
            alert('Please enter a task name.');
        }
    });

    stopButton.addEventListener('click', function() {
        stopTimer(timerId);
        startButton.style.display = 'block';
        stopButton.style.display = 'none';
        taskNameInput.value = '';
        timerDisplay.textContent = '00:00:00';
        stopTaskApi(taskId).then(data => {
            addTaskToList(data.task, taskList, parseDuration, formatDuration);
        }).catch(error => console.error('Error:', error));
        updateTotalDuration();
    });

    function updateTotalDuration() {
        let totalDurationSeconds = 0;

        document.querySelectorAll('.task-item').forEach(item => {
            const durationElement = item.querySelector('span:last-child');
            if (durationElement) {
                const duration = parseDuration(durationElement.textContent);
                totalDurationSeconds += duration;
            }
        });

        const formattedTotalDuration = formatDuration(totalDurationSeconds);

        const totalCountElement = document.getElementById('totalCount');
        if (totalCountElement) {
            totalCountElement.textContent = `Total time working today: ${formattedTotalDuration}`;
        }
    }

    function formatDuration(seconds) {
        if (typeof seconds !== 'number' || !isFinite(seconds)) {
            console.error('formatDuration: Invalid input', seconds);
            return '0h 0m 0s';
        }

        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secondsRemain = seconds % 60;
        return `${hours}h ${minutes}m ${secondsRemain}s`;
    }

    function parseDuration(duration) {
        if (typeof duration === 'number' && isFinite(duration)) {
            return duration;
        }

        if (typeof duration === 'string') {
            const parts = duration.match(/(\d+)h (\d+)m (\d+)s/);
            if (parts && parts.length === 4) {
                return parseInt(parts[1], 10) * 3600 + parseInt(parts[2], 10) * 60 + parseInt(parts[3], 10);
            }
        }
        console.error('parseDuration: Invalid input', duration);
        return 0;
    }

});
