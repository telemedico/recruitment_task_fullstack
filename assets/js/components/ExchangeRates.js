import React, { Component } from 'react'
import axios from 'axios'
import Row from './exchange-rates/Row'

class ExchangeRates extends Component {
    constructor (props) {
        super(props)
        const { date: dateParam } = props.match.params
        const date = dateParam === undefined ? this.getDate() : this.getDate(dateParam)

        this.state = {
            error: null,
            exchangeRates: [],
            loading: true,
            date,
        }
    }

    componentDidMount () {
        this.getExchangeRates()
    }

    componentDidUpdate (prevProps, prevState) {
        const { date: dateParam } = this.props.match.params
        const date = this.getDate()

        if (dateParam === undefined && this.state.date !== date) {
            this.setState({
                loading: true,
                date,
            })
        }

        if (prevState.date !== this.state.date) {
            this.getExchangeRates()
        }
    }

    getExchangeRates () {
        axios.get(`/api/exchange-rates?date=${this.state.date}`).then(response => {
            const exchangeRates = Object.values(response.data)
            const responseIsOK = exchangeRates.length > 0
            responseIsOK && this.setState({
                exchangeRates,
                loading: false,
                error: null,
            }) || this.setState({
                error: 'Brak danych dla podanej daty, wybierz inną datę',
            })
        }).catch(function (error) {
            console.error(error)
        })
    }

    getDate = (date = null) => {
        const dateObj = date === null ? new Date() : new Date(date)
        const [day, month, year] = dateObj.toLocaleDateString('pl-PL', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
        }).split('.')

        return `${year}-${month}-${day}`
    }

    handleChange = (event) => {
        this.props.history.push({
            pathname: `/exchange-rates/${event.target.value}`,
        })
        this.setState({
            error: null,
            loading: true,
            date: event.target.value,
        })
    }

    render () {
        return (
            <div>
                <section className="row-section">
                    <div className="container">
                        <div className="row mt-5">
                            <div className="col-md-12">
                                <h2 className="text-center">Exchange Rates</h2>
                                <div className="input-group mb-3">
                                    <span className="input-group-text">Data</span>
                                    <input type="date" min="2023-01-01" max={this.getDate()} value={this.state.date ?? this.getDate()} onChange={this.handleChange} />
                                </div>
                                {this.state.loading ? (
                                    <div className={'text-center'}>
                                        <span className="fa fa-spin fa-spinner fa-4x"></span>
                                        {this.state.error && <span className="row">{this.state.error}</span>}
                                    </div>
                                ) : (
                                    <table className="table table-striped table-bordered">
                                        <thead className="thead-dark">
                                        <tr>
                                            <th scope="col"><span className="table-txt">Kod</span></th>
                                            <th scope="col"><span className="table-txt">Nazwa waluty</span></th>
                                            <th scope="col"><span className="table-txt">Kurs NBP</span><span className="table-txt">{this.state.date}</span></th>
                                            <th scope="col"><span className="table-txt">Kurs kupna</span><span className="table-txt">{this.state.date}</span></th>
                                            <th scope="col"><span className="table-txt">Kurs sprzedaży</span><span className="table-txt">{this.state.date}</span></th>
                                            <th scope="col"><span className="table-txt">Aktualny kurs NBP</span></th>
                                            <th scope="col"><span className="table-txt">Aktualny kurs kupna</span></th>
                                            <th scope="col"><span className="table-txt">Aktualny kurs sprzedaży</span></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {this.state.exchangeRates.map(exchangeRate => <Row {...exchangeRate}></Row>)}
                                        </tbody>
                                    </table>
                                )}
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        )
    }
}

export default ExchangeRates
