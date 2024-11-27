import ExchangeRatesForm from "../components/ExchangeRatesForm";
import React from 'react';
import BackgroundBox from "../components/BackgroundBox";
import Spinner from "../components/Spinner";
import useExchangeRates from "../utils/useExchangeRates";
import ExchangeRateItem from "../components/ExchangeRateItem";
export default () => {
    const {
        date,
        rates,
        todayRates,
        handleDateChange
    } = useExchangeRates();
    return (
        <div>
            <ExchangeRatesForm value={date} onDateChange={handleDateChange}/>
            <BackgroundBox>
                <div className="container">
                    <div className="row">
                        <div className="col-md-6">
                            <h3 className="text-primary">Kurs dla wybranej daty:</h3>
                            {!rates && <Spinner/>}
                            {!!rates && rates.map(rate => <ExchangeRateItem primary={true} key={rate.code}
                                                                            data={rate}/>)}
                        </div>
                        <div className="col-md-6">
                            <h5 className="mt-2">Kurs na dziś:</h5>
                            {!todayRates && <Spinner/>}
                            {!!todayRates && todayRates.map(rate => <ExchangeRateItem key={rate.code} data={rate}/>)}
                        </div>
                    </div>
                </div>
            </BackgroundBox>
        </div>
    );
}