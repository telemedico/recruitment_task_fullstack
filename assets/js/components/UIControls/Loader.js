import React, { Fragment } from 'react';

const Loader = () => {
    return (
        <Fragment>
            <div className={'text-center'}>
                <span className="fa fa-spin fa-spinner fa-4x"></span>
            </div>
        </Fragment>
    );
};

export default Loader;