// ./assets/js/components/Users.js

import React, {Component} from 'react';
import axios from 'axios';
import DataTable from "./Table/DataTable";
import ExchangeRateService from "./Api/ExchangeRateService";

class ExchangeRates extends Component {
    constructor(props) {
        super(props);
        this.state = {
            data: { rates: [] },  // Ustaw rates jako pustą tablicę na początku
            date: ''
        };
    }

    fetchData = async (selectedDate) => {
        const response = await ExchangeRateService.getSortedData(selectedDate);
            this.setState({ data: response.data });
    };

    handleDateChange = (event) => {
        const newDate = event.target.value;
        this.setState({ date: newDate }, () => {
            this.fetchData(newDate);
        });
    };

    componentDidMount() {
        this.fetchData();
    }

    render() {
        const { data, date } = this.state;

        return <div><input type="date" value={date} onChange={this.handleDateChange}/>
            <DataTable data={data}/>;
         </div>
    }
}

export default ExchangeRates;

