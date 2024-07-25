import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router, Route, Switch } from 'react-router-dom';
import '../css/app.css';
import Home from './components/Home';
import ExchangeRatesPage from './pages/ExchangeRatesPage/ExchangeRatesPage';

function App() {
    return (
        <Router>
            <Switch>
                <Route path="/exchange-rates" component={ExchangeRatesPage} />
                <Route path="/" component={Home} />
            </Switch>
        </Router>
    );
}

ReactDOM.render(<App />, document.getElementById('root'));
