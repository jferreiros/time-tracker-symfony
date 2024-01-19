export function startTimer(updateCallback) {
    let elapsedTime = 0;
    return setInterval(() => {
        elapsedTime++;
        updateCallback(elapsedTime);
    }, 1000);
}

export function stopTimer(timerId) {
    clearInterval(timerId);
}
