import React from "react"
import useBackendAPI from "../../hooks/useBackendAPI";
import {getCurrentDate} from "../../utils";
import ExchangeRateRow from "./ExchangeRatesRow";

export default function ExchangeRatesTable({date}) {
    const {data, loading} = useBackendAPI(date);

    if (loading) {
        return <div className={'text-center'}>
            <span className="fa fa-spin fa-spinner fa-4x"></span>
        </div>
    }

    return (
        <table className="table">
            <thead className="thead-dark">
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>NBP</th>
                <th>Sell</th>
                <th>Buy</th>
            </tr>
            </thead>

            <tbody>

            {data[getCurrentDate()] && data[date] && Object.keys(data[date])?.map((currencyCode) => {
                const currentDate = getCurrentDate();
                const currencyData = {
                    ...data[date][currencyCode],
                    code: currencyCode,
                }
                // Handles the case when there are values to compare to
                if (date !== currentDate) {
                    const comparisonData = {
                        ...data[currentDate][currencyCode],
                        code: currencyCode
                    }
                    return <ExchangeRateRow data={currencyData} key={currencyCode}
                                            comparisonData={comparisonData}/>
                }

                return <ExchangeRateRow data={currencyData} key={currencyCode}/>
            })}
            </tbody>
        </table>);
}

