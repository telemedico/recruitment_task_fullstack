import React from "react";
import { Link, Switch, Route, Redirect, useHistory } from "react-router-dom";
import SetupCheck from "./SetupCheck";
import TodayExchangeRates from "./TodayExchangeRates";
import DateExchangeRates from "./DateExchangeRates";

function App() {
    const today = new Date().toISOString().split('T')[0];

    return (
        <div>
            <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
                <Link className="navbar-brand" to="#">
                    Telemedi Zadanko
                </Link>
                <div id="navbarText">
                    <ul className="navbar-nav mr-auto">
                        <li className="nav-item">
                            <Link className="nav-link" to="/setup-check">
                                React Setup Check
                            </Link>
                        </li>
                        <li className="nav-item">
                            <Link className="nav-link" to="/exchange-rates/today">
                                Dzisiejsze kursy
                            </Link>
                        </li>
                        <li className="nav-item">
                            <Link className="nav-link" to={`/exchange-rates/date/${today}`}>
                                Kursy wg daty
                            </Link>
                        </li>
                    </ul>
                </div>
            </nav>
            <Switch>
                <Redirect exact from="/" to="/setup-check" />
                <Route path="/setup-check" component={SetupCheck} />
                <Route path="/exchange-rates/today" component={TodayExchangeRates} />

                <Route exact path="/exchange-rates/date">
                    <Redirect to={`/exchange-rates/date/${today}`} />
                </Route>

                <Route path="/exchange-rates/date/:date" component={DateExchangeRates} />
            </Switch>
        </div>
    );
}

export default App;
