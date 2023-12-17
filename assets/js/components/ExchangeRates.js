import React, {Component} from 'react';

class ExchangeRates extends Component {
    constructor() {
        super();
        this.state = {selectedDate: new Date().toISOString().slice(0, 10), data: [], loading: true};
    }

    getBaseUrl() {
        return 'http://telemedi-zadanie.localhost';
    }

    componentDidMount() {
        this.loadData();
    }

    loadData() {

    }

    updateSelectedDate = (event) => {
        this.setState({
            selectedDate: event.target.value
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
                            <div className="col">
                                <input
                                    value={this.state.selectedDate}
                                    onChange={this.updateSelectedDate}
                                    type="date"
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
                                    <div className="row">
                                        {this.state.data}
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
