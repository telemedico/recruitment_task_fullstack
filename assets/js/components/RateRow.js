import React from 'react';
import RateRow from './RateRow';

const RatesTable = ({ rates, todayRates }) => {
    const calculatePercentageChange = (historicalRate, todayRate) => {
        if (!historicalRate || !todayRate) return null;
        return (((todayRate - historicalRate) / historicalRate) * 100).toFixed(2);
    };

    return (
        <table className="rates-table">
            <thead>
                <tr>
                    <th>Currency</th>
                    <th>Currency Name</th>
                    <th>Buying Rate</th>
                    <th>Selling Rate</th>
                    {todayRates && <th>Percentage Change (Buy)</th>}
                    {todayRates && <th>Percentage Change (Sell)</th>}
                </tr>
            </thead>
            <tbody>
                {Object.entries(rates).map(([code, rate]) => (
                    <RateRow
                        key={code}
                        code={code}
                        rate={rate}
                        todayRate={todayRates ? todayRates[code] : null}
                        calculatePercentageChange={calculatePercentageChange}
                    />
                ))}
            </tbody>
        </table>
    );
};

export default RatesTable;
