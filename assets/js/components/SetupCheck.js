import React, { useState, useEffect } from 'react';
import axios from 'axios';
import config from '../config';

function SetupCheck() {
    const [setupCheck, setSetupCheck] = useState({});
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const checkApiSetup = async () => {
            try {
                const testParam = 1;
                const response = await axios.get(config.baseUrl + '/api/setup-check', {
                    params: { testParam }
                });
                const responseIsOK = response.data && response.data.testParam === testParam;
                setSetupCheck(responseIsOK);
            } catch (error) {
                console.error(error);
                setSetupCheck(false);
            } finally {
                setLoading(false);
            }
        };

        checkApiSetup();
    }, []);

    return (
        <div>
            <section className="row-section">
                <div className="container">
                    <div className="row mt-5">
                        <div className="col-md-8 offset-md-2">
                            <h2 className="text-center">
                                <span>This is a test</span> @ Telemedi
                            </h2>

                            {loading ? (
                                <div className={'text-center'}>
                                    <span className="fa fa-spin fa-spinner fa-4x"></span>
                                </div>
                            ) : (
                                <div className={'text-center'}>
                                    {setupCheck === true ? (
                                        <h3 className={'text-success text-bold'}>
                                            <strong>React app works!</strong>
                                        </h3>
                                    ) : (
                                        <h3 className={'text-error text-bold'}>
                                            <strong>React app doesn't work :(</strong>
                                        </h3>
                                    )}
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </section>
        </div>
    );
}

export default SetupCheck;
