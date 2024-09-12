import React, { useState, useEffect } from 'react';
import axios from 'axios';
import CurrencyTable from './CurrencyTable';
import DatePicker from './DatePicker';

const CurrencyRate = () => {
    const [currencyRates, setCurrencyRates] = useState({});
    const [loading, setLoading] = useState(true);
    const [currentDate, setCurrentDate] = useState(getCurrentDate());
    const [chosenDate, setChosenDate] = useState(getCurrentDate());

    useEffect(() => {
        fetchRates(currentDate, 'current');
    }, [currentDate]);

    function getCurrentDate() {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    const fetchRates = async (date, type) => {
        const baseUrl = 'http://telemedi-zadanie.localhost';
        const url = `${baseUrl}/api/v1/exchange-rates/${date}`;

        try {
            const response = await axios.get(url);
            if (response.data && response.status === 200) {
                const rates = response.data;
                setCurrencyRates(prevState => {
                    const updatedRates = { ...prevState };
                    rates.forEach(rate => {
                        if (!updatedRates[rate.code]) {
                            updatedRates[rate.code] = {};
                        }
                        updatedRates[rate.code] = {
                            ...updatedRates[rate.code],
                            currency: rate.currency,
                            [`mid_${date}`]: rate.mid,
                            [`purchase_${date}`]: rate.purchase,
                            [`sale_${date}`]: rate.sale,
                        };
                    });
                    return updatedRates;
                });
            }
        } catch (error) {
            console.error(error);
        } finally {
            setLoading(false);
        }
    };

    const handleDateChange = (newDate) => {
        setChosenDate(newDate);
        fetchRates(newDate, 'chosen');
    };

    return (
        <div>
            <section className="row-section">
                <div className="container">
                    <div className="row mt-5">
                        <div className="col-md-12">
                            <div className="d-flex justify-content-between align-items-center mb-3">
                                <h1>Currency Courses</h1>
                                <DatePicker
                                    chosenDate={chosenDate}
                                    handleDateChange={handleDateChange}
                                    currentDate={currentDate}
                                />
                            </div>
                            <CurrencyTable
                                currencyRates={currencyRates}
                                loading={loading}
                                chosenDate={chosenDate}
                                currentDate={currentDate}
                            />
                        </div>
                    </div>
                </div>
            </section>
        </div>
    );
};

export default CurrencyRate;
