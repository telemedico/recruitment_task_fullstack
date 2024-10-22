import axios from 'axios';


export const fetchTodayRates = async () => {
    const response = await axios.get('/api/exchange-rates/today');
    return response.data;
};


export const fetchHistoricalRates = async (date) => {
    const response = await axios.get(`/api/exchange-rates?date=${date}`);
    return response.data;
};
