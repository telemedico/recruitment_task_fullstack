// ./assets/js/components/Users.js

import React, {Component} from 'react';
import axios from 'axios';

class Rates extends Component {
    constructor() {
        super();
        this.state = {
            loading: true,
            error: null,
            date: null,
            rates: [],
            currentRatesDate: null,
            currentRates: [],
        };
    }

    getBaseUrl() {
        return 'http://telemedi-zadanie.localhost';
    }

    formatDate(date) {
        if (!date) {
            return '';
        }

        return new Date(date).toISOString().split('T')[0]
    }

    formatNumber(value, decimals = 2) {
        if (!value) {
            return '';
        }

        return parseFloat(value).toLocaleString(undefined, {
            maximumFractionDigits: decimals,
            minimumFractionDigits: decimals
        })
    }

    componentDidMount() {
        this.getRatesByDate().then((response) => {
            const {rates, date} = response;

            this.setState({currentRates: rates, currentRatesDate: new Date(date)})
        }).catch((error) => {
            console.error(error);
        });
    }

    async getRatesByDate(date = null) {
        this.setState({loading: true, error: null});

        try {
            const response = await axios.get(this.getBaseUrl() + `/api/exchange-rate` + (date ? `/${date}` : ''));
            this.setState({loading: false})

            return response.data
        } catch (e) {
            this.setState({
                loading: false,
                error: e.response.data.message ?? 'unexpected error occurred.',
            });

            throw e;
        }
    }

    setDate(newDate) {
        this.setState({date: new Date(newDate)})

        this.getRatesByDate(newDate).then((response) => {
            const {rates, date} = response;

            this.setState({rates, date: new Date(date)})
        })
    }

    get rates() {
        const currencies = {}
        this.state.rates.map(rate => (currencies[rate.currency] = rate.name))
        this.state.currentRates.map(rate => (currencies[rate.currency] = rate.name))

        return Object.keys(currencies).map((currency) => {
            const currentRate = this.state.currentRates?.find((rate) => rate.currency === currency)
            const rate = this.state.rates?.find((rate) => rate.currency === currency)

            let diff = 0;

            if (rate && currentRate.buy && rate.buy) {
                diff = currentRate.buy - rate.buy
            } else if (rate && currentRate.sell && rate.sell) {
                diff = currentRate.sell - rate.sell
            }

            return {
                currency,
                name: currencies[currency],
                rate1: currentRate,
                rate2: rate,
                diff,
            }
        })
    }

    get hasDateSelected() {
        return this.state.date !== null && this.state.rates.length > 0
    }

    getDiffClass(diff) {
        if (diff === null) {
            return '';
        }

        if (diff >= 0) {
            return 'text-success';
        }

        return 'text-danger';
    }

    render() {
        const loading = this.state.loading;
        const error = this.state.error;
        return (
            <div>
                <section className="row-section">
                    <div className="container">
                        <div className="row mt-5">
                            <div className="col-md-8 offset-md-2">
                                <div className={'row'}>
                                    <h2 className="col-md-6">Currency Rates</h2>
                                    <div className={'col-md-6'}>
                                        <input
                                            type="date"
                                            id="date"
                                            name="rate-date"
                                            value={this.formatDate(this.state.date)}
                                            onChange={(e) => this.setDate(e.target.value)}
                                            min="2013-01-01"
                                            className={'form-control'}
                                        />
                                    </div>
                                </div>

                                {
                                    error
                                        ? (<div className="alert alert-danger" role="alert">{error}</div>)
                                        : (<div></div>)
                                }

                                {loading ? (
                                    <div className={'text-center'}>
                                        <span className="fa fa-spin fa-spinner fa-4x"></span>
                                    </div>
                                ) : (
                                    <div className={'bg-white'}>
                                        <table className={'table table-striped table-bordered table-sm thead-light'}>
                                            <thead>
                                            <tr>
                                                <th rowSpan={2}>Code</th>
                                                <th rowSpan={2}>Currency</th>
                                                {this.hasDateSelected &&
                                                    <th colSpan={2}>{this.formatDate(this.state.date)}</th>}
                                                <th colSpan={2}>
                                                    <div>Current Rates</div>
                                                    <div>{this.formatDate(this.state.currentRatesDate)}</div>
                                                </th>
                                                {this.hasDateSelected && <th rowSpan={2}>Diff</th>}
                                            </tr>
                                            <tr>
                                                {this.hasDateSelected && <th>Buy</th>}
                                                {this.hasDateSelected && <th>Sell</th>}
                                                <th>Buy</th>
                                                <th>Sell</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            {
                                                this.rates.map((rate) => {
                                                    return (
                                                        <tr key={rate.currency}>
                                                            <td>{rate.currency}</td>
                                                            <td>{rate.name}</td>
                                                            {
                                                                this.hasDateSelected &&
                                                                <td className={'text-right'}>{rate.rate2?.buy ? this.formatNumber(rate.rate2.buy, 4) : '-'}</td>
                                                            }
                                                            {
                                                                this.hasDateSelected &&
                                                                <td className={'text-right'}>{rate.rate2?.sell ? this.formatNumber(rate.rate2.sell, 4) : '-'}</td>
                                                            }
                                                            <td className={'text-right'}>{rate.rate1.buy ? this.formatNumber(rate.rate1.buy, 4) : '-'}</td>
                                                            <td className={'text-right'}>{rate.rate1.sell ? this.formatNumber(rate.rate1.sell, 4) : '-'}</td>
                                                            {
                                                                this.hasDateSelected &&
                                                                <td className={`text-right ${this.getDiffClass(rate.diff)}`}>
                                                                    <span>{rate.diff > 0 ? '+' : ''}</span>
                                                                    {rate.diff ? this.formatNumber(rate.diff, 4) : '-'}
                                                                </td>
                                                            }
                                                        </tr>
                                                    )
                                                })
                                            }
                                            </tbody>
                                        </table>
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

export default Rates;
