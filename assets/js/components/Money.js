import React, {Component} from "react";

class Money extends Component {
    render() {
        if (!this.props.money) {
            return 'N \\ A';
        }

        return (
            <>
                {this.props.money} <i className={"fa " + this.props.icon}></i>
            </>
        )
    }
}

export default Money