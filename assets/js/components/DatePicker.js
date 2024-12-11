import React from 'react';

export default function DatePicker({selectedDate, onChange}) {
    return (
        <div className={"date-picker"}>
            <label htmlFor={"date"}>Select a date: </label>
            <input
                type={"date"}
                id={"date"}
                value={selectedDate}
                onChange={onChange}
            />
        </div>
    );
}