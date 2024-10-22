import React from 'react';

const DateSelector = ({ selectedDate, onDateChange }) => {
    return (
        <input
            type="date"
            value={selectedDate}
            onChange={(e) => onDateChange(e.target.value)}
            min="2023-01-01"
            className="date-input"
        />
    );
};

export default DateSelector;
