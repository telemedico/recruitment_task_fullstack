// ./assets/js/components/Users.js

import React, {Component} from 'react';
import { withRouter } from 'react-router-dom';
import axios from 'axios';

class ExchangeRates extends Component {
    constructor() {
        super();

        this.state = {
            exchangeRates: null,
            comparisonRates: null,
            comparisonDate: null,
            loading: true,
            errorMessage: null
        };
    }

    componentDidMount() {
        this.getInitialRates();
        this.getComparisonRates();
    }

    changeDate(date) {
        return window.location.href = '/exchange-rates/' + date;
    }

    getInitialRates() {
        this.getRates(this.getTodayDate());
    }

    getTodayDate() {
        return (new Date())
            .toISOString()
            .split('T')[0].replace(/-/g, '-');
    }

    getComparisonRates() {
        let dateParam = this.props.match.params.date;
        this.setState({comparisonDate: dateParam});

        if (dateParam) {
            this.getRates(dateParam, true);
        }
    }

    getRates(date, isComparison = false) {
        const apiUrl = 'http://telemedi-zadanie.localhost';
        axios.get(apiUrl + `/api/exchange-rates/` + date).then(response => {
            this.setState(!isComparison
                ? {exchangeRates: Object.entries(response.data.data), loading: false}
                : this.setState({comparisonRates: Object.entries(response.data.data), loading: false})
            );
        }).catch((error) => {
            this.setState({
                loading: false,
                errorMessage: error.response.data.message ?? "Can't get rates. Please try again later."
            });
        });
    }

    renderCurrenciesCols(rates) {
        return rates.map(([currency]) => (
            <th className={'text-left'} scope={'row'} key={currency}>{currency}</th>
        ));
    }

    renderRatesCols(rates, isCompare = false) {
        return rates.map(([currency, rate]) => (
            <td className={'text-left'} scope={'row'} key={currency}>
                <span>{rate.rate}</span><br/>
                <span className="text-primary">{rate.buyRate ?? '-'}</span><br/>
                <span className="text-warning">{rate.sellRate ?? '-'}</span>
            </td>
        ));
    }

    render() {
        return(
            <div>
                <section className="row-section">
                    <div className="container">
                        <div className="row mt-2">
                            <div className="col-md-8 offset-md-2">
                                <h2 className="text-center mt-3 mb-3">Exchange Rates</h2>
                                { this.state.errorMessage && (
                                    <div className="alert alert-danger" role="alert">
                                        {this.state.errorMessage}
                                    </div>
                                )}

                                {this.state.loading ? (
                                    <div className={'text-center'}>
                                        <span className="fa fa-spin fa-spinner fa-4x"></span>
                                    </div>
                                ) : (
                                    <div className={'text-center'}>
                                    { this.state.exchangeRates && (
                                        <table className="table table-striped table-dark table-responsive-md small">
                                            <thead className="thead-dark">
                                                <tr>
                                                    <th></th>
                                                    {this.renderCurrenciesCols(this.state.exchangeRates)}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th scope={'row'} style={{'width': '150px'}}>Today</th>
                                                    {this.renderRatesCols(this.state.exchangeRates)}
                                                </tr>
                                                { this.state.comparisonRates && (
                                                    <tr>
                                                        <th scope={'row'}>{this.state.comparisonDate}</th>
                                                        {this.renderRatesCols(this.state.comparisonRates)}
                                                    </tr>
                                                ) }
                                                <tr>
                                                    <td className="text-left pt-3" colSpan="2">
                                                        <span className="mr-1">Mid rate</span>
                                                        <span className="mr-1 text-primary">Buy rate</span>
                                                        <span className="mr-1 text-warning">Sell rate</span>
                                                    </td>
                                                    <td colSpan={1000} className="text-right justify-content-center">
                                                        <label htmlFor="compare-date" className="mr-1">Compare with date: </label>
                                                        <input type="date"
                                                               defaultValue={this.state.comparisonDate}
                                                               id="compare-date"
                                                               name="trip-start"
                                                               min="2023-01-01"
                                                               onChange={e => this.changeDate(e.target.value)}/>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
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

ExchangeRates = withRouter(ExchangeRates);
export default ExchangeRates;
