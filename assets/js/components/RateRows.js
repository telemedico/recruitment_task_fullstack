import React, { Component } from 'react';
import axios from 'axios';

class RateRows extends Component {
    constructor(props) {
        super(props);
        this.state = { tables: null, loading: true };
    }

    componentDidMount() {
        this.getTablesRate();
    }

    componentDidUpdate(prevProps) {
        if (this.props.selectedDate !== prevProps.selectedDate) {
            this.setState({ tables: null, loading: true })
            this.getTablesRate();
        }
    }

    getTablesRate() {
        const { selectedDate } = this.props;
        const baseUrl = 'http://telemedi-zadanie.localhost';
        axios.get(`${baseUrl}/api/exchange-rates/tables?date=${selectedDate}`).then(response => {
            this.setState({ tables: response.data, loading: false })
        }).catch(function (error) {
            console.error(error);
        });
    }

    render() {
        return (
            <tbody>
                    {this.state.loading ? (
                        <tr>
                            <td colSpan={4}>
                                <span className="fa fa-spin fa-spinner fa-4x"></span>
                            </td>
                        </tr>

                    ) : (
                        this.state.tables.map((rate) =>
                            (
                                <tr>
                                    <td>{ rate.currency }</td>
                                    <td>{ rate.code }</td>
                                    <td>{ rate.rate_buy }</td>
                                    <td>{ rate.rate_sell }</td>
                                </tr>
                            )
                        )
                    )}
            </tbody>
        );
    }
}

export default RateRows;