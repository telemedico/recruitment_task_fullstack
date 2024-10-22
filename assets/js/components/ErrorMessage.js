import React from 'react';

const RateRow = ({ code, rate, todayRate, calculatePercentageChange }) => {
    return (
        <tr>
            <td>{code}</td>
            <td>{rate.name || 'N/A'}</td>
            <td>{rate.buy !== null ? rate.buy : 'N/A'}</td>
            <td>{rate.sell !== null ? rate.sell : 'N/A'}</td>
            {todayRate && (
                <>
                    <td>
                        {rate.buy !== null && todayRate?.buy !== null
                            ? `${calculatePercentageChange(rate.buy, todayRate.buy)}%`
                            : 'N/A'}
                    </td>
                    <td>
                        {rate.sell !== null && todayRate?.sell !== null
                            ? `${calculatePercentageChange(rate.sell, todayRate.sell)}%`
                            : 'N/A'}
                    </td>
                </>
            )}
        </tr>
    );
};

export default RateRow;
