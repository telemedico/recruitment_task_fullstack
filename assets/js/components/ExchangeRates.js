import React, {Component} from "react";
import axios from "axios";
import SetupCheck from "./SetupCheck";
import {useHistory} from 'react-router-dom';

class ExchangeRates extends Component
{
    constructor() {
        const urlParams = new URLSearchParams(window.location.search);
        super();
        this.setup = new SetupCheck();
        this.state = {
            rates: [],
            buyRates: [
                'USD', 'EUR'
            ],
            date: this.dateToString(urlParams.get('date')),
            loading: true
        };
        this.rates = {
            euro: 'EUR',
            dolar: 'USD',
            koruna: 'CZK',
            rupia: 'IDR',
            real: 'BRL'
        };
    }

    componentDidMount()
    {
        this.checkApiExchangeRate();
    }

    checkApiExchangeRate()
    {
        const baseUrl = this.setup.getBaseUrl();
        const tmpRates = [];
        for(let rate in this.rates) {
            axios.get(baseUrl + '/api/exchange-rate?currency=' + this.rates[rate] + (this.state.date !== '' ? '&date=' + this.state.date : ''))
                .then(response => {
                    tmpRates.push(response.data);
                    this.setState({rates: tmpRates, loading: false})
                })
                .catch(error => {
                    this.setState({rates: tmpRates, loading: false})
                })
        }
    }

    changeRateDate(event)
    {
        /*
        const history = useHistory();
        history.push({
            pathname: '/exchange-rates',
            search: '?date=' + event.target.value
        });
        */
        window.location.href = '/exchange-rates?date=' + event.target.value;
    }

    dateToString(date)
    {
        if (date !== undefined && date !== null) {
            date = new Date(date);
            let month = date.getMonth() + 1;
            let day = date.getDate();
            month = month < 10 ? '0' + month : month;
            day = day < 10 ? '0' + day : day;
            return date.getFullYear() + '-' + month + '-' + day;
        }
        return '';
    }

    render() {
        const loading = this.state.loading;
        const date = this.state.date || this.dateToString(new Date());
        return(
            <div>
                <section className="row-section">
                    <div className="container">
                        <div className={"row mt-5"}>
                            <div className={"col-md-8 offset-md-2"}>
                                <h2 className={"text-right"}>Select date:</h2>
                            </div>
                            <div className={"text-center"}>
                                <input type={"date"}
                                       className={"form-control"}
                                       onChange={this.changeRateDate}
                                       min={'2023-01-01'}
                                       max={this.dateToString(new Date())}
                                       value={this.state.date}
                                />
                            </div>
                        </div>
                        <div className="row mt-5">
                            <div className="col-md-8 offset-md-2">
                                <h2 className="text-center"><span>This is a Exchange </span>
                                    Rate table for {date}
                                </h2>

                                {loading ? (
                                    <div className={'text-center'}>
                                        <span className="fa fa-spin fa-spinner fa-4x"></span>
                                    </div>
                                ) : (
                                    <div className={'text-center mt-5'}>
                                        { this.state.rates.length > 0 ? (
                                            <div className={'text-center'}>
                                                <table className={'table table-striped'}>
                                                    <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Code</th>
                                                        <th>Rate</th>
                                                        <th>Buy price</th>
                                                        <th>Sell price</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    {this.state.rates.map(rate => (
                                                        <tr key={rate.code}>
                                                            <td>{rate.currency}</td>
                                                            <td>{rate.code}</td>
                                                            <td>{rate.rates[0].mid}</td>
                                                            {this.state.buyRates.includes(rate.code) ? (
                                                                <td>{(parseFloat(rate.rates[0].mid) - 0.05).toFixed(4)}</td>
                                                            ) : (
                                                                <td>-</td>
                                                            )}
                                                            {this.state.buyRates.includes(rate.code) ? (
                                                                <td>{(parseFloat(rate.rates[0].mid) + 0.07).toFixed(4)}</td>
                                                            ) : (
                                                                <td>{(parseFloat(rate.rates[0].mid) + 0.15).toFixed(4)}</td>
                                                            )}
                                                        </tr>
                                                    ))}
                                                    </tbody>
                                                </table>
                                            </div>
                                        ) : (
                                            <h3 className={'text-error text-bold'}><strong>Can not get exchange rates
                                                :(</strong></h3>
                                        )}
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        )
    }
}

export default ExchangeRates;
