import React, {useState, useEffect} from 'react';
import {useHistory, useParams} from "react-router-dom";
import axios from 'axios';

const ExchangeRates = () => {
    const history = useHistory();
    const {date} = useParams();
    const [todayRates, setTodayRates] = useState([]);
    const [customDateRates, setCustomDateRates] = useState([]);
    const [customDate, setCustomDate] = useState(date ? new Date(date) : new Date());
    const [loading, setLoading] = useState(true);
    const minDate = new Date('2023-01-01');
    const maxDate = new Date();

    useEffect(() => {
        fetchCurrencyForDate(new Date(), setTodayRates)
    }, []);

    useEffect(() => {
        fetchCurrencyForDate(customDate, setCustomDateRates)
    }, [customDate]);


    const fetchCurrencyForDate = (dateTime, stateSetter) => {
        axios.get(getBaseUrl() + `/api/exchange-rates/${extractDate(dateTime)}`)
            .then(response => {
                stateSetter(response.data);
            })
            .catch(error => {
                alert('Nie mogę pograć informacji o kursach wymiany walut. Spróbuj za chwilę.');
                console.error(error);
            });
    };

    const handleChangeCustomDate = date => {
        history.push(`/exchange-rates/${extractDate(date)}`);
        setCustomDate(date);
    }

    const extractDate = date => {
        return date.toISOString().split('T')[0];
    }
    const formatPrice = (price) => {
        if (!price) return price;
        return price.toString().replace('.', ',');
    };

    const renderTable = (rates) => {
        return (
            <table className={'table table-striped'}>
                <thead>
                <tr>
                    <th scope={'col'}>Nazwa waluty</th>
                    <th scope={'col'}>Kod waluty</th>
                    <th scope={'col'}>Cena kupna [PLN]</th>
                    <th scope={'col'}>Cena sprzedaży [PLN]</th>
                </tr>
                </thead>
                <tbody>
                {rates.length === 0 ? (
                    <tr scope={'row'}>
                        <td colSpan={4}>Informacje o kursach walut dla wskazanego dnia są w tej chwili niedostępne.</td>
                    </tr>
                ) : (
                    rates.map((rate, index) => {
                        return (
                            <tr scope={'row'} key={rate.code}>
                                <td>{rate.currency}</td>
                                <td>{rate.code}</td>
                                <td>{formatPrice(rate.buyPrice) || ' - '}</td>
                                <td>{formatPrice(rate.sellPrice) || ' - '}</td>
                            </tr>
                        );
                    })
                )}
                </tbody>
            </table>
        )
    }

    const getBaseUrl = () => {
        return 'http://telemedi-zadanie.localhost';
    }

    return (
        <div>
            <div className={'container pt-2 pb-2'}>
                <div className={'lead mt-3 mb-3'}>Kurs na dzień
                    <input
                        type="date"
                        className={'ml-2'}
                        value={extractDate(customDate)}
                        min={extractDate(minDate)}
                        max={extractDate(maxDate)}
                        onChange={e => handleChangeCustomDate(new Date(e.target.value))}
                    />
                </div>
                {renderTable(customDateRates)}

                <p className={'lead mt-5'}>Kurs na dziś</p>
                {renderTable(todayRates)}

            </div>
        </div>
    );
};

export default ExchangeRates;
