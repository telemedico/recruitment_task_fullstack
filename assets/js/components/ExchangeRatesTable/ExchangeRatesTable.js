import React from 'react';
import TrendIndicator from '../TrendIndicator/TrendIndicator';
import './ExchangeRatesTable.css';

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

    const calculateTrend = (todayRate, selectedRate) => {
        return todayRate !== null && selectedRate !== null ? selectedRate - todayRate : null;
    };

    return (
        <table className="table table-hover table-bordered exchange-rates-table text-center">
            <thead className="table-dark">
            <tr>
                <th rowSpan="2">Currency</th>
                <th colSpan="3" className="today-column">Today Rates</th>
                <th colSpan="4" className="selected-column">Selected Date Rates</th>
            </tr>
            <tr>
                <th className="today-column">NBP Rate</th>
                <th className="today-column">Buy Rate</th>
                <th className="today-column">Sell Rate</th>
                <th className="selected-column">NBP Rate</th>
                <th className="selected-column">Trend</th>
                <th className="selected-column">Buy Rate</th>
                <th className="selected-column">Sell Rate</th>
            </tr>
            </thead>
            <tbody>
            {loading && (
                <tr>
                    <td colSpan="8">Loading...</td>
                </tr>
            )}
            {error && (
                <tr>
                    <td colSpan="8" className="text-danger">Wystąpił błąd ładowania tabeli...</td>
                </tr>
            )}
            {!loading && !error && rates && rates.map(rate => {
                const trend = calculateTrend(rate.todayNbpRate, rate.nbpRate);
                return (
                    <tr key={rate.code} onClick={() => onRowClick(rate)}>
                        <td>
                            <span className={`flag ${getFlagClass(rate.code)}`} style={{ marginRight: '10px' }}></span>
                            {rate.code} - {rate.name}
                        </td>
                        <td className="today-column">{rate.todayNbpRate !== null ? rate.todayNbpRate.toFixed(4) : 'N/A'}</td>
                        <td className="today-column">{rate.todayBuyRate !== null ? rate.todayBuyRate.toFixed(4) : 'N/A'}</td>
                        <td className="today-column">{rate.todaySellRate !== null ? rate.todaySellRate.toFixed(4) : 'N/A'}</td>
                        <td className="selected-column">{rate.nbpRate.toFixed(4)}</td>
                        <td className="selected-column">
                            {trend !== null && <TrendIndicator value={trend} />}
                        </td>
                        <td className="selected-column">{rate.buyRate !== null ? rate.buyRate.toFixed(4) : 'N/A'}</td>
                        <td className="selected-column">{rate.sellRate.toFixed(4)}</td>
                    </tr>
                );
            })}
            </tbody>
        </table>
    );
};

export default ExchangeRatesTable;
