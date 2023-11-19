// ./assets/js/components/Users.js

import React, {Component} from 'react';
import axios from 'axios';

class ExchangeRates extends Component {
    constructor() {
        super();
        this.state = {
            selectedDate: null, currentDate: null,
            rates: [],
        };
        this.getExchangeRates();
    }

    todayDate = new Date();
    today = this.todayDate.toISOString().substring(0, 10);


    getExchangeRates() {
        axios.get('/api/exchange-rates')
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
                    <div className="container">
                        <div className="row mt-5">
                            <div className="col-md-8 offset-md-2">
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
                                    />
                                </div>
                                <table className="mt-5 table table-striped">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Waluta</th>
                                        <th scope="col">{this.today} r.</th>
                                        <th scope="col">12.11.2023 r.</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {this.state.rates.map((rate, index) =>
                                        <tr key={rate + '-' + index}>
                                            <th scope="row">1</th>
                                            <td>{rate.code} {rate.currency}</td>
                                            <td>{Math.round(rate.mid * 100)/100}</td>
                                            <td></td>
                                        </tr>
                                    )}
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
