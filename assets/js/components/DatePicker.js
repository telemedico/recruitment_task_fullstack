import React, { useState, useEffect } from "react";

function DatePicker({ initialDate, onDateChange }) {
  const [selectedDate, setSelectedDate] = useState(initialDate);

  useEffect(() => {
    setSelectedDate(initialDate);
  }, [initialDate]);

  const handleDateChange = (event) => {
    const newDate = event.target.value;
    setSelectedDate(newDate);
    onDateChange(newDate); // Notify parent of the date change
  };

  return (
    <div className="form-group">
      <input
        type="date"
        id="datePicker"
        className="form-control"
        value={selectedDate}
        onChange={handleDateChange}
        placeholder="YYYY-MM-DD"
      />
    </div>
  );
}

export default DatePicker;
