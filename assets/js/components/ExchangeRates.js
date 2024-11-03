import React, { Component, useState } from "react";
import {
  useHistory,
  useLocation,
} from "react-router-dom/cjs/react-router-dom.min";
import ExchangeRateTile from "./ExchangeRateTile";
import { useEffect } from "react";
import axios from "axios";

// type CurrencyRatesType = {
//   buyRate: number | null,
//   sellRate: number | null,
//   NBPValue:number|null,
// }

// type CurrencyType = {
//   code:string
//   name: string,
//   currentRates: CurrencyRatesType,
//   selectedDateRates:null | CurrencyRatesType,
// };

// type ExchangeRatesType = {
// isAvaibleTodayNPB: bool,
// dateOfRates: string,
// messageGPWNotWorking: string | null,
// currencies: Array<CurrencyType>|null,
// };

export default function ExchangeRates(props) {
  const history = useHistory();
  const location = useLocation();
  const [params, setParams] = useState(new URLSearchParams(location.search));
  const [exchangeRates, setExchangeRates] = useState({});
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const baseUrl = "http://telemedi-zadanie.localhost"; //najlepiej wrzucić to do configa
    let url = baseUrl + "/api/exchange-rates";
    if (params.get("date")) {
      // tutaj przydałby się porządny urlbuilder
      url += `?date=${params.get("date")}`;
    }
    axios
      .get(url)
      .then((response) => {
        setExchangeRates(response.data);
        setLoading(false);
        console.log(response);
      })
      .catch(function (error) {
        console.error(error);
      });
  }, [params.toString()]);

  function getTodayCalendarDate() {
    return new Date().toISOString().split("T")[0];
  }

  function getCalendarDate() {
    if (params.get("date")) {
      return params.get("date");
    }

    return getTodayCalendarDate();
  }

  function setDateUrl(date) {
    if (date !== getTodayCalendarDate()) {
      params.set("date", date);
      history.replace({
        pathname: location.pathname,
        search: params.toString(),
      });
    } else {
      params.delete("date");
      history.replace({
        pathname: location.pathname,
        search: params.toString(),
      });
    }
  }

  function getCurrencies() {
    return Object.values(exchangeRates.currencies).map((currency) => {
      return <ExchangeRateTile key={currency.code} currency={currency} />;
    });
  }

  return (
    <div className="container">
      <div className="exchangeRatesContainer">
        <div className="exchangeRatesContainer__date">
          <label htmlFor="date">Wybierz datę:</label>
          <input
            onChange={(e) => setDateUrl(e.target.value)}
            type="date"
            id="date"
            name="date"
            min="2023-01-01"
            max={getTodayCalendarDate()}
            value={getCalendarDate()}
          />
        </div>
        {!loading && (
          <>
            {exchangeRates.messageGPWNotWorking && (
              <div className="notAvaible">
                Giełda {exchangeRates.messageGPWNotWorking} niepracuje
              </div>
            )}
            {!exchangeRates.isAvaibleTodayNPB && exchangeRates.dateOfRates && (
              <div className="notAvaible">
                Aktualny średni kurs NBP nie jest dostępny ( Ceny są pokazywane
                za dzień {exchangeRates.dateOfRates})
              </div>
            )}
            <div className="exchangeRatesContainer__exchangeRatesTableDescription">
              <div className="exchangeRatesContainer__cellDescription">
                Kod Waluty
              </div>
              <div className="exchangeRatesContainer__cellDescription">
                Nazwa Waluty
              </div>
              <div className="exchangeRatesContainer__cellDescription">
                Średni kurs NBP {exchangeRates.dateOfRates}
              </div>
              {params.get("date") && (
                <div className="exchangeRatesContainer__cellDescription">
                  Wartości waluty z dnia: {params.get("date")}
                </div>
              )}
              <div className="exchangeRatesContainer__cellDescription">
                Kup/Sprzedaj
              </div>
            </div>
            <div className="exchangeRatesContainer__exchangeRates">
              {getCurrencies()}
            </div>
          </>
        )}
      </div>
    </div>
  );
}
