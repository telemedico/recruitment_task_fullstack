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
                <td>{code}</td>
                <td>{name}</td>
                <td>{chosenDate.mid ? chosenDate.mid.toFixed(6) : '-'}</td>
                <td>{chosenDate.buy ? chosenDate.buy.toFixed(6) : '-'}</td>
                <td>{chosenDate.sell ? chosenDate.sell.toFixed(6) : '-'}</td>
                <td>{todayDate.mid ? todayDate.mid.toFixed(6) : '-'}</td>
                <td>{todayDate.buy ? todayDate.buy.toFixed(6) : '-'}</td>
                <td>{todayDate.sell ? todayDate.sell.toFixed(6) : '-'}</td>
            </tr>
        )
    }
}
export default RateRow;
