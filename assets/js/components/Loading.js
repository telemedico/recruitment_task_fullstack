import React, { useEffect, useState } from 'react';
import { useParams, useHistory } from 'react-router-dom';
import DateSelector from './DateSelector';
import RatesTable from './RatesTable';
import Loading from './Loading';
import ErrorMessage from './ErrorMessage';
import '../../css/CurrencyRates.css';

const CurrencyRates = () => {
    const { date } = useParams();
    const [rates, setRates] = useState({});
    const [todayRates, setTodayRates] = useState({});
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [selectedDate, setSelectedDate] = useState(date || new Date().toISOString().split('T')[0]);
    const history = useHistory();

    const today = new Date().toISOString().split('T')[0];

    useEffect(() => {
        const fetchRates = async () => {
            setLoading(true);
            setError(null);

            try {
                if (selectedDate === today) {
                    const todayResponse = await fetch(`/api/exchange-rates/today`);
                    if (!todayResponse.ok) throw new Error('Failed to fetch today\'s rates');
                    const todayData = await todayResponse.json();
                    setTodayRates(todayData);
                    setRates({});
                } else {
                    const historicalResponse = await fetch(`/api/exchange-rates?date=${selectedDate}`);
                    if (!historicalResponse.ok) throw new Error('Failed to fetch historical rates');
                    const historicalData = await historicalResponse.json();
                    setRates(historicalData);

                    const todayResponse = await fetch(`/api/exchange-rates/today`);
                    if (!todayResponse.ok) throw new Error('Failed to fetch today\'s rates');
                    const todayData = await todayResponse.json();
                    setTodayRates(todayData);
                }
            } catch (error) {
                console.error('Error fetching rates:', error);
                setError(error.message);
            } finally {
                setLoading(false);
            }
        };

        fetchRates();
    }, [selectedDate, today]);

    const handleDateChange = (newDate) => {
        setSelectedDate(newDate);
        history.push(`/exchange-rates/${newDate}`);
    };

    return (
        <div className="currency-rates-container">
            <h1>Exchange Rates for {selectedDate}</h1>
            <DateSelector selectedDate={selectedDate} onDateChange={handleDateChange} />

            {error && <ErrorMessage message={error} />}
            {loading ? (
                <Loading />
            ) : (
                <>
                    {selectedDate !== today && (
                        <>
                            <h2>Historical Rates (for {selectedDate})</h2>
                            <RatesTable rates={rates} todayRates={todayRates} />
                        </>
                    )}
                    <h2>{selectedDate === today ? 'Today\'s Rates' : 'Today\'s Rates for Comparison'}</h2>
                    <RatesTable rates={todayRates} />
                </>
            )}
        </div>
    );
};

export default CurrencyRates;
