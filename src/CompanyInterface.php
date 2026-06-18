<?php

namespace Chargist\Fetcher;

interface CompanyInterface {
    public string $title { get; }
    public string $priceUrl { get; }
    public array $headers { get; }

    public function parseData(string $htmlSource): CompanyPrice;
}
