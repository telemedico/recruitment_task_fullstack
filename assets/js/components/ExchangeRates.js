

import React, { useState, useEffect } from 'react';
import DatePicker from 'react-datepicker';
import 'react-datepicker/dist/react-datepicker.css';
import { format } from 'date-fns';


const ExchangeRates = () => {
  const [exchangeRates, setExchangeRates] = useState([]);
  const [isLoading, setIsLoading] = useState(true);
  const [selectedDate, setSelectedDate] = useState(new Date());

  const handleChange = date => {
      setSelectedDate(date);
  };

  const formatDate = (date) => {
    return format(date, 'yyyy-MM-dd');
};


  const handleClick = () => {
    const formattedDate = formatDate(selectedDate);
    const encodedDate = encodeURIComponent(formattedDate);

    fetch('http://telemedi-zadanie.localhost/api/exchange-rates?date=' + encodedDate) //
  .then(response => response.json())
  .then(data => {
    console.log(data);
    setExchangeRates(data);
    setIsLoading(false);
  })
  .catch(error => {
    console.error('Error fetching data:', error);
    setIsLoading(false);
  })
};

  if (isLoading) {
    return <div>  <div>
    <h1>Choose a Date</h1>
    <div>
    <DatePicker 
        selected={selectedDate} 
        onChange={handleChange} 
    />
    </div>
        <button onClick={handleClick}>wyszukaj</button>
    </div></div>;
  }

  return (
    <div>
        <div>
            <h1>Choose a Date</h1>
            <div>
            <DatePicker 
                selected={selectedDate} 
                onChange={handleChange} 
            />
        </div>
            <button onClick={handleClick}>wyszukaj</button>
        </div>
        <table>
        <thead>
            <tr>
            <th>Currency</th>
            <th>Code</th>
            <th>Mid</th>
            </tr>
        </thead>
        <tbody>
            {exchangeRates.map((rate, index) => (
            <tr key={index}>
                <td>{rate.currency}</td>
                <td>{rate.code}</td>
                <td>{rate.mid}</td>
            </tr>
            ))}
        </tbody>
        </table>
        </div>
    
  );
};

export default ExchangeRates;