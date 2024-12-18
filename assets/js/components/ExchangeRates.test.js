import React from 'react';
import { render, screen } from '@testing-library/react';
import '@testing-library/jest-dom/extend-expect';
import ExchangeRates from './ExchangeRates';

test('renders date input', () => {
    render(<ExchangeRates />);
    const dateInput = screen.getByLabelText(/Select date/i);
    expect(dateInput).toBeInTheDocument();
});

test('shows loading spinner initially', () => {
    render(<ExchangeRates />);
    const spinner = screen.getByRole('status', { hidden: true });
    const spinnerEl = document.querySelector('.fa-spinner');
    expect(spinnerEl).toBeTruthy();
});
