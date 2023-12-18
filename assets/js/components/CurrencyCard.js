import React, {Component} from 'react';

class CurrencyCard extends Component {
    render() {
        return (
            <div className="row">
                <div className="col card mb-3">
                    <div className="card-body">
                        <h5 className="card-title">{this.props.currency.code}</h5>
                        <h6 className="card-subtitle mb-2 text-muted">{this.props.currency.currency}</h6>
                        <hr/>
                        <div className="row">
                            <div className="col">
                                <p>Sprzedaż: <b>{this.props.currency.sellPrice}</b></p>
                            </div>
                            <div className="col text-center">
                                <p>Kupno: <b>{this.props.currency.buyPrice ? this.props.currency.buyPrice : "---"}</b></p>
                            </div>
                            <div className="col text-right">
                                <p>Cena: <b>{this.props.currency.price}</b></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        )
    }
}

export default CurrencyCard;
