import React, { useState, useEffect } from 'react';
import './CurrencyCalculator.css';

const CurrencyCalculator = ({ currency, onClose }) => {
    const [amount, setAmount] = useState(0);
    const [result, setResult] = useState(0);
    const [mode, setMode] = useState('sell');

    useEffect(() => {
        setAmount(1);
        calculateResult(1, 'sell')
        setMode('sell');
    }, [currency]);

    if (!currency) return null;

    useEffect(() => {
        calculateResult(amount, mode);
    }, [amount, mode]);

    const handleAmountChange = (e) => {
        const value = e.target.value;
        setAmount(value);
        calculateResult(value, mode);
    };

    const handleResultChange = (e) => {
        const value = e.target.value;
        setResult(value);
        calculateAmount(value, mode);
    };

    const handleModeChange = (e) => {
        const newMode = e.target.value;
        setMode(newMode);
        calculateResult(amount, newMode);
    };

    const calculateResult = (amount, mode) => {
        let rate = mode === 'buy' ? currency.buyRate : currency.sellRate;
        setResult(amount * rate);
    };

    const calculateAmount = (result, mode) => {
        let rate = mode === 'buy' ? currency.buyRate : currency.sellRate;
        setAmount(result / rate);
    };

    const getFlagClass = (code) => {
        switch (code) {
            case 'EUR': return 'fi fi-eu';
            case 'USD': return 'fi fi-us';
            case 'CZK': return 'fi fi-cz';
            case 'IDR': return 'fi fi-id';
            case 'BRL': return 'fi fi-br';
            default: return '';
        }
    };

    return (
        <div className="currency-calculator card">
            <div className="card-body">
                <h2 className="card-title">
                    <span className=" d-inline-flex align-items-center">
                        <span className={`flag ${getFlagClass(currency.code)}`} style={{ marginRight: '10px' }}></span> {currency.code} Calculator
                    </span>
                </h2>
                <div className="mb-3">
                    <label className="form-label">Mode</label>
                    <select className="form-select" value={mode} onChange={handleModeChange} disabled={currency.buyRate === null}>
                        <option value="buy">Buy</option>
                        <option value="sell">Sell</option>
                    </select>
                </div>
                <div className="input-group mb-3">
                    <div className="input-group-prepend">
                        <span className="input-group-text"><span className={`flag ${getFlagClass(currency.code)}`}></span> {currency.code}</span>
                    </div>
                    <input
                        type="number"
                        className="form-control"
                        value={amount}
                        onChange={handleAmountChange}
                        placeholder="Amount"
                    />
                </div>
                <div className="input-group mb-3">
                    <div className="input-group-prepend">
                        <span className="input-group-text"><span className="flag fi fi-pl"></span> PLN</span>
                    </div>
                    <input
                        type="number"
                        className="form-control"
                        value={result || ''}
                        onChange={handleResultChange}
                    />
                </div>
                <button className="btn btn-secondary btn-block" onClick={onClose}>Close</button>
            </div>
        </div>
    );
};

export default CurrencyCalculator;
