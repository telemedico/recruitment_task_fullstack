import React, {useEffect, useMemo, useState} from "react";
import {getCurrentDate} from "../utils";
import axios from "axios";

export default function useBackendAPI(date) {
    const apiUrl = 'http://telemedi-zadanie.localhost/api';
    const currDate = getCurrentDate();
    const [data, setData] = useState({});
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);

    let fetchData = (date, abortController) => {
        //check if the data has already been fetched
        if (data[date]) return;

        setLoading(true);
        axios.get(`${apiUrl}/exchange-rates/${date}`,
            {signal: abortController.signal}
        ).then(response => {
            const fetchedData = response.data;
            if (!fetchedData || !Array.isArray(fetchedData)) {
                throw Error("Unexpected error.");
            }

            //transform array of objects into object with currency codes as keys
            const transformedData = fetchedData.reduce((acc, item) => {
                const key = Object.keys(item)[0];
                acc[key] = item[key];
                return acc;
            }, {});

            setData((data) => {
                return {
                    ...data,
                    [date]: transformedData
                }
            })
        }).catch(function (error) {
            //axios throws cancellation as an error, just ignore
            if (axios.isCancel(error)) { return;}

            //check if it's an error with a custom message
            const errorMsg = error.response?.data?.error;
            if (errorMsg) {
                setError(errorMsg);
                alert(`Error ${error.response.status}: ${errorMsg}`);
                return;
            }
            //otherwise just alert
            setError(error);
            alert(error);
        }).finally(() => {
            setLoading(false);
        });
    };

    //no matter the chosen date we'll have to fetch data for the current date
    useEffect(() => {
        const abortController = new AbortController();
        fetchData(currDate,abortController);

        return () => {
            abortController.abort();
        }
    }, [])

    //fetch data for the chosen date
    useEffect(() => {
        if (date === currDate) return;
        const abortController = new AbortController();
        fetchData(date,abortController);

        return () => {
            abortController.abort();
        }
    }, [date]);


    return {data, loading, error}
}