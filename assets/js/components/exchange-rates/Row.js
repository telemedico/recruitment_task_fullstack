import React from 'react'

const Row = ({
    code,
    currency,
    mid,
    buyPrice,
    sellPrice,
    todayMid,
    todayBuyPrice,
    todaySellPrice,
}) => (
    <tr>
        <td>{code}</td>
        <td>{currency}</td>
        <td>{mid}</td>
        <td>{buyPrice === 0 ? '' : buyPrice}</td>
        <td>{sellPrice === 0 ? '' : sellPrice}</td>
        <td>{todayMid}</td>
        <td>{todayBuyPrice === 0 ? '' : todayBuyPrice}</td>
        <td>{todaySellPrice === 0 ? '' : todaySellPrice}</td>
    </tr>
)

export default Row
