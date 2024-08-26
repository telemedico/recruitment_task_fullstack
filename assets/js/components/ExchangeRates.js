import React, { useEffect, useState } from 'react';
import { useParams, useHistory } from 'react-router-dom';
import DatePicker from './DatePicker'; // Adjust the import path as necessary
import Loading from './Loading'; // Import the Loading component
import ExchangeRateTable from './ExchangeRateTable'; // Import the ExchangeRateTable component
import CurrentRateTable from './CurrentRateTable'; // Import the CurrentRateTable component

const ExchangeRates = () => {
    const { currency, currencyOrDate, date } = useParams(); // Get the parameters from the URL
    const history = useHistory(); // Hook to manipulate history (URL)
    const [rates, setRates] = useState(null);
    const [currentRates, setCurrentRates] = useState(null); // State to hold current date's rates
    const [loading, setLoading] = useState(true);
    const [selectedDate, setSelectedDate] = useState(""); // State to hold the date
    const [todayDate, setTodayDate] = useState(new Date().toISOString().split("T")[0]); // State to hold today's date

    useEffect(() => {
        let apiUrl;

        // Determine the initial date
        let initialDate = date || currencyOrDate;
        if (!initialDate) {
            initialDate = todayDate;
            setSelectedDate(todayDate);
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

        // Fetch data for selected date
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

        // Fetch data for current date if a specific currency and date are selected
        if (currency && date) {
            fetch(`/api/exchange-rates/${currency}/${todayDate}`)
                .then(response => response.json())
                .then(data => {
                    setCurrentRates(data.rates || data);
                })
                .catch(error => {
                    console.error('Error fetching the current rates:', error);
                });
        }
    }, [currency, currencyOrDate, date, todayDate]); // Re-run effect when any of these params change

    const handleDateChange = (newDate) => {
        // Update the URL with the new date
        if (currency) {
            history.push(`/exchange-rates/${currency}/${newDate}`);
        } else {
            history.push(`/exchange-rates/${newDate}`);
        }
    };

    return (
        <div>
            <section className="row-section">
                <div className="container">
                    <div className="row mt-5">
                        <div className="col-md-8 offset-md-2">
                            <h2 className="text-center"><span>Kursy Wymiany Walut</span></h2>
                            {loading ? (
                                <Loading />
                            ) : (
                                <div className="container">
                                    <DatePicker initialDate={selectedDate} onDateChange={handleDateChange} />
                                    <ExchangeRateTable rates={rates} selectedDate={selectedDate} />
                                    {currency && date && currentRates && (
                                        <CurrentRateTable currentRates={currentRates} todayDate={todayDate} />
                                    )}
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    );
}

export default ExchangeRates;
