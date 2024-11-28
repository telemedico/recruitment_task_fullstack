import React from 'react';
import BackgroundBox from "./BackgroundBox";
import {getAdjustedTodayString} from "../utils/date";

export default ({ onDateChange, value }) => {
    const today = getAdjustedTodayString()
    const minDate = '2023-01-01';
    return (
        <BackgroundBox>
            <h1>Kurs wymiany walut</h1>
            <form>
                <div className="form-group">
                    <label htmlFor="date">Data kursu</label>
                    <input
                        id="date"
                        type="date"
                        className="form-control"
                        aria-describedby="dateHelp"
                        defaultValue={value}
                        min={minDate}
                        max={today}
                        onChange={(e) => onDateChange(e.target.value)}
                    />
                    <small id="dateHelp" className="form-text text-muted">Wybierz datę, dla której kurs chcesz
                        zobaczyć. Kurs na dziś pojawi się po godzinie 12:00</small>

                </div>
            </form>
        </BackgroundBox>
    );
};