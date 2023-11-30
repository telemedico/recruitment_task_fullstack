// ./assets/js/components/Users.js

import React, {Component} from 'react';
import axios from 'axios';
import Money from '../components/Money'
import DatePicker from '../components/DatePicker'

class ExchangeRates extends Component {
    constructor() {
        super();
        this.state = { todayRatesData: [], ratesData: [], isToday: false, loading: true};
    }

    getBaseUrl() {
        return window.location.origin;
    }

    componentDidUpdate(prevProps, prevState) {
        if (prevProps.match.params.date === this.props.match.params.date) {
            return
        }

        this.setState({ loading: true })

        if (prevState.loading === this.state.loading && this.state.loading === true) {
            return
        }

        const todayDate = this.getDefaultDate()
        const date = this.getDate();
        const isToday = todayDate === date

        this.getRatesFromApi(this.getDate()).then(response => {
            this.setState({ ratesData: response.data, loading: false, isToday})
        });
    }

    componentDidMount() {
        const todayDate = this.getDefaultDate()
        const date = this.getDate();
        const isToday = todayDate === date
        const promises = [];

        promises.push(this.getRatesFromApi(todayDate))

        if (isToday) {
            promises.push(promises[0])
        } else {
            promises.push(this.getRatesFromApi(date))
        }

        Promise.all(promises).then(([todayRates, rates]) => {
            this.setState({ ratesData: rates.data, todayRatesData: todayRates.data, loading: false, isToday })
        })
    }

    getRatesFromApi(date) {
        const baseUrl = this.getBaseUrl()
        return axios.get(baseUrl + `/api/currencies/${date}`).catch(function (error) {
            console.error(error);
            this.setState({ ratesData: [], loading: false});
        });
    }

    getRatesData() {
        return Object.values(this.state.ratesData)
    }

    addZeroPrefix(value) {
        return value > 9 ? value : value;
    }

    getDefaultDate() {
        const date = new Date()
        return date.getFullYear() + '-' + this.addZeroPrefix(date.getMonth() + 1) + '-' + this.addZeroPrefix(date.getDate())
    }

    getDate() {
        return this.props.match.params.date || this.getDefaultDate()
    }

    render() {
        const ratesData = this.getRatesData()
        const loading = this.state.loading;
        const date = this.getDate()
        const todayRates = this.state.todayRatesData

        return(
            <div>
                <h2>Dane dla daty {date}</h2>
                <DatePicker date={date}/>
                {loading ? (
                        <div className={'text-center'}>
                            <span className="fa fa-spin fa-spinner fa-4x"></span>
                        </div>
                ) : (
                    <table className="table table-striped table-dark">
                        <thead>
                            <tr>
                                <th scope="col">Nazwa waluty</th>
                                <th scope="col">Kod waluty</th>
                                <th scope="col">Średnia {this.state.isToday || " / Dzisiejsza"}</th>
                                <th scope="col">Sprzedaż {this.state.isToday || " / Dzisiejsza"}</th>
                                <th scope="col">Kupno {this.state.isToday || " / Dzisiejsza"}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {ratesData.map(({name, code, icon, rates}, key) => {
                                if (this.state.isToday) {
                                    return <tr key={key}>
                                        <td scope="row">{name}</td>
                                        <td scope="row">{code.toUpperCase()}</td>
                                        <td><Money money={rates.origin} icon={icon}/></td>
                                        <td><Money money={rates.sell} icon={icon}/></td>
                                        <td><Money money={rates.buy} icon={icon}/></td>
                                    </tr>
                                }

                                const todayRate = todayRates[code]

                                return <tr key={key}>
                                    <td scope="row">{name}</td>
                                    <td scope="row">{code.toUpperCase()}</td>
                                    <td>
                                        <Money money={rates.origin} icon={icon}/>
                                        &nbsp;/&nbsp;
                                        <Money money={todayRate.rates.origin} icon={icon}/>
                                    </td>
                                    <td>
                                        <Money money={rates.sell} icon={icon}/>
                                        &nbsp;/&nbsp;
                                        <Money money={todayRate.rates.origin} icon={icon}/>
                                    </td>
                                    <td>
                                        <Money money={rates.buy} icon={icon}/>
                                        &nbsp;/&nbsp;
                                        <Money className="text-secondary" money={todayRate.rates.origin} icon={icon}/>
                                    </td>
                                </tr>
                            })}
                        </tbody>
                    </table>
                )}
            </div>
        )
    }
}
export default ExchangeRates;
