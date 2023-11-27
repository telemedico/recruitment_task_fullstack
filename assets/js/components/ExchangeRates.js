import React, { Component } from 'react';
import axios from 'axios';

class ExchangeRates extends Component {
    constructor() {
        super();
        const urlParams = new URLSearchParams(window.location.search);
        this.state = {
            isLoaded: false,
            exchangeRates: {},
            exchangeRatesToday: {},
            exchangeRatesHeadings: ['code', 'currency', 'buy', 'sell'],
            exchangeRatesDate: urlParams.get('exchange-rates-date')
                ? urlParams.get('exchange-rates-date')
                : '',
        };
        this.handleSubmitExchangeRatesDate =
            this.handleSubmitExchangeRatesDate.bind(this);
    }

    handleSubmitExchangeRatesDate(e) {
        const formData = new FormData(e.target);
        const exchangeRatesDate = formData.get('exchangeRatesDate');
        window.history.replaceState(
            null,
            '',
            '?exchange-rates-date=' + exchangeRatesDate
        );
        this.setState({
            exchangeRatesDate: exchangeRatesDate,
        });
        this.getExchangeRates(exchangeRatesDate);
        e.preventDefault();
    }

    componentDidMount() {
        this.getExchangeRates(this.state.exchangeRatesDate);
    }

    getExchangeRates(exchangeRatesDate = '') {
        axios
            .get(
                window.baseUrl +
                    '/api/get-exchange-rates?exchange-rates-date=' +
                    exchangeRatesDate
            )
            .then((response) => {
                this.setState({
                    exchangeRatesToday: response.data.recent,
                    exchangeRates: response.data.fromDate,
                    isLoaded: true,
                });
            })
            .catch(function (error) {
                console.error(error);
            });
    }

    render() {
        var thFrom = null;
        var tbodyFrom = null;
        var tbodyToday = null;
        const headings = (
            <tr>
                {this.state.exchangeRatesHeadings.map((h, index) => (
                    <td key={index}>{h}</td>
                ))}
            </tr>
        );
        if (this.state.isLoaded) {
            if (this.state.exchangeRatesDate) {
                var thFrom = <th>From: {this.state.exchangeRatesDate}</th>;
            }
            if (
                Object.keys(this.state.exchangeRates).length !== 0 &&
                this.state.exchangeRates.rates.length
            ) {
                var tbodyFrom = this.state.exchangeRates.rates.map(
                    (r, index) => (
                        <tr key={index}>
                            <td>{r.code}</td>
                            <td>{r.currency}</td>
                            <td>{r.buy}</td>
                            <td>{r.sell}</td>
                        </tr>
                    )
                );
            }
            tbodyToday = this.state.exchangeRatesToday.rates.map((r, index) => (
                <tr key={index}>
                    <td>{r.code}</td>
                    <td>{r.currency}</td>
                    <td>{r.buy}</td>
                    <td>{r.sell}</td>
                </tr>
            ));
        }
        return (
            <div>
                <section className='row-section'>
                    <div className='container'>
                        <div className='row mt-5'>
                            <div className='col-md-8 offset-md-2'>
                                <h2 className='text-center'>Kursy walut</h2>
                                <form
                                    onSubmit={
                                        this.handleSubmitExchangeRatesDate
                                    }
                                >
                                    <label>
                                        Data Kursów:
                                        <input
                                            type='date'
                                            name='exchangeRatesDate'
                                            min='2023-01-01'
                                        />
                                    </label>
                                    <input type='submit' value='Submit' />
                                </form>
                                <div>
                                    <table className='table table-bordered'>
                                        <thead>
                                            <tr>
                                                <th>Today</th>
                                                {thFrom}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <table>
                                                        <thead>
                                                            {headings}
                                                        </thead>
                                                        <tbody>
                                                            {tbodyToday}
                                                        </tbody>
                                                    </table>
                                                </td>
                                                {this.state
                                                    .exchangeRatesDate ? (
                                                    <td>
                                                        <table>
                                                            <thead>
                                                                {headings}
                                                            </thead>
                                                            <tbody>
                                                                {tbodyFrom}
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                ) : null}
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        );
    }
}
export default ExchangeRates;
