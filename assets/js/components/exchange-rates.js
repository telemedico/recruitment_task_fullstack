import React, { useState, useEffect, useRef } from "react";
import axios from "axios";

const formatCurrency = (number) => {
  if (typeof number !== "number") return null;
  return number.toFixed(4);
};

const createUrl = (date) => {
  const newUrl = `${location.origin}/exchange-rates/${date}`;
  history.pushState(null, null, newUrl);
};

const ExchangeRates = () => {
  const {
    date: serverDate,
    supportedCurrencies,
    convertibleCurrencies,
    buyMarginForConvertibleCurrencies,
    sellMarginForConvertibleCurrencies,
    sellMargin,
    NBPdata,
  } = window.SERVER_DATA;
  const { rates } = NBPdata;

  const now = new Date();
  const today = now.toISOString().substring(0, 10);

  const currencyDateRef = useRef();

  const [currentDate, setCurrentDate] = useState(serverDate);
  const [currencyTable, setCurrencyTable] = useState(null);

  const isCurrencyConvertible = (code) => {
    return convertibleCurrencies.find((curr) => code === curr) ? true : false;
  };

  const onDataChange = (ev) => {
    fetchCurrencyRates(ev.target.value);
  };

  const updateCurrencyTable = (rates) => {
    const currentData = [...initialCurrencyTable];
    if (rates !== undefined) {
      currentData.forEach((currency) => {
        const pastCurrency = rates.find((curr) => curr.code === currency.code);
        currency.past.bank = pastCurrency.mid;
        if (isCurrencyConvertible(currency.code)) {
          currency.past.buy =
            currency.past.bank + buyMarginForConvertibleCurrencies;
          currency.past.sell =
            currency.past.bank + sellMarginForConvertibleCurrencies;
        } else {
          currency.past.buy = null;
          currency.past.sell = currency.past.bank + sellMargin;
        }
      });
    } else {
      currentData.forEach((currency) => {
        currency.past.bank = null;
        currency.past.buy = null;
        currency.past.sell = null;
      });
    }
    setCurrencyTable(currentData);
  };

  const fetchCurrencyRates = (date) => {
    const baseUrl = "http://telemedi-zadanie.localhost";
    createUrl(date);
    setCurrentDate(date);
    axios
      .get(baseUrl + `/api/exchange-rates/${date}`)
      .then((response) => {
        const { rates } = response.data;
        updateCurrencyTable(rates);
      })
      .catch(function (error) {
        console.error(error);
      });
  };

  const createCurrencyTable = (rates) => {
    const data = [];
    for (const code of supportedCurrencies) {
      const currency = {
        current: {},
        past: {},
      };
      const supported = rates.find((currency) => currency.code === code);
      currency.code = supported.code;
      currency.name = supported.currency;
      const isDateToday = today === serverDate;
      currency.current.bank = supported.mid;
      currency.past.bank = isDateToday ? supported.mid : null;
      if (isCurrencyConvertible(currency.code)) {
        currency.current.buy =
          currency.current.bank + buyMarginForConvertibleCurrencies;
        currency.current.sell =
          currency.current.bank + sellMarginForConvertibleCurrencies;
        currency.past.buy = isDateToday ? currency.current.buy : null;
        currency.past.sell = isDateToday ? currency.current.sell : null;
      } else {
        currency.current.buy = null;
        currency.current.sell = currency.current.bank + sellMargin;
        currency.past.buy = null;
        currency.past.sell = isDateToday ? currency.current.sell : null;
      }
      data.push(currency);
    }
    return data;
  };

  let initialCurrencyTable = createCurrencyTable(rates);

  useEffect(() => {
    currencyDateRef.current.value = serverDate;
    setCurrencyTable(initialCurrencyTable);
    if (today !== serverDate) {
      fetchCurrencyRates(serverDate);
    }
  }, []);

  return (
    <>
      <h3 className="text-center mt-5">
        Tablica z kursami walut na dzień {currentDate}
      </h3>

      <table className="table table-bordered my-5">
        <thead>
          <tr>
            <th scope="col" rowSpan={2} className="text-center align-middle">
              nazwa waluty
            </th>
            <th scope="col" rowSpan={2} className="text-center align-middle">
              kod
            </th>

            <th scope="col" colSpan={3} className="text-center bg-light">
              {currentDate}
            </th>
            <th scope="col" colSpan={3} className="text-center">
              bieżący
            </th>
          </tr>
          <tr>
            <th scope="col" className="text-center bg-light">
              NBP
            </th>
            <th scope="col" className="text-center bg-light">
              kupno
            </th>
            <th scope="col" className="text-center bg-light">
              sprzedaż
            </th>
            <th scope="col" className="text-center">
              NBP
            </th>
            <th scope="col" className="text-center">
              kupno
            </th>
            <th scope="col" className="text-center">
              sprzedaż
            </th>
          </tr>
        </thead>
        <tbody>
          {currencyTable
            ? currencyTable.map((row, key) => (
                <tr key={key}>
                  <td>{row.name}</td>
                  <td className="text-center">{row.code}</td>
                  <td className="text-center bg-light">
                    {formatCurrency(row.past.bank)}
                  </td>
                  <td className="text-center bg-light">
                    {formatCurrency(row.past.buy)}
                  </td>
                  <td className="text-center bg-light">
                    {formatCurrency(row.past.sell)}
                  </td>
                  <td className="text-center">
                    {formatCurrency(row.current.bank)}
                  </td>
                  <td className="text-center">
                    {formatCurrency(row.current.buy)}
                  </td>
                  <td className="text-center">
                    {formatCurrency(row.current.sell)}
                  </td>
                </tr>
              ))
            : null}
        </tbody>
      </table>
      <div className="form-group d-flex align-items-center">
        <label htmlFor="currencyDate" className="mb-0 pr-3">
          Wybierz datę
        </label>
        <input
          id="currencyDate"
          name="currencyDate"
          type="date"
          min="2023-01-01"
          max={today}
          onChange={onDataChange}
          ref={currencyDateRef}
          className="form-control w-25"
        />
      </div>
    </>
  );
};

export default ExchangeRates;
