import React, { useState, useEffect } from 'react';
import { fetchExchangeRates } from '../api/exchangeRates';
import ExchangeRatesTable from '../components/ExchangeRatesTable';

const ExchangeRatesPage = () => {
    const [date, setDate] = useState(new Date().toISOString().split('T')[0]);
    const [rates, setRates] = useState(null);

    useEffect(() => {
        const loadRates = async () => {
            try {
                const data = await fetchExchangeRates(date);
                setRates(data);
            } catch (error) {
                console.error('Failed to fetch exchange rates', error);
            }
        };

        loadRates();
    }, [date]);

    return (
        <div>
            <header>
                <h1>Exchange Rates</h1>
                <input
                    type="date"
                    value={date}
                    onChange={e => setDate(e.target.value)}
                    min="2023-01-01"
                    max={new Date().toISOString().split('T')[0]}
                />
            </header>
            <ExchangeRatesTable rates={rates} />
        </div>
    );
};

export default ExchangeRatesPage;
