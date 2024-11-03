import React from "react";
// type CurrencyType = {
//   code:string
//   name: string,
//   currentRates: CurrencyRatesType
//   selectedDateRates: CurrencyRatesType
// };

export default function ExchangeRateTile({ currency }) {
  return (
    <div className="exchangeRateTile">
      <div className="exchangeRateTile__container">
        <div className="exchangeRateTile__cell">{currency.code}</div>
        <div className="exchangeRateTile__cell">{currency.name}</div>
        <div className="exchangeRateTile__cell exchangeRateTile__currentValues">
          {currency.currentRates.buyRate && (
            <div>Cena Kupna: {currency.currentRates.buyRate}</div>
          )}
          <div>Cena Sprzedaży: {currency.currentRates.sellRate}</div>
          <div>Średni kurs NBP: {currency.currentRates.NBPValue}</div>
        </div>

        <div className="exchangeRateTile__cell exchangeRateTile__previousValues">
          {currency.selectedDateRates ? (
            <>
              {currency.selectedDateRates.buyRate && (
                <div>Cena Kupna: {currency.selectedDateRates.buyRate}</div>
              )}
              <div>Cena Sprzedaży: {currency.selectedDateRates.sellRate}</div>
              <div>Średni kurs NBP: {currency.selectedDateRates.NBPValue}</div>
            </>
          ) : (
            <>Niedostępne - zmień datę</>
          )}
        </div>
        <div className="exchangeRateTile__cell exchangeRateTile__sellBuyContainer">
          <button
            className="buyButton button"
            disabled={!currency.currentRates.buyRate}
            onClick={() => alert("Kupiono")}
          >
            Buy
          </button>
          <button
            className="sellButton button"
            onClick={() => alert("Sprzedano")}
          >
            Sell
          </button>
        </div>
      </div>
    </div>
  );
}
