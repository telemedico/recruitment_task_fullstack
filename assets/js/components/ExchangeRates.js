import React, { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';

const ExchangeRates = () => {
    const { currency, currencyOrDate, date } = useParams(); // Get the parameters from the URL
    const [rates, setRates] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        let apiUrl;

        // Determine the API URL based on the parameters
        if (!currency && !currencyOrDate && !date) {
            // No params: Fetch all exchange rates for the current day
            apiUrl = `/api/exchange-rates`;
        } else if (currency && date) {
            // Both currency and date provided: Fetch exchange rate for the particular currency on the particular date
            apiUrl = `/api/exchange-rates/${currency}/${date}`;
        } else if (currencyOrDate) {
            // One parameter provided: it could be either currency or date
            // We will check if it matches a currency code format (simple regex check)
            const isCurrency = /^[A-Z]{3}$/.test(currencyOrDate);
            if (isCurrency) {
                // It's a currency code: Fetch exchange rate for the particular currency for today
                apiUrl = `/api/exchange-rates/${currencyOrDate}`;
            } else {
                // It's a date: Fetch all exchange rates for that date
                apiUrl = `/api/exchange-rates/${currencyOrDate}`;
            }
        }

        // Fetch data from the API
        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                setRates(data.rates || data); // Adjust based on how your API returns data
                setLoading(false);
            })
            .catch(error => {
                console.error('Error fetching the rates:', error);
                setLoading(false);
            });
    }, [currency, currencyOrDate, date]); // Re-run effect when any of these params change

    if (loading) {
        return (
            <div>
                <section className="row-section">
                    <div className="container">
                        <div className="row mt-5">
                            <div className="col-md-8 offset-md-2">
                                <h2 className="text-center"><span>Kursy Wymiany Walut</span></h2>
                                <div className={'text-center'}>
                                    <span className="fa fa-spin fa-spinner fa-4x"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        );
    }

    return (
        <div>
            <section className="row-section">
                <div className="container">
                    <div className="row mt-5">
                        <div className="col-md-8 offset-md-2">
                            <h2 className="text-center"><span>Kursy Wymiany Walut</span></h2>
                            <div className="table-responsive">
                                <table className="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Currency</th>
                                            <th>Code</th>
                                            <th>Mid Rate</th>
                                            <th>Buy Rate</th>
                                            <th>Sell Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {Array.isArray(rates)
                                            ? rates.map((rate, index) => (
                                                <tr key={index}>
                                                    <td>{rate.currency}</td>
                                                    <td>{rate.code}</td>
                                                    <td>{rate.mid ? rate.mid.toFixed(4) : 'N/A'}</td>
                                                    <td>{rate.buy ? rate.buy.toFixed(4) : 'N/A'}</td>
                                                    <td>{rate.sell ? rate.sell.toFixed(4) : 'N/A'}</td>
                                                </tr>
                                              ))
                                            : (
                                                <tr>
                                                    <td>{rates.currency}</td>
                                                    <td>{rates.code}</td>
                                                    <td>{rates.mid ? rates.mid.toFixed(4) : 'N/A'}</td>
                                                    <td>{rates.buy ? rates.buy.toFixed(4) : 'N/A'}</td>
                                                    <td>{rates.sell ? rates.sell.toFixed(4) : 'N/A'}</td>
                                                </tr>
                                              )
                                        }
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    );
}

export default ExchangeRates;
