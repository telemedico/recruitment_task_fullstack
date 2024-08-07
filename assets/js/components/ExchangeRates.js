import React, {useEffect, useState} from 'react';
import axios from 'axios';
import {useLocation, useHistory} from 'react-router-dom';
import DatePicker from 'react-datepicker';
import 'react-datepicker/dist/react-datepicker.css';
import config from '../config';

function ExchangeRates() {
    const [data, setData] = useState(null);
    const [loading, setLoading] = useState(true);
    const [selectedDate, setSelectedDate] = useState(new Date());
    const location = useLocation();
    const history = useHistory();
    const today = new Date().toISOString().split('T')[0];

    const queryParams = new URLSearchParams(location.search);
    const date = queryParams.get('date') || today;

    useEffect(() => {
        if (date) {
            setSelectedDate(new Date(date));
        }
    }, [date]);

    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await axios.get(config.baseUrl + '/api/get-currencies', {
                    params: {date: date}
                });
                setData(response.data);
            } catch (error) {
                console.error(error);
                setData(null);
            } finally {
                setLoading(false);
            }
        };

        fetchData();
    }, [date]);

    const handleDateChange = (date) => {
        setSelectedDate(date);
        const formattedDate = date.toISOString().split('T')[0];
        history.push(`?date=${formattedDate}`);
    };

    const renderTable = (data) => {
        const {today, date} = data;
        const allCurrencies = new Set([...Object.keys(today), ...Object.keys(date)]);

        return (
            <table className="table table-bordered">
                <thead>
                <tr>
                    <th>Currency</th>
                    <th>Code</th>
                    <th>Buy</th>
                    <th>Sell</th>
                    <th>Buy (Today)</th>
                    <th>Sell (Today)</th>
                </tr>
                </thead>
                <tbody>
                {Array.from(allCurrencies).map(key => {
                    const todayData = today[key] || {};
                    const dateData = date[key] || {};

                    return (
                        <tr key={key}>
                            <td>{dateData.currency || 'N/A'}</td>
                            <td>{dateData.code || 'N/A'}</td>
                            <td>{dateData.buy !== null ? dateData.buy : 'N/A'}</td>
                            <td>{dateData.sell !== null ? dateData.sell : 'N/A'}</td>
                            <td>{todayData.buy !== null ? todayData.buy : 'N/A'}</td>
                            <td>{todayData.sell !== null ? todayData.sell : 'N/A'}</td>
                        </tr>
                    );
                })}
                </tbody>
            </table>
        );
    };

    const minDate = new Date('2023-01-01');
    const displayDate = !date || date === today ? 'today' : date;

    return (
        <div>
            <section className="row-section">
                <div className="container">
                    <div className="row mt-5">
                        <div className="col-md-8 offset-md-2">
                            <h2 className="text-center">
                                <span>Currencies for {displayDate}</span>
                            </h2>

                            <div className="text-center mt-3">
                                <DatePicker
                                    selected={date}
                                    onChange={handleDateChange}
                                    dateFormat="yyyy-MM-dd"
                                    minDate={minDate}
                                    maxDate={today}
                                />
                            </div>

                            {loading ? (
                                <div className={'text-center'}>
                                    <span className="fa fa-spin fa-spinner fa-4x"></span>
                                </div>
                            ) : (
                                !data || data.error ? (
                                    <div className={'text-center mt-4'}>
                                        {data && data.error ? (
                                            <h3 className={'text-error text-bold'}>
                                                <strong>{data.error}</strong>
                                            </h3>
                                        ) : (
                                            <h3 className={'text-error text-bold'}>
                                                <strong>Unexpected error</strong>
                                            </h3>
                                        )}
                                    </div>
                                ) : (
                                    <div className={'mt-4'}>
                                        {renderTable(data)}
                                    </div>
                                )
                            )}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    );
}

export default ExchangeRates;
