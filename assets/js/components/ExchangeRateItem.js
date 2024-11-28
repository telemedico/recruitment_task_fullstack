import React from "react";
export default ({data, primary}) => {
    return (
        <div className={`card ${primary ? 'border-primary' : ''}`} style={{marginBottom: '1rem'}}>
            <div className="card-body">
                <h5 className="card-title">{data.name} ({data.code})</h5>
                <p className="card-text">
                    <strong>Buy Price:</strong> {data.buyPrice ? data.buyPrice.toFixed(2) : 'N/A'} PLN
                    <br/>
                    <strong>Sell Price:</strong> {data.sellPrice ? data.sellPrice.toFixed(2) : 'N/A'} PLN
                    <br/>
                    <strong>NBP Price:</strong> {data.nbpPrice ? data.nbpPrice.toFixed(4) : 'N/A'} PLN
                </p>
            </div>
        </div>
    );
}