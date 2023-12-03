import React, { useEffect, useState } from 'react';
import axios from 'axios';

const ExchangeRates = () => {
    const [rates, setRates] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        axios.get('/api/exchange-rates')
            .then(response => {
                console.log(response)
                if(response.data && Array.isArray(response.data[0].rates)) {
                    setRates(response.data[0].rates);
                } else {
                    setRates([]);
                }
                setLoading(false);
            })
            .catch(error => {
                console.error('There was an error!', error);
                setRates([]);
                setLoading(false);
            });
    }, []);

    return (
        <div>
            <h2>Exchange Rates</h2>
            {loading ? <p>Loading...</p> : (
                rates.length > 0 ? (
                    <ul>
                        {rates.map(rate => (
                            <li key={rate.code}>{rate.currency}: {rate.mid}</li>
                        ))}
                    </ul>
                ) : <p>No data available.</p>
            )}
        </div>
    );
};

export default ExchangeRates;
