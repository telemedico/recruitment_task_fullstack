export const fetchExchangeRates = async (date) => {
    const response = await fetch(`/api/exchange-rates/${date}`);
    if (!response.ok) {
        throw new Error('Failed to fetch exchange rates');
    }
    return response.json();
};
