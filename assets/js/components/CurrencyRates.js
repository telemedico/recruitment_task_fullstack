import React, {useEffect, useState} from 'react';
import {useParams, useHistory} from 'react-router-dom';

const CurrencyRates = () => {
    const {date} = useParams();
    const [rates, setRates] = useState({});
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [selectedDate, setSelectedDate] = useState(date || new Date().toISOString().split('T')[0]);
    const history = useHistory();

    useEffect(() => {
        const fetchRates = async() => {
            setLoading(true);
            setError(null);

            try {
                const response = await fetch(`/api/exchange-rates?date=${selectedDate}`);
                if (!response.ok) {
                    const errorData = await response.json();
                    if (errorData.error && errorData.error.includes('Today\'s exchange rates are not updated')) {
                        throw new Error('Current rates aren\'t updated yet. Please pick another date.');
                    }
                    throw new Error(errorData.error || 'Failed to fetch rates');
                }
                const data = await response.json();
                setRates(data);
            } catch (error) {
                console.error('Error fetching rates:', error);
                setError(error.message);
            } finally {
                setLoading(false);
            }
        };

        fetchRates();
    }, [selectedDate]);

    const handleDateChange = (event) => {
        const newDate = event.target.value;
        setSelectedDate(newDate);
        history.push(`/exchange-rates/${newDate}`);
    };

    if (loading) {
        return <div>Loading...</div>;
    }

    return (
        <div>
            <h1>Exchange Rates for {selectedDate}</h1>
            <input
                type="date"
                value={selectedDate} // Controlled input for date
                onChange={handleDateChange} // Handle date change
                min="2023-01-01" // Set minimum date
            />
            {error && <div style={{color: 'red'}}>{error}</div>} {/* Show error message if exists */}
            <h2>Historical Rates</h2>
            <table>
                <thead>
                    <tr>
                        <th>Currency</th>
                        <th>Currency Name</th>
                        <th>Buying Rate</th>
                        <th>Selling Rate</th>
                    </tr>
                </thead>
                <tbody>
                    {Object.entries(rates).map(([code, rate]) => (
                        <tr key={code}>
                            <td>{code} </td>
                            <td>{rate.buy !== null ? rate.buy : 'N/A'}</td>
                            {/* Handle null buy rate */}
                            <td>{rate.sell !== null ? rate.sell : 'N/A'}</td>
                            {/* Handle null sell rate */}
                        </tr>
                        ))}
                </tbody>
            </table>
            <h2>Today's Rates</h2>
            <table>
                <thead>
                    <tr>
                    <th>Currency</th>
                        <th>Currency Name</th>
                        <th>Today's Buying Rate</th>
                        <th>Today's Selling Rate</th>
                    </tr>
                </thead>
                <tbody>
                    {Object.entries(rates).map(([code, rate]) => (
                        <tr key={code}>
                            <td>{code} </td>
                            <td>{rate.name}</td>
                            <td>{rate.buy !== null ? rate.buy : 'N/A'}</td>
                            {/* Handle null buy rate */}
                            <td>{rate.sell !== null ? rate.sell : 'N/A'}</td>
                            {/* Handle null sell rate */}
                        </tr>
                        ))}
                </tbody>
            </table>
        </div>
    );
};

export default CurrencyRates;
