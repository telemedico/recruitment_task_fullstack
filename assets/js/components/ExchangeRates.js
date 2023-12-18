import React, {Component} from 'react';
import axios from "axios";
import CurrencyCard from "./CurrencyCard";
import {getBaseUrl} from "../utils";

class ExchangeRates extends Component {
    constructor() {
        super();
        this.state = {
            selectedDate: new Date().toISOString().slice(0, 10),
            today: new Date().toISOString().slice(0, 10),
            data: [],
            loading: true
        };
    }

    componentDidMount() {
        if (this.props.match.params.date) {
            this.setState({
                selectedDate: this.props.match.params.date
            }, () => {
                this.loadData();
            });
        } else {
            this.loadData();
        }
    }

    loadData() {
        let url = getBaseUrl() + `/api/exchange-rates/` + this.state.selectedDate;
        axios.get(url).then(response => {
            this.setState({loading: false, data: response.data})
        }).catch((error) => {
            this.setState({loading: false, data: null});

            if (error.response.status !== 404) {
                alert('Whoops! Wystąpił niespodziewany błąd: ' + error.response.data.error);
            }
        });
    }

    updateSelectedDate = (event) => {
        if (!event.target.value) {
            event.target.value = this.state.today;
        }
        this.setState({
            selectedDate: event.target.value,
            loading: true
        }, () => {
            this.loadData();
            this.props.history.push(`/exchange-rates/${event.target.value}`);
        });
    }

    render() {
        const loading = this.state.loading;
        return (
            <div>
                <section className="row-section">
                    <div className="container">
                        <div className="row mt-5">
                            <div className="col">
                                <h2 className="text-center"><span>Kursy wymiany walut {this.state.selectedDate}</span>
                                </h2>
                            </div>
                        </div>
                        <div className="row mt-5">
                            <div className="col-md-8 offset-md-2 mb-3">
                                <input
                                    value={this.state.selectedDate}
                                    onChange={this.updateSelectedDate}
                                    type="date"
                                    min={'2023-01-01'}
                                    max={this.state.today}
                                />
                            </div>
                        </div>
                        <div>
                            <div className="col-md-8 offset-md-2">
                                {loading ? (
                                    <div className={'text-center'}>
                                        <span className="fa fa-spin fa-spinner fa-4x"></span>
                                    </div>
                                ) : (
                                    <div>
                                        {this.state.data ? (
                                            <div>
                                                {this.state.data.map((item, index) => (
                                                    <CurrencyCard key={index} currency={item}/>
                                                ))}
                                            </div>
                                        ) : (
                                            <div className="row">
                                                <div className="col card mb-3">
                                                    <div className="card-body">
                                                        <h5 className="card-title mt-2">Brak danych dla wybranej
                                                            daty.</h5>
                                                    </div>
                                                </div>
                                            </div>
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
