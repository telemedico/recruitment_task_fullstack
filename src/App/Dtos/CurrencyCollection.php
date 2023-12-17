<?php

declare(strict_types=1);

namespace App\Dtos;

use Countable;
use Iterator;

class CurrencyCollection implements Iterator, Countable
{
    private $position;

    private $items;

    public function add(Currency $currency): CurrencyCollection
    {
        $this->items[] = $currency;

        return $this;
    }

    public function current(): Currency
    {
        return $this->items[$this->position];
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function count(): int
    {
        return count($this->items);
    }
}