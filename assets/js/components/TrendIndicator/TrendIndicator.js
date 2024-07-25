import React from 'react';
import './TrendIndicator.css';

const TrendIndicator = ({ difference }) => {
    const isPositive = difference >= 0;
    const trendClass = isPositive ? 'text-success' : 'text-danger';
    const iconClass = isPositive ? 'fa-arrow-up' : 'fa-arrow-down';

    return (
        <div className={`trend-indicator ${trendClass}`}>
            <i className={`fas ${iconClass}`}></i> &nbsp; {difference.toFixed(4)}
        </div>
    );
};

export default TrendIndicator;