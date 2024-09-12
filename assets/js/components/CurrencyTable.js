import React from 'react';

const CurrencyTable = ({ currencyRates, loading, chosenDate, currentDate }) => {
    const headers = [
        { title: '', colspan: 1 },
        { title: '', colspan: 1 },
        { title: `${chosenDate}`, colspan: 3 },
        { title: `${currentDate}`, colspan: 3 }
    ];

    const subHeaders = ['NBP Rate', 'Purchase', 'Sale'];

    return (
        <table className="table mx-auto">
            <thead>
            <tr>
                {headers.map((header, index) => (
                    <th key={index} colSpan={header.colspan} className="text-center">
                        {header.title}
                    </th>
                ))}
            </tr>
            <tr>
                <th className="text-center">Code</th>
                <th className="text-center">Name</th>
                {subHeaders.map((subHeader, index) => (
                    <th key={index} className="text-center">{subHeader}</th>
                ))}
                {subHeaders.map((subHeader, index) => (
                    <th key={index + 3} className="text-center">{subHeader}</th>
                ))}
            </tr>
            </thead>
            <tbody>
            {loading ? (
                <tr>
                    <td colSpan="8">Loading...</td>
                </tr>
            ) : (
                Object.keys(currencyRates).length > 0 ? (
                    Object.keys(currencyRates).map((code, index) => {
                        const rate = currencyRates[code];
                        return (
                            <tr key={index}>
                                <th scope="row">{code}</th>
                                <td>{rate.currency}</td>
                                <td>{rate[`mid_${chosenDate}`] || 'N/A'}</td>
                                <td className="text-danger">{rate[`purchase_${chosenDate}`] || 'N/A'}</td>
                                <td className="text-success">{rate[`sale_${chosenDate}`] || 'N/A'}</td>
                                <td>{rate[`mid_${currentDate}`] || 'N/A'}</td>
                                <td className="text-danger">{rate[`purchase_${currentDate}`] || 'N/A'}</td>
                                <td className="text-success">{rate[`sale_${currentDate}`] || 'N/A'}</td>
                            </tr>
                        );
                    })
                ) : (
                    <tr>
                        <td colSpan="8">No data available</td>
                    </tr>
                )
            )}
            </tbody>
        </table>
    );
};

export default CurrencyTable;
