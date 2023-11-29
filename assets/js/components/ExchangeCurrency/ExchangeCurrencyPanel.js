import React, {Component} from 'react';
import '../../../css/ExchangeCurrencyStyle.css';

class ExchangeCurrencyPanel extends Component {
    constructor() {
        super();
        this.state = {
            currencyName: '',
            currencyCode: '',
            exchangeNbp: '',
            exchangeBuy: '',
            exchangeSel: '',
            amountMultiplied: '1',
            latestDate: null,
        };
    }
    componentDidMount() {
        this.setState({
            ...this.state,
            ...this.props.model,
            latestDate: this.props.latestDate
        })
    }

    _currencyFormat(amount) {
        return amount !== null ? Intl.NumberFormat('pl-PL', { style: 'currency', currency: 'PLN' }).format(
            amount,
        ): '-,--';
    }

    render() {
        
        const { state } = this;

        return(
            <div className="currency-panel__exchange">
                <div className="panel-row__exchange header__exchange">
                    <h6>{state.currency} ({state.code})</h6>
                    <div className="small-info__exchange">
                        <div>min. Ilość: <span>{state.amountMultiplied}</span></div>
                        <div>kurs NBP: <span>{this._currencyFormat(state.nbp)}</span></div>
                    </div>
                </div>
                { 
                    state.latestDate &&
                    <div className="panel-row__exchange">
                        <div></div>
                        <span style={{fontSize: '0.7em', fontWeight: 500}}>{state.latestDate}</span>
                    </div>
                }
                <div className="panel-row__exchange">
                    <span>Kupno</span>
                    <span className="strong" style={{color:'red'}}>{this._currencyFormat(state.buy)}</span>
                    { 
                        state.latestDate && 
                        <span className="light">{this._currencyFormat(state.currentBuy)}</span>
                    }
                </div>
                <div className="panel-row__exchange">
                    <span>Sprzedaż</span>
                    <span className="strong" style={{color:'green'}}>{this._currencyFormat(state.sell)}</span>
                    { 
                        state.latestDate && 
                        <span className="light">{this._currencyFormat(state.currentSell)}</span>
                    }
                </div>
            </div>
        );
    }
}

export default ExchangeCurrencyPanel;