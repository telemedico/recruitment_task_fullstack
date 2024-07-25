import React, { useState, useEffect } from 'react';
import './ExchangeRatesPage.css';
import {fetchExchangeRates} from "../../api/exchangeRates";
import ExchangeRatesTable from "../../components/ExchangeRatesTable/ExchangeRatesTable";
import CurrencyCalculator from "../../components/CurrencyCalculator/CurrencyCalculator";

const ExchangeRatesPage = () => {
    const [date, setDate] = useState(new Date().toISOString().split('T')[0]);
    const [rates, setRates] = useState(null);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(false);
    const [selectedCurrency, setSelectedCurrency] = useState(null);

    useEffect(() => {
        const loadRates = async () => {
            setLoading(true);
            setError(false);
            try {
                const data = await fetchExchangeRates(date);
                setRates(data);
            } catch (error) {
                setError(true);
            } finally {
                setLoading(false);
            }
        };

        loadRates();
    }, [date]);

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
                            onChange={e => setDate(e.target.value)}
                            min="2023-01-01"
                            max={new Date().toISOString().split('T')[0]}
                        />
                    </header>
                    <ExchangeRatesTable rates={rates} loading={loading} error={error} onRowClick={setSelectedCurrency} />
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
