export const getCurrentDate = () => {
    const today = new Date();
    const month = today.getMonth()+1;
    const year = today.getFullYear();
    const day = today.getDate();
    return `${year}-${month}-${day}`;
}

