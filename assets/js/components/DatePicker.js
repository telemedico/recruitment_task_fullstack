import React from 'react';

const DatePicker = ({ chosenDate, handleDateChange, currentDate }) => (
    <div className="form-group mb-0">
        <label htmlFor="datePicker" className="sr-only">Choose Date:</label>
        <input
            type="date"
            id="datePicker"
            value={chosenDate}
            onChange={(e) => handleDateChange(e.target.value)}
            min="2023-01-01"
            max={currentDate}
            className="form-control"
        />
    </div>
);

export default DatePicker;
