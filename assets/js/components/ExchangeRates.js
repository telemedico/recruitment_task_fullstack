import React, { useEffect, useState } from 'react';
import axios from 'axios';
import './../../css/ExchangeRates.css';
import { useParams, useHistory } from 'react-router-dom';

const ExchangeRates = () => {
    const { date: paramDate } = useParams();
    const [rates, setRates] = useState([]);
    const [date, setDate] = useState(date || new Date().toISOString().split('T')[0]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const history = useHistory();

    useEffect(() => {
        const fetchRates = async () => {
            try {
                setLoading(true);
                setError(null);
                const response = await axios.get(`http://172.45.49.10/api/exchange-rates?date=${date}`);
                setRates(response.data);
                setLoading(false);
            } catch (err) {
                setError(err.message);
                setLoading(false);
            }
        };

        fetchRates();
    }, [date]);

    useEffect(() => {
        if (paramDate) {
            setDate(paramDate);
        }
    }, [paramDate]);

    const handleDateChange = (event) => {
        const newDate = event.target.value;
        setDate(newDate);
        history.push(`/exchange-rates/${newDate}`);
    };

    return (
        <div>
            <h1>Kursy walut</h1>
            <input
                type="date"
                value={date}
                onChange={handleDateChange}
                max={new Date().toISOString().split('T')[0]}
            />
            {loading ? (
                <p>Ładowanie...</p>
            ) : error ? (
                <p>Wystąpił błąd: {error}</p>
            ) : (
                <table>
                    <thead>
                    <tr>
                        <th>Waluta</th>
                        <th>Kurs NBP</th>
                        <th>Kurs Kupna</th>
                        <th>Kurs Sprzedaży</th>
                    </tr>
                    </thead>
                    <tbody>
                    {rates.map((rate) => (
                        <tr key={rate.currency}>
                            <td>{rate.currency}</td>
                            <td>{rate.averageRate.toFixed(2)}</td>
                            <td>{rate.buyRate !== null ? rate.buyRate.toFixed(2) : 'N/A'}</td>
                            <td>{rate.sellRate.toFixed(2)}</td>
                        </tr>
                    ))}
                    </tbody>
                </table>
            )}
        </div>
    );
};

export default ExchangeRates;