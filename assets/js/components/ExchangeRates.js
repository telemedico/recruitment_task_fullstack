import React, {Component} from 'react';
import axios from 'axios';

class ExchangeRates extends Component {
    constructor(props) {
        super(props);
        this.state = {
            rates: [],
            loading: true,
            errorMessage: '',
            userDate: '',
            latestDate: ''
        };
    }

    getBaseUrl() {
        return 'http://telemedi-zadanie.localhost';
    }

    componentDidMount() {
        this.fetchData();
    }

    getTodaysDate = () => {
        return (new Date()).toISOString().split('T')[0];
    };

    adjustDate(date) {
        const inputDate = new Date(date);
        const currentHour = new Date().getHours();
        if (currentHour < 12) {
            inputDate.setDate(inputDate.getDate() - 1);
        }

        const year = inputDate.getFullYear();
        const month = String(inputDate.getMonth() + 1).padStart(2, '0');
        const day = String(inputDate.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    }

    getQueryParamDate(param) {
        const searchParams = new URLSearchParams(this.props.location.search);

        return searchParams.get(param);
    }

    handleDateChange = (event) => {
        const selectedDate = event.target.value;
        this.setState({userDate: selectedDate}, () => {
            this.props.history.push({
                pathname: '/exchange-rates',
                search: `?date=${selectedDate}`,
            });
            this.fetchData();
        });
    };

    fetchData() {
        const todaysDate = this.getTodaysDate();
        const ratesDate = this.adjustDate(this.state.userDate || this.getQueryParamDate('date') || todaysDate);

        axios.get(`${this.getBaseUrl()}/api/exchange-rates?userDate=${ratesDate}&latestDate=${this.adjustDate(todaysDate)}`)
            .then(response => {
                if (200 === response.status) {
                    this.setState({
                        rates: response.data.rates,
                        userDate: response.data.userDate,
                        latestDate: response.data.latestDate
                    });
                } else {
                    this.setState({userDate: ratesDate, errorMessage: 'Something went wrong.'});
                }
            })
            .catch(error => {
                if (error.response.headers && error.response.headers.has('X-Validation-Errors')) {
                    this.setState({
                        loading: false,
                        errorMessage: `Fix following errors and try again: ${error.response.headers.get('X-Validation-Errors')}`
                    });
                } else {
                    this.setState({userDate: ratesDate, errorMessage: 'Something went wrong.'});
                }
            })
            .finally(() => {
                this.setState({loading: false});
            })
    }

    renderTableRows() {
        return this.state.rates.map((rate, index) => (
            <tr key={index}>
                <td><b>{rate.currencyCode}</b> ({rate.currencyName})</td>
                <td className="highlighted-rate">{rate.userDateBidRate ?? 'N/A'}</td>
                <td className="highlighted-rate">{rate.userDateAskRate ?? 'N/A'}</td>
                <td className="highlighted-rate">{rate.userDateNbpRate ?? 'N/A'}</td>
                <td>{rate.latestBidRate ?? 'N/A'}</td>
                <td>{rate.latestAskRate ?? 'N/A'}</td>
                <td>{rate.latestNbpRate ?? 'N/A'}</td>
            </tr>
        ));
    }

    render() {
        const {rates, loading, userDate, latestDate, errorMessage} = this.state;

        return (
            <div className="container mt-5">
                <h2 className="text-center">Exchange Rates @ Telemedi by Adam Guła</h2>
                {loading ? (
                    <div className="text-center">
                        <span className="fa fa-spin fa-spinner fa-4x"></span>
                    </div>
                ) : (
                    <>
                        <div className="text-center mb-4 date-picker-container">
                            <label htmlFor="date-picker">Selected date</label>
                            <input
                                id="date-picker"
                                type="date"
                                value={userDate}
                                min="2023-01-01"
                                max={this.getTodaysDate()}
                                onChange={this.handleDateChange}
                            />
                        </div>
                        <table className="table table-bordered">
                            <thead>
                            <tr>
                                <th rowSpan="1"></th>
                                <th colSpan="3" className="text-center highlighted-rate">Rates for selected date
                                    ({userDate})
                                </th>
                                <th colSpan="3" className="text-center">Latest rates ({latestDate})</th>
                            </tr>
                            <tr>
                                <th>Currency</th>
                                <th className="highlighted-rate">Bid</th>
                                <th className="highlighted-rate">Ask</th>
                                <th className="highlighted-rate">NBP</th>
                                <th>Bid</th>
                                <th>Ask</th>
                                <th>NBP</th>
                            </tr>
                            </thead>
                            <tbody>
                            {rates.length > 0 ? this.renderTableRows() : (
                                <tr>
                                    {errorMessage.length > 0 ? (
                                        <td colSpan="6" className="text-center error-message">{errorMessage}</td>) : (
                                        <td colSpan="6" className="text-center">No data available</td>)}
                                </tr>
                            )}

                            </tbody>
                        </table>
                    </>
                )}
            </div>
        );
    }
}

export default ExchangeRates;
