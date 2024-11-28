// ./assets/js/components/Home.js

import React, {Component} from 'react';
import {Route, Redirect, Switch, Link} from 'react-router-dom';
import SetupCheck from "../views/SetupCheck";
import ExchangeRates from "../views/ExchangeRates";

class Home extends Component {

    render() {
        return (
            <div>
                <nav className="navbar navbar-expand-lg navbar-dark bg-primary">
                    <Link className={"navbar-brand"} to={"#"}> Telemedi Zadanko </Link>
                    <div id="navbarText">
                        <ul className="navbar-nav mr-auto">
                            <li className="nav-item">
                                <Link className={"nav-link"} to={"/setup-check"}> React Setup Check </Link>
                            </li>
                            <li className="nav-item">
                                <Link className={"nav-link"} to={"/exchange-rates"}> Kantor </Link>
                            </li>

                        </ul>
                    </div>
                </nav>
                <div className="container py-4 my-4">
                    <Switch>
                        <Redirect exact from="/" to="/setup-check" />
                        <Route path="/setup-check" component={SetupCheck} />
                        <Route path="/exchange-rates" component={ExchangeRates} />
                    </Switch>
                </div>
            </div>
        )
    }
}

export default Home;
