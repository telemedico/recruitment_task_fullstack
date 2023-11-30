import React, {Component} from "react";
import {withRouter} from "react-router-dom";

class DatePicker extends Component {
    constructor() {
        super()
        this.inputRef = React.createRef();
    }

    componentDidMount() {
        $('#datepicker').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            startDate: '2023-01-01',
        }).on("changeDate", () => {
            const path = '/exchange-rates/' + this.inputRef.current.value
            this.props.history.push(path)
        });

        $("#datepicker").val(this.props.date);
        $("#datepicker input").val(this.props.date);
    }

    redirect() {
        console.log('hello')
    }

    render() {
        return (
            <div>
                <label htmlFor="datepicker-input">Wybierz date</label>
                <div id="datepicker" className="input-group date" data-provide="datepicker">
                    <input ref={this.inputRef} id="datepicker-input" type="text" className="form-control" readOnly />
                    <div className="input-group-addon form-control">
                        <i className="fa fa-calendar" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        )
    }
}

export default withRouter(DatePicker)