import React, {Component} from 'react';
import Datepicker from "./Datepicker";
import RateRows from "./RateRows";

class ExchangeRates extends Component {

    constructor() {
        super();

        const urlParams = new URLSearchParams(window.location.search);
        const urlDate = urlParams.get('date');
        const selectedDate = urlDate ? this.formatDate(new Date(urlDate)) : this.formatDate(new Date());
        this.state = { selectedDate };
    }

    getBaseUrl() {
        return 'http://telemedi-zadanie.localhost/exchange-rates';
    }

    handleDateChange = (newDate) => {
        this.setState({ selectedDate: this.formatDate(newDate) });
    };

    formatDate(date) {
        const year = date.getFullYear();
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const day = date.getDate().toString().padStart(2, '0');

        return `${year}-${month}-${day}`;
    }

    render() {
        const { selectedDate } = this.state;

        return(
            <div>
                <section className="row-section">
                    <div className="container">
                        <div className="row mt-5">
                            <div className="col-md-8 offset-md-2">
                                <h2 className="text-center"><span>Exchange rate</span></h2>
                                <div className={'text-center'}>
                                    <Datepicker selectedDate={selectedDate} onDateChange={this.handleDateChange} />
                                    <table className={'table thead-dark '}>
                                        <thead>
                                            <tr>
                                                <th>Waluta</th>
                                                <th>Kod</th>
                                                <th>Kurs kupna</th>
                                                <th>Kurs sprzedaży</th>
                                            </tr>
                                        </thead>
                                        <RateRows selectedDate={selectedDate} />
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        )
    }
}
export default ExchangeRates;
