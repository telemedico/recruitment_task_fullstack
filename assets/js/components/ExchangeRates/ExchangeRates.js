// ./assets/js/components/ExchangeRates/ExchangeRates.tsx

import './styles.css';

import React, {Component} from 'react';
import axios from 'axios';

export default class ExchangeRates extends Component {

    TRY_GET_REQUEST_DATA_COUNT = 7;
    BASE_URL = 'http://telemedi-zadanie.localhost';
    EARLIEST_DATE_POSSIBLE = '2023-01-01';

    constructor(props) {
        super(props);

        this.state = {loading: true};
        this.message = '';

        this.firstAvaliableRates = Object; // ToDo :: on model
        this.firstAvaliableRatesDate = new Date();

        this.exchangeRatesTableRender = String;
        this.dateInputValue = '';

        this.historicalExchangeRates = Object;

        this.getFirstAvailableRatesTrys = 0;
    }

    async getFirstAvailableRates() {
        let result = await axios.get(
            this.BASE_URL + `/api/exchange-rates`,
            {
                params: {
                    date: this.formatDateToString(this.firstAvaliableRatesDate)
                }
            }
        ).then(response => {
            if (typeof response.data.date === 'undefined') {
                return false;
            }

            return response.data;
        }).catch(error => {
            console.log(error)

            return false;
        });

        if (result === false) {
            this.firstAvaliableRatesDate.setDate(
                this.firstAvaliableRatesDate.getDate() - 1
            );

            this.getFirstAvailableRatesTrys++;

            this.getFirstAvailableRates();

            return;
        }

        this.firstAvaliableRates = result;

        this.dateInputValue = result.date;

        this.prepareExchangeRatesTable();

        if (this.getFirstAvailableRatesTrys !== 0) {
            this.message = 'Nie udało się pobrać danych na dzień dzisiejszy. Pobrano dane na dzień ' + this.dateInputValue;
        }

        this.setState({loading: false});
    }

    prepareExchangeRatesTable() {
        let isHistoricalData = typeof this.historicalExchangeRates.date !== 'undefined';

        let rows = [];

        this.firstAvaliableRates.buyableCurrencies.forEach((currency, index) => {
            rows.push((<tr key={'bc -' + index}>
                <td>{currency.code}</td>
                <td>{currency.name}</td>
                <td>{currency.nbpMidRate}</td>
                <td>{currency.buyPrice}</td>
                <td>{currency.sellPrice}</td>
                {isHistoricalData
                    ? (<>
                        <td>{this.historicalExchangeRates.buyableCurrencies[index].nbpMidRate}</td>
                        <td>{this.historicalExchangeRates.buyableCurrencies[index].buyPrice}</td>
                        <td>{this.historicalExchangeRates.buyableCurrencies[index].sellPrice}</td>

                    </>) : (<></>)
                }
            </tr>))
        });

        this.firstAvaliableRates.supportedCurrencies.forEach((currency, index) => {
            rows.push((<tr key={'sc -' + index}>
                <td>{currency.code}</td>
                <td>{currency.name}</td>
                <td>{currency.nbpMidRate}</td>
                <td>-</td>
                <td>{currency.sellPrice}</td>
                {isHistoricalData
                    ? (<>
                        <td>{this.historicalExchangeRates.supportedCurrencies[index].nbpMidRate}</td>
                        <td>-</td>
                        <td>{this.historicalExchangeRates.supportedCurrencies[index].sellPrice}</td>

                    </>) : (<></>)
                }
            </tr>));
        });

        this.exchangeRatesTableRender = (
            <table className="text-center">
                <thead>
                <tr>
                    <th>Kod waluty</th>
                    <th>Nazwa</th>
                    <th>Kurs NBP</th>
                    <th>Cena kupna</th>
                    <th>Cena sprzedaży</th>
                    {isHistoricalData
                        ? (
                            <>
                                <th>Kurs NBP</th>
                                <th>Cena kupna</th>
                                <th>Cena sprzedaży</th>
                            </>
                        ) : (<></>)
                    }
                </tr>
                </thead>

                <tbody>
                <tr>
                    <th colSpan={2}></th>
                    <th colSpan={3}>
                        {this.firstAvaliableRates.date}
                    </th>
                    {isHistoricalData
                        ? (
                            <th colSpan={3}>
                                {this.historicalExchangeRates.date}
                            </th>
                        ) : (<></>)
                    }
                </tr>
                {rows}
                </tbody>

            </table>
        );
    }

    formatDateToString(date = Date) {
        return date.toISOString().split('T')[0];
    }

    componentDidMount() {
        this.getFirstAvailableRates();
    }

    render() {
        const loading = this.state.loading;

        let thisHandler = this;

        function changeExchangeRatesDate(event) {
            thisHandler.setState({loading: true});

            thisHandler.message = '';

            let firstAvailableRatesDateString = thisHandler.formatDateToString(thisHandler.firstAvaliableRatesDate);

            if (event.target.value > firstAvailableRatesDateString) {
                thisHandler.message = 'Wybrana data nie może być późniejsza niż ' + firstAvailableRatesDateString;

                thisHandler.setState({loading: false});

                return;
            }

            if (event.target.value < thisHandler.EARLIEST_DATE_POSSIBLE) {
                thisHandler.message = 'Wybrana data nie może być wcześniejsza niż ' + thisHandler.EARLIEST_DATE_POSSIBLE;

                thisHandler.setState({loading: false});

                return;
            }

            axios.get(
                thisHandler.BASE_URL + `/api/exchange-rates`,
                {
                    params: {
                        date: event.target.value
                    }
                }
            ).then(response => {
                if (typeof response.data.date === 'undefined') {
                    thisHandler.message = 'Błąd';

                    return;
                }

                thisHandler.dateInputValue = event.target.value;

                thisHandler.historicalExchangeRates = response.data;

                thisHandler.prepareExchangeRatesTable();

                thisHandler.setState({loading: false});
            }).catch(error => {
                console.log(error)
            });

        }

        return (
            <div>
                <div>
                    <h1>Tabela kursów kantoru</h1>
                </div>
                <div>
                    {this.message}
                </div>
                <div>
                    <div>
                        {
                            loading
                                ? (
                                    <div className={'text-center'}>
                                        <span className="fa fa-spin fa-spinner fa-4x"></span>
                                    </div>
                                ) : (
                                    <div>
                                        <div>
                                            Wybierz date historyczną do porównania <input type="date" id="exchangeRatesDate"
                                                                                          name="exchangeRatesDate"
                                                                                          onChange={changeExchangeRatesDate}
                                                                                          value={this.dateInputValue}
                                        />
                                        </div>

                                        <div>
                                            {this.exchangeRatesTableRender}
                                        </div>
                                    </div>
                                )
                        }
                    </div>
                </div>
            </div>
        );
    }
}