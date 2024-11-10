import React, {Component} from 'react';

class RateRow extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        const code = this.props.rowData.code;
        const name = this.props.rowData.name;

        const chosenDate = this.props.rowData.chosenDate;
        const todayDate = this.props.rowData.todayDate;

        return(
            <tr>
                <th className={'code'}>{code}</th>
                <th className={'name'}>{name}</th>
                <th className={'nbpRate'}>{chosenDate.mid ? chosenDate.mid.toFixed(6) : '-'}</th>
                <th className={'buyRate'}>{chosenDate.buy ? chosenDate.buy.toFixed(6) : '-'}</th>
                <th className={'sellRate'}>{chosenDate.sell ? chosenDate.sell.toFixed(6) : '-'}</th>
                <th className={'nbpRate'}>{todayDate.mid ? todayDate.mid.toFixed(6) : '-'}</th>
                <th className={'buyRate'}>{todayDate.buy ? todayDate.buy.toFixed(6) : '-'}</th>
                <th className={'sellRate'}>{todayDate.sell ? todayDate.sell.toFixed(6) : '-'}</th>
            </tr>
        )
    }
}
export default RateRow;
