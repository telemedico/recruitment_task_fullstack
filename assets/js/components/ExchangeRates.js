import React, { useEffect, useState } from 'react';
import { useParams, useHistory } from 'react-router-dom';
import DatePicker from './DatePicker'; // Adjust the import path as necessary

const ExchangeRates = () => {
    const { currency, currencyOrDate, date } = useParams(); // Get the parameters from the URL
    const history = useHistory(); // Hook to manipulate history (URL)
    const [rates, setRates] = useState(null);
    const [loading, setLoading] = useState(true);
    const [selectedDate, setSelectedDate] = useState(""); // State to hold the date

    useEffect(() => {
        let apiUrl;

        // Determine the initial date
        let initialDate = date || currencyOrDate;
        if (!initialDate) {
            const today = new Date().toISOString().split("T")[0];
            initialDate = today;
            setSelectedDate(today);
        } else {
            setSelectedDate(initialDate);
        }

        // Determine the API URL based on the parameters
        if (!currency && !currencyOrDate && !date) {
            // No params: Fetch all exchange rates for the current day
            apiUrl = `/api/exchange-rates`;
        } else if (currency && date) {
            // Both currency and date provided: Fetch exchange rate for the particular currency on the particular date
            apiUrl = `/api/exchange-rates/${currency}/${date}`;
        } else if (currencyOrDate) {
            // One parameter provided: it could be either currency or date
            const isCurrency = /^[A-Z]{3}$/.test(currencyOrDate);
            if (isCurrency) {
                apiUrl = `/api/exchange-rates/${currencyOrDate}`;
            } else {
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

    const handleDateChange = (newDate) => {
        // Update the URL with the new date
        if (currency) {
            history.push(`/exchange-rates/${currency}/${newDate}`);
        } else {
            history.push(`/exchange-rates/${newDate}`);
        }
    };

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
                            <div className="container">
                                <DatePicker initialDate={selectedDate} onDateChange={handleDateChange} />
                                <div className="table-responsive">
                                    <table className="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Waluta</th>
                                                <th>Kod</th>
                                                <th>Kupno</th>
                                                <th>Kurs średni</th>
                                                <th>Sprzedaż</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {Array.isArray(rates)
                                                ? rates.map((rate, index) => (
                                                    <tr key={index}>
                                                        <td>{rate.currency}</td>
                                                        <td>{rate.code}</td>
                                                        <td>{rate.buy ? rate.buy.toFixed(4) : 'N/A'}</td>
                                                        <td>{rate.mid ? rate.mid.toFixed(4) : 'N/A'}</td>
                                                        <td>{rate.sell ? rate.sell.toFixed(4) : 'N/A'}</td>
                                                    </tr>
                                                  ))
                                                : (
                                                    <tr>
                                                        <td>{rates.currency}</td>
                                                        <td>{rates.code}</td>
                                                        <td>{rates.buy ? rates.buy.toFixed(4) : 'N/A'}</td>
                                                        <td>{rates.mid ? rates.mid.toFixed(4) : 'N/A'}</td>
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
                </div>
            </section>
        </div>
    );
}

export default ExchangeRates;
