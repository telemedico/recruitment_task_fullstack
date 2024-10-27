import React, { useEffect, useState } from "react";
import axios from "axios";

function TodayExchangeRates() {
    const [rates, setRates] = useState([]);

    useEffect(() => {
        axios.get("/exchange-rates/today")
            .then(response => setRates(response.data))
            .catch(error => console.error("Błąd pobierania kursów:", error));
    }, []);

    return (
        <div>
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

export default TodayExchangeRates;
