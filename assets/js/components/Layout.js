import React, {Component} from 'react';
import { Route, Switch, Link } from "react-router-dom";
import ExchangeRates from "../pages/ExchangeRates";
import Home from "../pages/Home";

class Layout extends Component {
    render() {
        return (
            <div>
                <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
                    <Link className={"navbar-brand"} to={"/"}> Telemedi Zadanko </Link>
                    <div id="navbarText">
                        <ul className="navbar-nav mr-auto">
                            <li className="nav-item">
                                <Link className={"nav-link"} to={"/exchange-rates"}>Exchange Rates</Link>
                            </li>
                        </ul>
                    </div>
                </nav>
                <Switch>
                    <Route path="/exchange-rates/:date?" component={ExchangeRates} />
                    <Route path="/" component={Home} />
                </Switch>
            </div>
        )
    }
}

export default Layout