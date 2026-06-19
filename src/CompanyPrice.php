<?php

namespace Chargist\Fetcher;

class CompanyPrice
{
    /**
     * @param array<ChargePrice> $prices
     */
    public function __construct(public string $title, public array $prices) {}
}

enum ChargerType
{
    case AC;
    case DC;
    case HPC;
}

class ChargePrice
{
    public function __construct(
        public ChargerType $type,
        public float $price,
        public string $currency,
    ) {}
}
