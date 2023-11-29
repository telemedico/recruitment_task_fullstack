
export class ExchangeCurrencyDataModelConverter {

    constructor(params = {}) {
        this._exchangeParams = {
            sellCommisions: {
                EUR: 0.05,
                USD: 0.05,
                DEFAULT: 0.15
            },
            buyCommisions: {
                EUR: -0.05,
                USD:  -0.05,
                DEFAULT: null
            },
            showCurrencies: ['EUR', 'USD', 'CZK', 'IDR', 'BRL', 'SLO'],
            amountMultiplied: {
                IDR: 1000, 
            },
            ...params,
        };
        
    }


    calculateExchangeDataModel(response) {
        const result = this._calculateDates(response);
        const toCalculateFiltered = this._filterCurrencies(response);
        result.currencies = toCalculateFiltered.map(item => this._calculateCurrencyPrices(response.onlyLatestData, item));
        return result;
    }

    _filterCurrencies(response) {
        let result = [];
        if (response.onlyLatestData) {
            result = response.latest.rates.filter(
                item => this._exchangeParams.showCurrencies.includes(item.code)
            );
        } else {
            const filteredHistory = response.historical.rates.filter(
                item => this._exchangeParams.showCurrencies.includes(item.code)
            );
            const filteredCurrent = response.latest.rates.filter(
                item => this._exchangeParams.showCurrencies.includes(item.code)
            );
            result = filteredHistory.map(itemHistory => {
                const foundCurrent = filteredCurrent.find(itemCurrent => itemCurrent.code === itemHistory.code);
                  if (foundCurrent) {
                    return {
                        ...itemHistory,
                        currentMid: foundCurrent.mid,
                    }
                } 
                throw new Error('Nie mozna zsychronizować danych.');
            });
        }
        return result;
    }

    _calculateDates(response) {
        const result = {}
        result.effectiveDate = response.onlyLatestData 
            ? response.latest.effectiveDate
            : response.historical.effectiveDate

        if (!response.onlyLatestData) {
            result.latestDate = response.latest.effectiveDate;
        }
        return result;
    }

    _calculateCurrencyPrices(onlyLatestData, item) {
        const amountMultiplied = this._exchangeParams.amountMultiplied[item.code] || 1;
        const buyCommision = this._exchangeParams.buyCommisions[item.code] ? 
            this._exchangeParams.buyCommisions[item.code]:
            this._exchangeParams.buyCommisions.DEFAULT
        const sellCommision = this._exchangeParams.sellCommisions[item.code] ? 
            this._exchangeParams.sellCommisions[item.code]:
            this._exchangeParams.sellCommisions.DEFAULT
        const result = {
            ...item,
            nbp: (item.mid * amountMultiplied),
            buy: buyCommision ? (item.mid * amountMultiplied) + buyCommision : null,
            sell: sellCommision ? (item.mid * amountMultiplied) + sellCommision : null,
            key: item.code,
            amountMultiplied,
        };
        if (!onlyLatestData) {
            result.currentNbp = (item.currentMid * amountMultiplied);
            result.currentBuy = buyCommision ? (item.currentMid * amountMultiplied) + buyCommision : null;
            result.currentSell = sellCommision ? (item.currentMid * amountMultiplied) + sellCommision : null;
        }
        return result;
    }


}