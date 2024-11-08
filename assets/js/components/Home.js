// ./assets/js/components/Home.js

import React, {Component} from 'react';
import {Route, Redirect, Switch, Link, withRouter } from 'react-router-dom';
import SetupCheck from "./SetupCheck";
import ExchangeRates from "./ExchangeRates";
import PageNotFound from "./PageNotFound";

class Home extends Component {

    componentDidMount() {
        document.title = "Home - Telemedi Zadanko";
    }

    componentDidUpdate(prevProps) {
        if (this.props.location !== prevProps.location) {
            const titles = {
                '/': 'Home - Telemedi Zadanko',
                '/setup-check': 'Setup Check - Telemedi Zadanko',
                '/exchange-rates': 'Exchange rates - Telemedi Zadanko',
            };
            document.title = titles[this.props.location.pathname] || '404 - not found';
        }
    }

    render() {
        return (
            <div>
                <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
                    <Link className={"navbar-brand"} to="/"> Telemedi Zadanko </Link>
                    <div id="navbarText">
                        <ul className="navbar-nav mr-auto">
                            <li className="nav-item">
                                <Link className={"nav-link"} to={"/setup-check"}> React Setup Check </Link>
                            </li>
                            <li className="nav-item">
                                <Link className={"nav-link"} to={"/exchange-rates"}> Exchange rates </Link>
                            </li>
                        </ul>
                    </div>
                </nav>
                <Switch>
                    <Redirect exact from="/" to="/exchange-rates" />
                    <Route path="/setup-check" component={SetupCheck} />
                    <Route path="/exchange-rates" component={ExchangeRates} />
                    <Route component={PageNotFound} />
                </Switch>
            </div>
        )
    }
}

export default withRouter(Home);
