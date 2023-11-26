import React, {useEffect, useState} from 'react';
import {useHistory} from 'react-router-dom';
import axios from 'axios';
import DateInput from "./UIControls/DateInput";
import CurrencyTable from "./UIControls/CurrencyTable";
import Loader from "./UIControls/Loader";
import PageTitle from "./UIControls/PageTitle";

const ExchangeRates = () => {

    const apiURL = 'http://telemedi-zadanie.localhost/api/exchange-rates';
    const history = useHistory();

    const setDateFormat = (selectedDate) => {
        try {
            selectedDate = new Date(selectedDate).toISOString().split('T')[0];
          } catch (e) {
            selectedDate = new Date().toISOString().split('T')[0];
          }
        return selectedDate;
    }

    const currentDate = setDateFormat(new Date('2023-11-25'));

    const getDateToRequest = (currentDate) => {
        let paramDate = new URLSearchParams(history.location.search).get('date');
        paramDate = paramDate ? setDateFormat(new Date(paramDate)) : null;
        return paramDate || currentDate;
    }

    const [date, setDate] = useState(getDateToRequest(currentDate));
    const [currentRates, setCurrentRates] = useState([]);
    const [paramRates, setParamRates] = useState([]);
    const [loading, setLoading] = useState(true);
    const [errorCurrentDate, setErrorCurrentDate] = useState(null);
    const [errorParamDate, setErrorParamDate] = useState(null);

    const handleDateChange = (event) => {
        let selectedDate = setDateFormat(event.target.value);
        history.push('?date='+ selectedDate);
        setDate(selectedDate);
    };

    const getExchangeRatesFromApi = async (dateToApi, isCurrent = false, isParam = false) => {
        const response = await axios.get(apiURL + '?date=' + dateToApi).then(response => {
            let rates = response.data;
            isCurrent ? setCurrentRates(rates) : null;
            isParam ? setParamRates(rates) : null;
        }).catch(function (e) {
            isCurrent ? setCurrentRates([]) : null;
            isCurrent ? setErrorCurrentDate(e.response.data.error.details[0] || e.response.data.error.message) : null;
            isParam ? setParamRates([]) : null;
            isParam ? setErrorParamDate(e.response.data.error.details[0] || e.response.data.error.message) : null;
            ;
        });
    }

    useEffect(() => {
        setLoading(true);
        if (currentDate===date) {
            setErrorCurrentDate(null);
            setErrorParamDate(null);
            getExchangeRatesFromApi(currentDate,true,true);
        } else {
            if(!currentRates.length && !errorCurrentDate) {
                setErrorCurrentDate(null);
                getExchangeRatesFromApi(currentDate,true,false);
            }
            setErrorParamDate(null);
            getExchangeRatesFromApi(date,false,true);
        }
        setLoading(false);
    },[date]);

    return(
        <div>
            <section className="row-section">
                <div className="container">
                    <div className="row mt-5">
                        <div className="col-md-8 offset-md-2">
                            <PageTitle title='Exchange Rates'/>
                            <DateInput
                                id='DateExchange'
                                label='Currency rates per day:'
                                value={date}
                                onChange={handleDateChange}
                                minDate='2023-01-01'
                                maxDate={currentDate}
                            />
                            {loading ? (
                                <Loader />
                            ) : (
                                <CurrencyTable
                                    currentDate={currentDate}
                                    currentRates={currentRates}
                                    errorCurrentDate={errorCurrentDate}
                                    paramDate={date}
                                    paramRates={paramRates}
                                    errorParamDate={errorParamDate}
                                />
                            )}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    );
}
export default React.memo(ExchangeRates);