import React, { useState } from "react";
import { Route, Redirect, Switch, NavLink } from "react-router-dom";
import SetupCheck from "./SetupCheck";
import ExchangeRates from "./ExchangeRates";

const Home = () => {
  const [menuOpen, setMenuOpen] = useState(false);

  const toggleMenu = () => {
    setMenuOpen(!menuOpen);
  };

  return (
    <div>
      <nav className="navbar">
        <NavLink className="navbar-brand" to="/" exact>
          Telemedi Zadanko
        </NavLink>
        <button
          className="burger"
          onClick={toggleMenu}
          aria-label="Toggle navigation"
        >
          ☰
        </button>
        <div className={`nav-links ${menuOpen ? "open" : ""}`}>
          <NavLink
            className="nav-link"
            activeClassName="active"
            to="/setup-check"
            onClick={() => setMenuOpen(false)}
          >
            React Setup Check
          </NavLink>
          <NavLink
            className="nav-link"
            activeClassName="active"
            to="/exchange-rates"
            onClick={() => setMenuOpen(false)}
          >
            Exchange Rates
          </NavLink>
        </div>
      </nav>
      <div className="container">
        <Switch>
          <Redirect exact from="/" to="/setup-check" />
          <Route path="/setup-check" component={SetupCheck} />
          <Route path="/exchange-rates" component={ExchangeRates} />
        </Switch>
      </div>
    </div>
  );
};

export default Home;
