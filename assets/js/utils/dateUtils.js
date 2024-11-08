export function getFormattedDate() {
    const today = new Date();
    const year = today.getFullYear();
    let mm = today.getMonth() + 1; // Miesiące zaczynają się od 0, więc dodajemy 1
    let dd = today.getDate();

    if (mm < 10) mm = "0" + mm;
    if (dd < 10) dd = "0" + dd;

    return `${year}-${mm}-${dd}`;
}