import React, { useEffect, useState } from "react";
import { useParams, useHistory } from "react-router-dom";
import axios from "axios";

function DateExchangeRates() {
    const { date } = useParams();
    const history = useHistory();
    const [rates, setRates] = useState([]);
    const [selectedDate, setSelectedDate] = useState(date || new Date().toISOString().split('T')[0]);

    useEffect(() => {
        axios.get(`/exchange-rates/${selectedDate}`)
            .then(response => setRates(response.data))
            .catch(error => console.error("Błąd pobierania kursów:", error));
    }, [selectedDate]);

    const handleDateChange = (event) => {
        const newDate = event.target.value;
        setSelectedDate(newDate);
        history.push(`/exchange-rates/date/${newDate}`);
    };

    return (
        <div>
            <div className="form-group">
                <label htmlFor="date-picker">Wybierz datę:</label>
                <input
                    type="date"
                    id="date-picker"
                    className="form-control"
                    value={selectedDate}
                    onChange={handleDateChange}
                    min="2024-10-11"
                    max={new Date().toISOString().split('T')[0]}
                />
            </div>
            <table className="table">
                <thead>
                <tr>
                    <th>Waluta</th>
                    <th>Kurs NBP</th>
                    <th>Kurs Kupna</th>
                    <th>Kurs Sprzedaży</th>
                </tr>
                </thead>
                <tbody>
                {rates.map(rate => (
                    <tr key={rate.currency}>
                        <td>{rate.currency}</td>
                        <td>{rate.averageRate}</td>
                        <td>{rate.buyRate ?? "N/A"}</td>
                        <td>{rate.sellRate}</td>
                    </tr>
                ))}
                </tbody>
            </table>
        </div>
    );
}

export default DateExchangeRates;
