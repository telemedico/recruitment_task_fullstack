import React, {Component} from 'react';

class Datepicker extends Component {
    handleDateChange = (event) => {
        const { onDateChange } = this.props;
        const newDate = new Date(event.target.value);
        onDateChange(newDate);
    };

    render() {
        const { selectedDate } = this.props;
        const urlSearchParams = new URLSearchParams(window.location.search);
        urlSearchParams.set('date', selectedDate);
        const newUrl = `${window.location.pathname}?${urlSearchParams.toString()}`;
        window.history.pushState({ path: newUrl }, '', newUrl);

        return (<div>
            <p><span>Kurs z dnia: </span>
            <input
                type="date"
                max={(new Date()).toISOString().split('T')[0]}
                min="2023-01-01"
                value={selectedDate}
                onChange={this.handleDateChange}
            />
            </p>
        </div>)
    }
}
export default Datepicker;