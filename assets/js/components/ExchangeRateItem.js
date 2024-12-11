import React from 'react';

export default function ExchangeRateItem({ rate, listRatesToday }) {
    const rateToday = listRatesToday.find(rateToday => rateToday.code === rate.code);

    return (
        <>
            <div className={"table-cell"}>
                {rate.name} ({rate.code})
            </div>
            <div className={"table-cell"}>
                {rate.exchangeRate.mid}
            </div>
            <div className={"table-cell"}>
                {rate.exchangeRate.buyRate?? '---'}
            </div>
            <div className={"table-cell"}>
                {rate.exchangeRate.sellRate}
            </div>
            <div className={"table-cell"}>
                {rateToday?.exchangeRate.mid ?? '---'}
            </div>
            <div className={"table-cell"}>
                {rateToday?.exchangeRate.buyRate ?? '---'}
            </div>
            <div className={"table-cell"}>
                {rateToday?.exchangeRate.sellRate ?? '---'}
            </div>
        </>
    );
}