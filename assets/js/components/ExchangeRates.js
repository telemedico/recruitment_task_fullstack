import React, {Component} from 'react';
import axios from 'axios';
import RateRow from "../atoms/RateRow";

class ExchangeRates extends Component {
    constructor(props) {
        super(props);

        const urlParams = new URLSearchParams(window.location.search);
        const urlDate = urlParams.get('date');
        const today = new Date();
        const formattedToday = today.toISOString().split('T')[0];

        this.state = {
            rates: {},
            loading: true,
            date: urlDate && this.isValidDate(urlDate) ? urlDate : formattedToday,
            realChosenDate: null,
            realTodayDate: null,
        };
    }

    isValidDate(dateStr) {
        const date = new Date(dateStr);
        const minDate = new Date('2023-01-01');
        return date >= minDate && !isNaN(date.getTime());
    }

    handleDateChange = (event) => {
        const newDate = event.target.value;

        this.setState({
            ...this.state,
            date: newDate,
            loading: true,
        });

        const url = new URL(window.location);
        url.searchParams.set('date', newDate);
        window.history.pushState({}, '', url);

        this.getRates(newDate);
    };

    getBaseUrl() {
        return 'http://telemedi-zadanie.localhost';
    }

    componentDidMount() {
        this.getRates(this.state.date);
    }

    getRates(date) {
        const baseUrl = 'http://telemedi-zadanie.localhost';
        axios.get(baseUrl + `/api/exchange-rates?date=` + date).then(response => {
            this.setState({
                ...this.state,
                rates: response.data.rates,
                realChosenDate: response.data.realChosenDate,
                realTodayDate: response.data.realTodayDate,
                loading: false
            });
        }).catch(function (error) {
            console.error(error);
            this.setState({
                ...this.state,
                rates: [],
                loading: false
            });
        });
    }

    render() {
        const loading = this.state.loading;
        const rates = this.state.rates;
        return(
            <div>
                <section className="row-section">
                    <div className="container">
                        <div className="row mt-5">
                            <div className="col-md-10 offset-md-1">
                                <h2 className="text-center">Exchange Rates</h2>

                                <div>
                                    <label htmlFor="date-input">Select Date: </label>
                                    <input
                                        type="date"
                                        id="date-input"
                                        value={this.state.date}
                                        min="2023-01-01"
                                        max={(new Date()).toISOString().split('T')[0]}
                                        onChange={this.handleDateChange}
                                    />
                                </div>

                                {loading ? (
                                    <div className={'text-center'}>
                                        <span className="fa fa-spin fa-spinner fa-4x"></span>
                                    </div>
                                ) : (
                                    <div className={'text-center'}>
                                        { rates.length ? (
                                            <div className={'currency-table'}>
                                                <h3 className={'text-success text-bold'}></h3>
                                                <table className={'table table-bordered table-hover table-striped'}>
                                                    <thead className={'thead-light'}>
                                                        <tr>
                                                            <th colSpan="2">Currency</th>
                                                            <th colSpan="3">{this.state.date}</th>
                                                            <th colSpan="3">Actual Rate</th>
                                                        </tr>
                                                        <tr>
                                                            <th>Code</th>
                                                            <th>Name</th>
                                                            <th>NBP</th>
                                                            <th>Buy</th>
                                                            <th>Sell</th>
                                                            <th>NBP</th>
                                                            <th>Buy</th>
                                                            <th>Sell</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    {rates.map((element, index) => {
                                                        return <RateRow rowData={element} key={'currency-' + index}/>
                                                    })}
                                                    </tbody>
                                                </table>
                                                {this.state.realChosenDate != this.state.date &&
                                                    <p>* {this.state.date} rate was taken from {this.state.realChosenDate}</p>
                                                }
                                                {this.state.realTodayDate != (new Date()).toLocaleDateString('en-CA') &&
                                                    <p>* Actual Rate was taken from {this.state.realTodayDate}</p>
                                                }
                                            </div>
                                        ) : (
                                            <h3 className={'text-error text-bold'}>Exchange rates are not available. Please try again later.</h3>
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
