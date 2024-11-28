import {useHistory, useLocation} from "react-router-dom";
import {useEffect, useState} from "react";
import {getAdjustedTodayString} from "./date";
import axios from "axios";

const getRates = async (date) =>
    axios.get(`/api/exchange-rates/${date}`)

export default () => {
    const location = useLocation();
    const history = useHistory();
    const [rates, setRates] = useState(null);
    const [todayRates, setTodayRates] = useState(null);
    const params = new URLSearchParams(location.search);
    const dateToday = getAdjustedTodayString();
    const dateFromUrl = params.get('date') || getAdjustedTodayString();

    useEffect( async () => {
       const response = await getRates(dateToday);
       setTodayRates(response.data)
    }, [setTodayRates]);

    useEffect(async () => {
        setRates(null);
        const response = await getRates(dateFromUrl);
        setRates(response.data);
        handleDateChange(dateFromUrl);
    }, [dateFromUrl]);

    const handleDateChange = (newDate) => {
        const updatedParams = new URLSearchParams(location.search);
        updatedParams.set('date', newDate);
        history.push({
            pathname: location.pathname,
            search: updatedParams.toString(),
        });
    };

    return {
        date: dateFromUrl,
        rates,
        todayRates,
        handleDateChange,
    };
};