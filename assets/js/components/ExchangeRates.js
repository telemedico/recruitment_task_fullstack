// ./assets/js/components/Users.js

import React, {Component} from 'react';
import axios from 'axios';

class ExchangeRates extends Component {
    constructor() {
        super();
        this.state = {
            selectedDate: null,
            rates: [],
        };
        this.getExchangeRates();
    }

    todayDate = new Date();
    today = this.todayDate.toISOString().substring(0, 10);

    selectDate = (event) => {
        let selectedDate = event.target.value;

        this.setState({
            selectedDate: selectedDate,
        });

        // axios.get(`/api/exchange-rates/${selectedDate}`)
        //     .then((response) => {
        //         // const { rates } = response.data;
        //         console.log(response)
        //         // updateCurrencyTable(rates);
        //     })
        //     .catch(function (error) {
        //         console.error(error);
        //     });
    };

    getExchangeRates() {
        axios.get(`/api/exchange-rates/${this.state.selectedDate ? this.state.selectedDate : this.today}`)
            .then(response => {
                let data = [...this.state.rates];
                data.push(response.data);
                this.setState({
                    rates: response.data,
                });
            })
            .catch(error => {
                console.error(error);
            });
    }

    render() {
        return (
            <div>
                <section className="row-section">
                    <div className="container justify-content-center">
                        <div className="row mt-5">
                            <div className="">
                                <div className="mt-5 form-group d-flex align-items-center">
                                    <label htmlFor="currencyDate" className="mb-0 pr-3">
                                        Wybierz datę, z której chcesz zobaczyć kursy walut:
                                    </label>
                                    <input
                                        id="currencyDate"
                                        name="currencyDate"
                                        type="date"
                                        min="2023-01-01"
                                        max={this.today}
                                        className="form-control w-25"
                                        onChange={this.selectDate}
                                    />
                                </div>
                                <table className="mt-5 table table-striped">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th colSpan="2"></th>
                                        <th colSpan="2" scope="colgroup" className="text-center">{this.today} r.</th>
                                        <th colSpan="2" scope="colgroup"
                                            className="text-center">{this.state.selectedDate ? this.state.selectedDate + ' r.' : 'Wybierz datę'}</th>
                                    </tr>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Nazwa waluty</th>
                                        <th scope="col">Kod waluty</th>
                                        <th scope="col">Kurs sprzedaży</th>
                                        <th scope="col">Kurs kupna</th>
                                        <th scope="col">Kurs sprzedaży</th>
                                        <th scope="col">Kurs kupna</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {Object.keys(this.state.rates).map((date, index) => {
                                        const currencies = this.state.rates[date];

                                        return Object.keys(currencies).map((currencyCode, innerIndex) => {
                                            const rate = currencies[currencyCode];
                                            const sellRate = rate.sellRate !== false ? Math.round(rate.sellRate * 10000) / 10000 : '-';
                                            const buyRate = rate.buyRate !== false ? Math.round(rate.buyRate * 10000) / 10000 : '-';

                                            return (
                                                <tr key={currencyCode + '-' + index + '-' + innerIndex}>
                                                    <th scope="row">{index + 1}</th>
                                                    <td>{rate.currency}</td>
                                                    <td>1 {currencyCode}</td>
                                                    <td>{sellRate}</td>
                                                    <td>{buyRate}</td>
                                                    <td>{sellRate}</td>
                                                    <td>{buyRate}</td>
                                                </tr>
                                            );
                                        });
                                    })}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        )
    }
}

export default ExchangeRates;
