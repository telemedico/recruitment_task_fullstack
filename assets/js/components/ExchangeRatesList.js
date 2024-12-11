import React, {useEffect, useState} from 'react';
import axios from "axios";
import {useHistory, useParams} from "react-router-dom";
import ExchangeRateItem from "./ExchangeRateItem";
import DatePicker from "./DatePicker";

export default function ExchangeRatesList() {
    const history = useHistory();
    const {date} = useParams();
    const today = getTodayDate();
    const requestDate = isValidDate(date) ? date : today;

    const [exchangeRatesByDate, setExchangeRatesByDate] = useState(JSON.parse(localStorage.getItem('exchangeRatesToday-' + today) ?? '[]'));
    const [exchangeRatesToday, setExchangeRatesToday] = useState([]);
    const [selectedDate, setSelectedDate] = useState(requestDate);

    useEffect(() => {
        axios
            .get('/api/v1/exchange-rates?requestDate=' + requestDate)
            .then(response => {
                setExchangeRatesByDate(response.data);

                if (today !== requestDate) {
                    let todayRates = localStorage.getItem('exchangeRatesToday-' + today);

                    if (!todayRates) {
                        axios.get('/api/v1/exchange-rates?requestDate=' + today).then(response => {
                            setExchangeRatesToday(response.data);
                            localStorage.setItem('exchangeRatesToday-' + today, JSON.stringify(response.data));
                        });
                    } else {
                        setExchangeRatesToday(JSON.parse(todayRates));
                    }
                } else {
                    setExchangeRatesToday(response.data);
                }
            });
    }, [selectedDate]);

    const handleChangeDate = (event) => {
        const newDate = event.target.value;

        if (!isValidDate(newDate)) {
            alert(`Invalid date! Please, choose a valid date (must be after 2023-01-01 and before ${today}).`);

            setSelectedDate(today);
            history.push(`/exchange-rates/${today}`);

            return;
        }

        setSelectedDate(newDate);
        history.push(`/exchange-rates/${newDate}`);
    }

    return (
        <div>
            <DatePicker selectedDate={selectedDate} onChange={handleChangeDate}/>

            {exchangeRatesByDate.length > 0
                ? (
                    <div className={"exchange-rates"}>
                        <div className={"table-header"}>
                            <div className={"header-cell"}>Currency (Code)</div>
                            <div className={"header-cell"}>Mid Rate ({requestDate})</div>
                            <div className={"header-cell"}>Buy Rate ({requestDate})</div>
                            <div className={"header-cell"}>Sell Rate ({requestDate})</div>
                            <div className={"header-cell"}>Mid Rate ({today})</div>
                            <div className={"header-cell"}>Buy Rate ({today})</div>
                            <div className={"header-cell"}>Sell Rate ({today})</div>
                        </div>

                        {exchangeRatesByDate.map(exchangeRate => {
                            return (
                                <div key={exchangeRate.code} className={"table-row"}>
                                    <ExchangeRateItem rate={exchangeRate} listRatesToday={exchangeRatesToday}/>
                                </div>
                            )
                        })}
                    </div>
                ) : (<div className={'no-data-message'}>No data</div>)
            }
        </div>
    );
}

const getTodayDate = () => {
    const now = new Date();

    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
};

const isValidDate = (requestDate) => {
    const regex = /^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/;

    if (!regex.test(requestDate)) {
        return false;
    }

    if (new Date(requestDate) > new Date(getTodayDate())) {
        return false;
    }

    if (new Date(requestDate) < new Date('2023-01-01')) {
        return false;
    }

    // Ensure the date is a valid calendar date
    const date = new Date(requestDate);
    return date instanceof Date && !isNaN(date);
}
