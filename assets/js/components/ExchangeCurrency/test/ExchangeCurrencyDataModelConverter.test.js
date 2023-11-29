
import { ExchangeCurrencyDataModelConverter } from '../ExchangeCurrencyDataModelConverter';


it('check ExchangeCurrencyDataModelConverter constructor _exchangeParams data', () => {
    const converter = new ExchangeCurrencyDataModelConverter();
    expect(converter._exchangeParams.buyCommisions).toBeDefined();
    expect(converter._exchangeParams.sellCommisions).toBeDefined();
    expect(converter._exchangeParams.showCurrencies).toBeDefined();
});

it('check calculateExchangeDataModel function calls', () => {
    const converter = new ExchangeCurrencyDataModelConverter();
    converter._calculateDates = jest.fn(() => ({ currencies: [] }));
    converter._filterCurrencies = jest.fn(() => [{}]);
    converter._calculateCurrencyPrices = jest.fn(() => {});
    converter.calculateExchangeDataModel({})
    expect(converter._calculateDates.mock.calls).toHaveLength(1);
    expect(converter._filterCurrencies.mock.calls).toHaveLength(1);
    expect(converter._calculateCurrencyPrices.mock.calls).toHaveLength(1);
});

it('check _calculateDates function returns calculated dates', () => {
    const converter = new ExchangeCurrencyDataModelConverter();
    const result = converter._calculateDates({
        onlyLatestData: true, 
        latest: {
            effectiveDate: 'test',
        }
    })
    expect(result).toEqual({
        effectiveDate: 'test'
    });
});

it('check _calculateDates function returns calculated dates for historical data', () => {
    const converter = new ExchangeCurrencyDataModelConverter();
    const result = converter._calculateDates({
        onlyLatestData: false, 
        latest: {
            effectiveDate: 'test',
        },
        historical: {
            effectiveDate: 'test1',
        }
    })
    expect(result).toEqual({
        effectiveDate: 'test1',
        latestDate: 'test'
    });
});

it('check _filterCurrencies function returns filtered array', () => {
    const converter = new ExchangeCurrencyDataModelConverter();
    const mock = {
        onlyLatestData: true,
        latest: {
            rates: [
                {code: 'USD'},
                {code: 'EUR'},
                {code: 'PLN'},
                {code: 'GBP'},
            ]
        }
    }
    const result = converter._filterCurrencies(mock)
    expect(result).toEqual([
        {code: 'USD'},
        {code: 'EUR'}
    ]);
});

it('check _filterCurrencies function returns filtered array for historical data', () => {
    const converter = new ExchangeCurrencyDataModelConverter();
    const mock = {
        onlyLatestData: false,
        latest: {
            rates: [
                {code: 'USD'},
                {code: 'EUR'},
                {code: 'BRL'},
                {code: 'GBP'},
            ]
        },
        historical: {
            rates: [
                {code: 'USD'},
                {code: 'EUR'},
                {code: 'BRL'},
                {code: 'GBP'},
            ]
        }
    }
    const result = converter._filterCurrencies(mock)
    expect(result).toEqual([
        {code: 'USD'},
        {code: 'EUR'},
        {code: 'BRL'}
    ]);
});

it('check _calculateCurrencyPrices function returns converted currency object', () => {
    const converter = new ExchangeCurrencyDataModelConverter();
    let result = converter._calculateCurrencyPrices(true, {
        mid: 1,
        code: 'USD'
    })
    expect(result).toEqual({
        amountMultiplied: 1,
        buy: 0.95,
        code: 'USD',
        key: 'USD',
        mid: 1,
        nbp: 1,
        sell: 1.05,
    });

    result = converter._calculateCurrencyPrices(true, {
        mid: 1,
        code: 'EUR'
    })
    expect(result).toEqual({
        amountMultiplied: 1,
        buy: 0.95,
        code: 'EUR',
        key: 'EUR',
        mid: 1,
        nbp: 1,
        sell: 1.05,
    });
    result = converter._calculateCurrencyPrices(true, {
        mid: 1,
        code: 'CZK'
    })
    expect(result).toEqual({
        amountMultiplied: 1,
        buy: null,
        code: 'CZK',
        key: 'CZK',
        mid: 1,
        nbp: 1,
        sell: 1.15,
    });
});

it('check _calculateCurrencyPrices function returns converted currency object for historical data', () => {
    const converter = new ExchangeCurrencyDataModelConverter();
    let result = converter._calculateCurrencyPrices(false, {
        mid: 1,
        currentMid: 2,
        code: 'USD'
    })
    expect(result).toEqual({
        amountMultiplied: 1,
        buy: 0.95,
        code: 'USD',
        key: 'USD',
        mid: 1,
        nbp: 1,
        currentBuy: 1.95,
        currentMid: 2,
        currentNbp: 2,
        currentSell: 2.05,
        sell: 1.05,
    });

    result = converter._calculateCurrencyPrices(false, {
        mid: 1,
        currentMid: 2,
        code: 'EUR'
    })
    expect(result).toEqual({
        amountMultiplied: 1,
        buy: 0.95,
        code: 'EUR',
        key: 'EUR',
        mid: 1,
        nbp: 1,
        currentBuy: 1.95,
        currentMid: 2,
        currentNbp: 2,
        currentSell: 2.05,
        sell: 1.05,
    });
    result = converter._calculateCurrencyPrices(false, {
        mid: 1,
        currentMid: 2,
        code: 'IDR'
    })
    expect(result).toEqual({
        amountMultiplied: 1000,
        buy: null,
        code: 'IDR',
        key: 'IDR',
        mid: 1,
        nbp: 1000,
        currentBuy: null,
        currentMid: 2,
        currentNbp: 2000,
        currentSell: 2000.15,
        sell: 1000.15,
    });
});