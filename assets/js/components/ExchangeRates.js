import React from 'react';

const ExchangeRates = () => {
    return (
        <div>
            <section className="row-section">
                    <div className="container">
                        <div className="row mt-5">
                            <div className="col-md-8 offset-md-2">
                                <h2 className="text-center"><span>Kursy Wymiany Walut</span></h2>

                                    <div className={'text-center'}>
                                        <span className="fa fa-spin fa-spinner fa-4x"></span>
                                    </div>
                            </div>
                        </div>
                    </div>
            </section>
        </div>
    );
}

export default ExchangeRates;
