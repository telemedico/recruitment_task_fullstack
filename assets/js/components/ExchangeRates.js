import React, { useState, useEffect, useCallback } from "react";
import axios from "axios";

const ExchangeRates = () => {
  const [date, setDate] = useState(new Date().toISOString().slice(0, 10));
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [key, setKey] = useState(0);

  const baseUrl = "http://telemedi-zadanie.localhost";

  const isWeekend = useCallback((date) => {
    const d = new Date(date);
    return d.getDay() === 0 || d.getDay() === 6;
  }, []);

  const fetchData = useCallback(
    async (selectedDate) => {
      console.log("Fetching data for date:", selectedDate);
      setLoading(true);
      setError(null);
      setData(null);

      try {
        const response = await axios.get(
          `${baseUrl}/api/exchange-rates?date=${selectedDate}`,
          {
            headers: {
              "Cache-Control": "no-cache",
              Pragma: "no-cache",
              Expires: "0",
            },
          }
        );
        console.log("API Response:", response.data);
        setData(response.data);
        setKey((prevKey) => prevKey + 1);
      } catch (err) {
        console.error("API Error:", err);
        setError(err.response?.data?.error || "Unexpected error");
        setData(null);
      } finally {
        setLoading(false);
      }
    },
    [baseUrl]
  );

  useEffect(() => {
    fetchData(date);
  }, [date, fetchData]);

  const handleDateChange = useCallback((e) => {
    const newDate = e.target.value;
    console.log("New date selected:", newDate);
    setDate(newDate);
  }, []);

  const renderTableContent = useCallback(() => {
    if (!data) return null;

    const {
      today,
      date: historicalDate,
      historical: historicalRates = [],
      current: currentRates = [],
    } = data;

    const currentMap = currentRates.reduce((acc, rate) => {
      acc[rate.code] = rate;
      return acc;
    }, {});

    return (
      <div className="table-responsive" key={key}>
        <h5 className="section-subtitle">
          Rates for selected date: {historicalDate}
        </h5>
        <h6 className="section-subtitle">
          Comparison with today's rates: {today}
        </h6>
        <table>
          <thead>
            <tr>
              <th>Code</th>
              <th>Currency</th>
              <th colSpan="3">Selected Date ({historicalDate})</th>
              <th colSpan="3">Today ({today})</th>
            </tr>
            <tr>
              <th></th>
              <th></th>
              <th>NBP Rate</th>
              <th>Buy Rate</th>
              <th>Sell Rate</th>
              <th>NBP Rate</th>
              <th>Buy Rate</th>
              <th>Sell Rate</th>
            </tr>
          </thead>
          <tbody>
            {historicalRates.map((hr) => {
              const cr = currentMap[hr.code] || {};
              return (
                <tr key={`${hr.code}-${historicalDate}-${key}`}>
                  <td>{hr.code}</td>
                  <td>{hr.currency}</td>
                  <td>{hr.nbpRate}</td>
                  <td>{hr.buyRate !== null ? hr.buyRate : "—"}</td>
                  <td>{hr.sellRate}</td>
                  <td>{cr.nbpRate || "—"}</td>
                  <td>{cr.buyRate !== null ? cr.buyRate : "—"}</td>
                  <td>{cr.sellRate || "—"}</td>
                </tr>
              );
            })}
          </tbody>
        </table>
      </div>
    );
  }, [data, key]);

  return (
    <div>
      <h2 className="page-title">Exchange Rates</h2>
      <div className="mb-20">
        <label htmlFor="date" className="mr-10">
          Select date:
        </label>
        <input
          type="date"
          id="date"
          value={date}
          onChange={handleDateChange}
          min="2023-01-01"
          max={new Date().toISOString().slice(0, 10)}
          className="date-input"
        />
        {isWeekend(date) && (
          <div className="info-message mt-2">
            Note: For weekends, rates from the last working day are shown
          </div>
        )}
      </div>

      {loading && (
        <div className="spinner-container" role="status">
          <div className="spinner"></div>
        </div>
      )}

      {error && <div className="error-message">{error}</div>}

      {!loading && !error && renderTableContent()}
    </div>
  );
};

export default React.memo(ExchangeRates);
