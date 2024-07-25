import React from 'react';
import './TrendIndicator.css';

const TrendIndicator = ({ value }) => {
    if (value > 0) {
        return <span className="trend-up">↑ {value.toFixed(4)}</span>;
    } else if (value < 0) {
        return <span className="trend-down">↓ {Math.abs(value).toFixed(4)}</span>;
    } else {
        return <span className="trend-neutral">→ {value.toFixed(4)}</span>;
    }
};

export default TrendIndicator;