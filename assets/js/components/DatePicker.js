import React, { useState, useEffect } from "react";

function DatePicker({ initialDate }) {
  const [selectedDate, setSelectedDate] = useState("");

  useEffect(() => {
    if (initialDate) {
      setSelectedDate(initialDate);
    }
  }, [initialDate]);

  const handleDateChange = (event) => {
    setSelectedDate(event.target.value);
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
