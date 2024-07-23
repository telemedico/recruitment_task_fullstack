import React, { useState, useEffect } from 'react';
import PropTypes from 'prop-types';
import ExchangeRateService from "../Api/ExchangeRateService";

const DataTable = ({data}) => {
    const [sortConfig, setSortConfig] = useState({ key: null, direction: 'ascending' });

    return (
        <>
            <table>
                <thead>
                <tr>
                    {data.rates && data.rates.length > 0 && Object.keys(data.rates[0]).map(key => (
                        <th key={key} onClick={() => requestSort(key)}>
                            {key}
                            {sortConfig.key === key ? (sortConfig.direction === 'ascending' ? ' 🔼' : ' 🔽') : null}
                        </th>
                    ))}
                </tr>
                </thead>
                <tbody>
                {data.rates && data.rates.map((item, index) => (
                    <tr key={index}>
                        {Object.values(item).map((val, idx) => <td key={idx}>{val}</td>)}
                    </tr>
                ))}
                </tbody>
            </table>
        </>
    );
};

DataTable.propTypes = {
    data: PropTypes.arrayOf(PropTypes.object).isRequired,
};

export default DataTable;
