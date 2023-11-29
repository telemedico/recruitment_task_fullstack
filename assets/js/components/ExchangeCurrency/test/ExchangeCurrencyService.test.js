import { ExchangeCurrencyService } from '../ExchangeCurrencyService';

it('check ExchangeCurrencyService constructor _exchangeParams data', () => {
    const service = new ExchangeCurrencyService();
    expect(service._currenciesMinDate).toEqual('2023-01-01');
    expect(service._currencyTableApi).toEqual('http://zadanie.localhost/api/exchange-nbp-table');
});


it('check _convertDateToIso8601 returns Iso8601 format date', () => {
    const service = new ExchangeCurrencyService();
    expect(service._convertDateToIso8601(new Date('2000-01-01'))).toEqual('2000-01-01');
    expect(service._convertDateToIso8601(new Date('2001-10-30'))).toEqual('2001-10-30');
    expect(service._convertDateToIso8601(new Date('2023-11-01'))).toEqual('2023-11-01');
});

it('check _checkToday returns is actually current day', () => {
    const service = new ExchangeCurrencyService();
    const today = service._convertDateToIso8601(new Date());
    expect(service._checkToday(today)).toEqual(true);
    expect(service._checkToday('2023-11-28')).toEqual(false);
    expect(service._checkToday('2023-01-08')).toEqual(false);
});

it('check _validDate returns date is valid', () => {
    const service = new ExchangeCurrencyService();
    expect(service._validDate('2023-01-01')).toEqual(true);
    expect(service._validDate('2023-10-12')).toEqual(true);
    expect(service._validDate('2022-12-31')).toEqual(false);
    expect(service._validDate('test')).toEqual(false);
    expect(service._validDate('2023-12-1')).toEqual(false);
    expect(service._validDate('2022')).toEqual(false);
    expect(service._validDate('23-10-10')).toEqual(false);
    expect(service._validDate('2023-10-122')).toEqual(false);
    expect(service._validDate('20231-10-12')).toEqual(false);
    expect(service._validDate('2023-110-12')).toEqual(false);
    expect(service._validDate('203-10-12')).toEqual(false);
});

it('check _prepareUrl returns valid url', () => {
    const service = new ExchangeCurrencyService();
    expect(service._prepareUrl()).toEqual(new URL('http://zadanie.localhost/api/exchange-nbp-table'));
    expect(service._prepareUrl('2023-10-11')).toEqual(new URL('http://zadanie.localhost/api/exchange-nbp-table?date=2023-10-11'));
});

it('check _fetchExchangeCurrencyTableByDate returns valid data', async () => {
    const service = new ExchangeCurrencyService();
    global.fetch = jest.fn(() =>
        Promise.resolve({
            status: 200,
            ok: true,
            json: () => Promise.resolve(['test']),
        })
    );
    const result = await service._fetchExchangeCurrencyTableByDate();
    
    expect(result).toEqual('test');
});

it('check _fetchExchangeCurrencyTableByDate returns invalid data', async () => {
    const service = new ExchangeCurrencyService();
    global.fetch = jest.fn(() =>
        Promise.resolve({
            status: 404,
            ok: true,
            json: () => Promise.resolve(['test']),
        })
    );
    
    await service._fetchExchangeCurrencyTableByDate()
        .catch(data => {
            expect(data).toEqual({message: "Brak danych do pobrania. Wybierz inny dzień", type: "EMPTY"});
        });

});