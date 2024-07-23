// apiService.js

import axios from 'axios';

const BASE_URL = 'http://telemedi-zadanie.localhost/api'; // Zmień to na URL twojego API

const getSortedData = async (selectedDate) => {
    try {
        let url = `${BASE_URL}/exchange-rates`;
        if (selectedDate) {
            url = `${BASE_URL}/exchange-rates?date=${selectedDate}`;
        }
        const response = await axios.get(url);
        return response.data;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};

export default {
    getSortedData,
};
