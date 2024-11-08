// ./assets/js/components/Users.js

import React, { Component } from "react";
import axios from "axios";
import { getBaseUrl } from "../config/config";
import { getFormattedDate } from '../utils/dateUtils';

class ExchangeRates extends Component {
  constructor() {
    super();
    this.state = { exRatesData: {}, loading: true, selectedDate: '', error: '' };
  }

  componentDidMount() {
    const params = new URLSearchParams(window.location.search);
    const dateFromUrl = params.get('date') || '';
    let selectedDate = dateFromUrl;
    if ((new Date(dateFromUrl)).toString() === "Invalid Date") {
        selectedDate = getFormattedDate();
    }
    this.updateExchangeRates(selectedDate);
    this.setState({ selectedDate });
  }

  updateExchangeRates(date = "") {
    const baseUrl = getBaseUrl();
    this.setState({ loading: true, error: ''});
    axios
      .get(baseUrl + `/api/exchange-rates`, { params: { date } })
      .then((response) => {
        const url = new URL(window.location);
        url.searchParams.set('date', date);
        window.history.pushState({}, '', url);

        this.setState({ exRatesData: response.data, loading: false });
      })
      .catch((error) => {
        //debugger;
        //console.error(error);
        this.setState({ exRatesData: null, loading: false, error: error.response.data.error });
      });
  }

  handleDateChange = (event) => {
    this.setState({ selectedDate: event.target.value });
  };

  handleSubmit = () => {
    this.updateExchangeRates(this.state.selectedDate);
  };

  render() {
    const loading = this.state.loading;
    return (
      <div>
        <section className="row-section">
          <div className="container">
            <div className="row mt-5">
              <div className="col-md-8 offset-md-2">
                <h2 className="text-center mb-4">Exchange rates</h2>
                <div className={"text-center"}>
                  <div className="text-center small text-muted mb-1">
                    Compare today's rate to
                  </div>
                  <div className="d-flex justify-content-center mb-2">
                    <input
                      type="date"
                      min="2023-01-01"
                      value={this.state.selectedDate}
                      onChange={this.handleDateChange}
                      className="form-control w-25 mr-3"
                    />
                    <button
                      onClick={this.handleSubmit}
                      className="btn btn-primary"
                    >
                      Get Rates
                    </button>
                  </div>
                  <div className="text-center small text-muted mb-4">
                    Archive data available for exchange rates – from January 1,
                    2023.
                  </div>
                  {this.state.error ? (
                    <p className="text-danger">{this.state.error}</p>
                  ) : (loading || !this.state.exRatesData) ? (
                    <span className="fa fa-spin fa-spinner fa-4x"></span>
                  ) : (
                    <table className="table table-striped mb-8">
                      <thead className="thead-light">
                        <tr>
                          <th>Date</th>
                          <th>Currency</th>
                          <th>Mid Rate</th>
                          <th>Buy Rate</th>
                          <th>Sell Rate</th>
                        </tr>
                      </thead>
                      <tbody>
                        {this.state.exRatesData && this.state.exRatesData.rates &&
                          this.state.exRatesData.rates.map((rate, index) => (
                            <React.Fragment key={index}>
                              <tr>
                                <td>{this.state.exRatesData.latestDate}</td>
                                <td>{rate.code}</td>
                                <td>{rate.mid} zł</td>
                                <td>{rate.buy ? rate.buy + " zł" : "N/A"}</td>
                                <td>{rate.sell ? rate.sell + " zł" : "N/A"}</td>
                              </tr>
                              {rate.selected && (
                                <tr>
                                  <td>{this.state.exRatesData.selectedDate}</td>
                                  <td>{rate.selected.code}</td>
                                  <td>{rate.selected.mid} zł</td>
                                  <td>
                                    {rate.selected.buy
                                      ? rate.selected.buy + " zł"
                                      : "N/A"}
                                  </td>
                                  <td>
                                    {rate.selected.sell
                                      ? rate.selected.sell + " zł"
                                      : "N/A"}
                                  </td>
                                </tr>
                              )}
                            </React.Fragment>
                          ))}
                      </tbody>
                    </table>
                  )}
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
