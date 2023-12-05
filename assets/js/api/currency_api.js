/**
 * Return a Promise object with the currency data
 * @returns {Promise<any>}
 */

export function getCurrencyData(date) {
    return fetch('/currencyData/' + date, {
    }).then(response => {
            return response.json().then((data) => data.items);
        })
}
