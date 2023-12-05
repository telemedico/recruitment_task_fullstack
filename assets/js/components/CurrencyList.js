// ./assets/js/components/Home.js

import React, {Component} from 'react';
import {Route, Redirect, Switch, Link} from 'react-router-dom';
import SetupCheck from "./SetupCheck";
import {getCurrencyData} from '../api/currency_api'

class CurrencyList extends Component {

    constructor(props) {
        super(props);

        this.state = {
            currencyData: [],
        };
    }

    handleChange(event) {
        window.history.pushState({}, '', event.target.value)
        getCurrencyData(event.target.value)
            .then((data) => {
                this.setState({
                    currencyData: data
                })
            });
    }

    componentDidMount() {
        getCurrencyData('today')
            .then((data) => {
                this.setState({
                    currencyData: data
                })
            });
    }

    render() {
        const {currencyData} = this.state;

        return (
            <div className="col-md-7">
                <form className="form-inline">
                    <label htmlFor="date"> Wybierz date</label>
                    <input type="date" id="data" name="data" onChange={this.handleChange} min="2023-01-01"></input>
                </form>

                <table className="table table-striped">
                    <thead>
                    <tr>
                        <th>Kod</th>
                        <th>Nazwa</th>
                        <th>średni kurs</th>
                        <th>sprzedaz</th>
                        <th>Kupno</th>
                    </tr>
                    </thead>
                    <tbody>
                    {currencyData.map((currency) => (
                        <tr>
                            <td>{currency.code}</td>
                            <td>{currency.name}</td>
                            <td>{currency.mid}</td>
                            <td>{currency.buy}</td>
                            <td>{currency.sell}</td>
                        </tr>
                    ))}
                    </tbody>
                </table>


            </div>

        );
    }
}

export default CurrencyList;
