import React, { useEffect, useState } from 'react';
import { useParams, useHistory } from 'react-router-dom';
import './ExchangeRates.css';

const currencyNames = {
  EUR: "Euro",
  USD: "Dolar amerykański",
  CZK: "Korona czeska",
  IDR: "Rupia indonezyjska",
  BRL: "Real brazylijski",
};

const fetchExchangeRates = async (date) => {
  const response = await fetch(`http://telemedi-zadanie.localhost/office-rates/${date}`);
  const data = await response.json();
  return data[0].rates.filter(rate => ["EUR", "USD", "CZK", "IDR", "BRL"].includes(rate.code));
};

const ExchangeRates = () => {
    const [rates, setRates] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [selectedDate, setSelectedDate] = useState('');
    const [todayRates, setTodayRates] = useState([]);

    useEffect(() => {
        const getDateFromUrl = () => {
            const urlParams = new URLSearchParams(window.location.search);
            const dateFromUrl = urlParams.get('date');
            return dateFromUrl ? dateFromUrl : new Date().toISOString().split('T')[0]; // Domyślna data to dziś
        };

        const initialDate = getDateFromUrl();
        setSelectedDate(initialDate);

        const fetchRates = async (date) => {
            try {
                const response = await fetch(`/office-rates/${date}`);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const data = await response.json();
                return data;
            } catch (error) {
                setError(error);
                return [];
            }
        };

        const fetchAllRates = async () => {
            setLoading(true);
            const historicalRates = await fetchRates(initialDate);
            const todayRate = await fetchRates(new Date().toISOString().split('T')[0]);

            setRates(historicalRates);
            setTodayRates(todayRate);
            setLoading(false);
        };

        fetchAllRates();
    }, []);

    useEffect(() => {
        const fetchRates = async () => {
            setLoading(true);
            const historicalRates = await fetch(`/office-rates/${selectedDate}`);
            const todayRate = await fetch(`/office-rates/${new Date().toISOString().split('T')[0]}`);

            if (historicalRates.ok && todayRate.ok) {
                const historicalData = await historicalRates.json();
                const todayData = await todayRate.json();

                setRates(historicalData);
                setTodayRates(todayData);
            } else {
                setError('Failed to fetch rates');
            }

            setLoading(false);
        };

        if (selectedDate) {
            fetchRates();
        }
    }, [selectedDate]);

    const handleDateChange = (event) => {
        const newDate = event.target.value;
        setSelectedDate(newDate);
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.set('date', newDate); // Ustawiamy parametr date w URL
        window.history.pushState({}, '', currentUrl);
    };

    if (loading) {
        return <div>Loading...</div>;
    }

    if (error) {
        return <div>Error: {error.message}</div>;
    }

    return (
        <div className={'container'}>
            <h1>Exchange Rates</h1>
            <label htmlFor="datePicker">Select date:
            <input
                type="date"
                id="datePicker"
                value={selectedDate}
                onChange={handleDateChange}
                min="2023-01-01" // Minimum data od początku 2023 roku
                max={new Date().toISOString().split('T')[0]} // Maksimum data to dzisiaj
            /></label>
            <h2>Rates for {selectedDate}</h2>
            <table>
                <thead>
                    <tr>
                        <th>Currency</th>
                        <th>Code</th>
                        <th>NBP Rate</th>
                        <th>Buy Rate</th>
                        <th>Sell Rate</th>
                    </tr>
                </thead>
                <tbody>
                    {rates.map((rate) => (
                        <tr key={rate.code}>
                            <td>{rate.currency}</td>
                            <td>{rate.code}</td>
                            <td>{rate.mid.toFixed(4)}</td>
                            <td>{rate.buyRate !== null ? rate.buyRate.toFixed(4) : 'N/A'}</td>
                            <td>{rate.sellRate.toFixed(4)}</td>
                        </tr>
                    ))}
                </tbody>
            </table>

            <h2>Today's Rates</h2>
            <table>
                <thead>
                    <tr>
                        <th>Currency</th>
                        <th>Code</th>
                        <th>NBP Rate</th>
                        <th>Buy Rate</th>
                        <th>Sell Rate</th>
                    </tr>
                </thead>
                <tbody>
                    {todayRates.map((rate) => (
                        <tr key={rate.code}>
                            <td>{rate.currency}</td>
                            <td>{rate.code}</td>
                            <td>{rate.mid.toFixed(4)}</td>
                            <td>{rate.buyRate !== null ? rate.buyRate.toFixed(4) : 'N/A'}</td>
                            <td>{rate.sellRate.toFixed(4)}</td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
};
export default ExchangeRates;
