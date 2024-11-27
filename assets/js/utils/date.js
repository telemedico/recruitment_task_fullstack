export function getAdjustedTodayString() {
    const now = new Date();
    console.log(now)
        return now.toISOString().split('T')[0];

}