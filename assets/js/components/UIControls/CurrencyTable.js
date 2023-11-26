import React, { Fragment } from 'react';

const CurrencyTable = ({currentDate, currentRates, errorCurrentDate, paramDate, paramRates, errorParamDate}) => {

    return (
        <table className="table table-striped table-hover">
        <thead className="thead-dark">
        <tr>
            <th colSpan="2"></th>
            <th colSpan="3">Selected Date:<br/>{paramDate}</th>
            <th colSpan="3">Today:<br/>{currentDate}</th>
        </tr>
        <tr>
            <th>Code</th>
            <th>Currency</th>
            <th>NBP</th>
            <th>Purchase</th>
            <th>Sell</th>
            <th>NBP</th>
            <th>Purchase</th>
            <th>Sell</th>
        </tr>
        </thead>
        {errorCurrentDate || errorParamDate ? (
            <tfoot>
                <tr>
                    <th colSpan="2"><p className="text-center">Additional information</p></th>
                    <th colSpan="3">{errorParamDate ? (
                        <p className="text-center">{paramDate} {errorParamDate}</p>
                    ) : (
                        <p className="text-center"> - </p>
                    )}
                    </th>
                    <th colSpan="3">{errorCurrentDate ? (
                        <p className="text-center">{currentDate} {errorCurrentDate}</p>
                    ) : (
                        <p className="text-center"> - </p>
                    )}
                    </th>
                </tr>
            </tfoot>
        ) : (
            null
        )}
        <tbody>
            {paramRates.map((paramRate, index) => (
                <tr key={index}>
                    <td>{paramRate.code}</td>
                    <td>{paramRate.name}</td>
                    <td><strong>{parseFloat(paramRate.midRate).toFixed(4)}</strong></td>
                    <td><strong>{paramRate.purchase ? parseFloat(paramRate.purchase).toFixed(4) : ''}</strong></td>
                    <td><strong>{parseFloat(paramRate.sell).toFixed(4)}</strong></td>

                    {currentRates.map((currentRate, index) => (
                        paramRate.code == currentRate.code ? (
                        <Fragment key={index}>
                            <td>{parseFloat(currentRate.midRate).toFixed(4)}</td>
                            <td>{currentRate.purchase ? parseFloat(currentRate.purchase).toFixed(4) : ''}</td>
                            <td>{parseFloat(currentRate.sell).toFixed(4)}</td>
                        </Fragment>
                        ) : (
                            null
                        )
                    ))}
                </tr>
            ))}
        </tbody>
    </table>
    );
};

export default React.memo(CurrencyTable);