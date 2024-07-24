import React from 'react';

const ExchangeRatesTable = ({ rates }) => {
    if (!rates) return <div>Loading...</div>;

    return (
        <table>
            <thead>
            <tr>
                <th>Currency</th>
                <th>NBP Rate</th>
                <th>Buy Rate</th>
                <th>Sell Rate</th>
            </tr>
            </thead>
            <tbody>
            {rates.map(rate => (
                <tr key={rate.code}>
                    <td>{rate.code} - {rate.name}</td>
                    <td>{rate.nbpRate}</td>
                    <td>{rate.buyRate !== null ? rate.buyRate : 'N/A'}</td>
                    <td>{rate.sellRate}</td>
                </tr>
            ))}
            </tbody>
        </table>
    );
};

export default ExchangeRatesTable;
