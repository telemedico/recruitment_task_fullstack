import React from 'react';
import './ExchangeRatesTable.css';
import TrendIndicator from "../TrendIndicator/TrendIndicator";

const ExchangeRatesTable = ({ rates, loading, error, onRowClick }) => {
    const getFlagClass = (code) => {
        switch (code) {
            case 'EUR': return 'fi fi-eu';
            case 'USD': return 'fi fi-us';
            case 'CZK': return 'fi fi-cz';
            case 'IDR': return 'fi fi-id';
            case 'BRL': return 'fi fi-br';
            default: return '';
        }
    };

    return (
        <table className="table table-hover table-bordered exchange-rates-table">
            <thead className="table-dark">
            <tr>
                <th>Currency</th>
                <th>NBP Rate</th>
                <th>Buy Rate</th>
                <th>Sell Rate</th>
                <th>Trend</th>
            </tr>
            </thead>
            <tbody>
            {loading && (
                <tr>
                    <td colSpan="5">Loading...</td>
                </tr>
            )}
            {error && (
                <tr>
                    <td colSpan="5" className="text-danger">Wystąpił błąd ładowania tabeli...</td>
                </tr>
            )}
            {!loading && !error && rates && rates.map(rate => (
                <tr key={rate.code} onClick={() => onRowClick(rate)}>
                    <td>
                        <span className={`flag ${getFlagClass(rate.code)}`} style={{ marginRight: '10px' }}></span>
                        {rate.code} - {rate.name}
                    </td>
                    <td>{rate.nbpRate.toFixed(4)} <TrendIndicator difference={rate.trend} /></td>
                    <td>{rate.buyRate !== null ? rate.buyRate.toFixed(4) : 'N/A'}</td>
                    <td>{rate.sellRate.toFixed(4)}</td>
                    <td><span className="fas fa-arrow-right"></span></td>
                </tr>
            ))}
            </tbody>
        </table>
    );
};

export default ExchangeRatesTable;
