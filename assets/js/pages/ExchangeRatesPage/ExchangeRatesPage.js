import React, { useState, useEffect } from 'react';
import { useHistory, useLocation } from 'react-router-dom';
import { fetchExchangeRates } from '../../api/exchangeRates';
import ExchangeRatesTable from '../../components/ExchangeRatesTable/ExchangeRatesTable';
import CurrencyCalculator from '../../components/CurrencyCalculator/CurrencyCalculator';
import './ExchangeRatesPage.css';

const ExchangeRatesPage = () => {
    const history = useHistory();
    const location = useLocation();
    const queryParams = new URLSearchParams(location.search);
    const initialDate = queryParams.get('date') || new Date().toISOString().split('T')[0];
    const [date, setDate] = useState(initialDate);
    const [todayRates, setTodayRates] = useState(null);
    const [selectedDateRates, setSelectedDateRates] = useState(null);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(false);
    const [selectedCurrency, setSelectedCurrency] = useState(null);

    useEffect(() => {
        const loadRates = async () => {
            setLoading(true);
            setError(false);
            try {
                const today = new Date().toISOString().split('T')[0];
                const [todayData, selectedDateData] = await Promise.all([
                    fetchExchangeRates(today),
                    fetchExchangeRates(date)
                ]);
                setTodayRates(todayData);
                setSelectedDateRates(selectedDateData);
            } catch (error) {
                setError(true);
            } finally {
                setLoading(false);
            }
        };

        loadRates();
    }, [date]);

    useEffect(() => {
        history.push(`?date=${date}`);
    }, [date, history]);

    const mergedRates = selectedDateRates?.map(rate => {
        const todayRate = todayRates?.find(r => r.code === rate.code);
        return {
            ...rate,
            todayNbpRate: todayRate ? todayRate.nbpRate : null,
            todayBuyRate: todayRate ? todayRate.buyRate : null,
            todaySellRate: todayRate ? todayRate.sellRate : null
        };
    });

    const handleDateChange = (e) => {
        const selectedDate = new Date(e.target.value);
        const day = selectedDate.getUTCDay();
        if (day !== 6 && day !== 0) {
            setDate(e.target.value);
        } else {
            alert("Weekends are not selectable. Please choose a weekday.");
        }
    };

    return (
        <div className="exchange-rates-page container">
            <div className="row w-100">
                <div className="col-md-8">
                    <header className="mb-3">
                        <h1>Exchange Rates</h1>
                        <input
                            type="date"
                            className="form-control"
                            value={date}
                            onChange={handleDateChange}
                            min="2023-01-01"
                            max={new Date().toISOString().split('T')[0]}
                        />
                    </header>
                    <ExchangeRatesTable rates={mergedRates} loading={loading} error={error} onRowClick={setSelectedCurrency} />
                </div>
                <div className="col-md-4">
                    {selectedCurrency && (
                        <CurrencyCalculator
                            currency={selectedCurrency}
                            onClose={() => setSelectedCurrency(null)}
                        />
                    )}
                </div>
            </div>
        </div>
    );
};

export default ExchangeRatesPage;
