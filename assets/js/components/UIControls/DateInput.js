import React, { Fragment } from 'react';

const DateInput = ({id, label, value, onChange, minDate, maxDate}) => {
    return (
        <Fragment>
            <label htmlFor={id}>{label}</label>
            <input
                id={id}
                type="date"
                className="form-control mb-3"
                value={value}
                onChange={onChange}
                min={minDate}
                max={maxDate}
            />
        </Fragment>
    );
};

export default DateInput;