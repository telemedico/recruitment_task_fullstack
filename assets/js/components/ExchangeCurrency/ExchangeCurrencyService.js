


export class ExchangeCurrencyService {

    constructor() {
        this._currenciesMinDate = '2023-01-01';
        this._currencyTableApi = 'http://zadanie.localhost/api/exchange-nbp-table';
    }

    static get ErrorCodes() {
        return {
            INVALID_DATE: 'INVALID_DATE',
            INVALID_JSON: 'INVALID_JSON',
            HTTP_ERROR: 'HTTP_ERROR',
            EMPTY: 'EMPTY',
        }
    }

    async fetchExchangeCurrencyData(dateIso8601) {

        const result = {
            latest: null,
            historical: null,
        }

        result.onlyLatestData = true;

        if (this._checkToday(dateIso8601)) {
            result.latest = await this._fetchExchangeCurrencyTableByDate();

        } else if (this._validDate(dateIso8601)) {
            const response = await Promise.all([
                this._fetchExchangeCurrencyTableByDate(),
                this._fetchExchangeCurrencyTableByDate(dateIso8601),
            ])
            result.onlyLatestData = false;
            result.latest = response[0];
            result.historical = response[1];

        } else {
            return Promise.reject({ type: ExchangeCurrencyService.ErrorCodes.INVALID_DATE });
        }

        return result;
    }


    todayIso8601() {
        return this._convertDateToIso8601(new Date());
    }


    async _fetchExchangeCurrencyTableByDate(dateIso8601 = null) {
        const response = await fetch(this._prepareUrl(dateIso8601), {
            method: 'GET',
            headers: {
              'Content-type': 'application/json',
            },
        });
      
        if (!response.ok || response.status !== 200) {
            return response.status === 404 
            ? Promise.reject({ type: ExchangeCurrencyService.ErrorCodes.EMPTY, message: 'Brak danych do pobrania. Wybierz inny dzień' })
            : Promise.reject({ type: ExchangeCurrencyService.ErrorCodes.HTTP_ERROR, message: 'Błąd podczas pobierania danych NBP. Proszę spróbować później' });
        }

        try {
            const json = await response.json();
            return json[0];
        } catch (error) {
            return Promise.reject({ type: ExchangeCurrencyService.ErrorCodes.INVALID_JSON });
        };
    }

  
    _prepareUrl(dateIso8601) {
        return dateIso8601 
            ? new URL(`${this._currencyTableApi}?date=${dateIso8601}`)
            : new URL(this._currencyTableApi);
    }

    _validDate(dateIso8601) {
        return dateIso8601 && /^[0-9]{4}-[0-9]{2}-[0-9]{2}$/.test(dateIso8601)  &&  dateIso8601 >= this._currenciesMinDate;
    }

    _checkToday(dateIso8601) {
        return dateIso8601 === null || dateIso8601 === this._convertDateToIso8601(new Date());
    }

    _convertDateToIso8601(date) {
        const month = date.getMonth() + 1 < 10 ? `0${date.getMonth() + 1}` : date.getMonth() + 1;
        const day = date.getDate() < 10 ? `0${date.getDate()}` : date.getDate();
        return `${date.getFullYear()}-${month}-${day}`
    }
}