import React from 'react';
export default ({ children }) => {
    return (
        <div className="bg-light rounded shadow m-2 p-4 container">
            {children}
        </div>
    );
};