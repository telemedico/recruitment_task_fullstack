export function getAdjustedTodayString() {
    const now = new Date();
    if (now.getHours() >= 12) {
        return now.toLocaleDateString('en-CA');
    } else {
        const yesterday = new Date();
        yesterday.setDate(now.getDate() - 1);
        return yesterday.toLocaleDateString('en-CA');
    }
}