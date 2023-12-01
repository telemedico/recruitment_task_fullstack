import React, {Component} from 'react';
import { ExchangeCurrencyService }  from './ExchangeCurrencyService.js';
import ExchangeCurrencyPanel from './ExchangeCurrencyPanel.js';

class ExchangeCurrencyView extends Component {

    constructor() {
        super();
        this._exchangeService = new ExchangeCurrencyService();
        this.state = {
            effectiveDate: this._exchangeService.todayIso8601(),
            latestDate: null,
            currencies: [],
            errorMessage: null,
            infoMessage: null,
        }
    }

    componentDidMount() {
        let params = new URLSearchParams(this.props.location.search);
        const date = params.get('date');
        this._fetchCurrencyExchangeModel(date);
    }


    _fetchCurrencyExchangeModel(date) {
        this._exchangeService.fetchExchangeCurrencyData(date).then(response => {
            const newState = {
                ...this.state,
                ...response,
                errorMessage: null,
                infoMessage: null,
            }
            this.setState(newState);
        })
        .catch(error => {
            const newState = {
                ...this.state,
                currencies: [],
                latestDate: null,
                errorMessage: null,
                infoMessage: null,
            }
            switch(error.type) {
                case ExchangeCurrencyService.ErrorCodes.EMPTY:
                    newState.infoMessage = error.message;
                    break;
                case ExchangeCurrencyService.ErrorCodes.HTTP_ERROR:
                    newState.errorMessage = error.message;
                    console.error(error);
                    break;
                default:
                    console.error(error);
                    break;
            }
            this.setState(newState)
        });
    }

    _onChangeDate(e) {
        const effectiveDate = e.target.value;
        this.props.history.push(`/exchange-rates?date=${e.target.value}`)
        this.setState({
            ...this.state,
            effectiveDate,
            latestDate: null,
            currencies: [],
            errorMessage: null,
            infoMessage: null,
        })
        this._fetchCurrencyExchangeModel(effectiveDate)
    }

    render() {
        const { 
            effectiveDate,
            latestDate,
            currencies,
            errorMessage,
            infoMessage
        } = this.state;

        return (
            <div className="container">
                <div className="row mt-5">
                    <div className="col-md-6 offset-md-1">
                        <h2 className="text-center"><span>Kurs wymiany walut z dnia:</span></h2>
                    </div>
                    <div className='col-md-3'>
                        <input 
                            type="date" 
                            className="form-control" 
                            value={effectiveDate} 
                            onChange={e => this._onChangeDate(e)}
                            min="2023-01-02"
                            max={new Date().toISOString().split("T")[0]}
                        />
                    </div>
                </div>
                {
                    errorMessage &&
                    <div className="row mt-5">
                        <div className="col-md-12">
                            <h4 className="text-center"><span style={{color: 'red'}}>{errorMessage}</span></h4>
                        </div>
                    </div>
                }
                {
                    infoMessage &&
                    <div className="row mt-5">
                        <div className="col-md-12">
                            <h4 className="text-center"><span>{infoMessage}</span></h4>
                        </div>
                    </div>
                }
                <div className="row">
                    <div className="col-md-12">
                        <div className="table-container__exchange">
                            {currencies.map(item => (
                                <ExchangeCurrencyPanel
                                    key={item.key}
                                    model={item}
                                    latestDate={latestDate}
                                    effectiveDate={effectiveDate}
                                >
                                </ExchangeCurrencyPanel>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}

export default ExchangeCurrencyView;