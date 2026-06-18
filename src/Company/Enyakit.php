<?php
namespace Chargist\Fetcher\Company;

use Chargist\Fetcher\{ChargePrice, CompanyPrice, ChargerType};
use Chargist\Fetcher\CompanyInterface;
use Chargist\Fetcher\Parser;
use Dom\HTMLDocument;

class Enyakit implements CompanyInterface
{
    public string $title = "enyakit";
    public string $priceUrl = "https://www.enyakit.com.tr/ucretlendirme";
    public array $headers = [];
    public function parseData(string $htmlSource): CompanyPrice
    {
        $dom = HTMLDocument::createFromString($htmlSource);
        $data = $dom->querySelector(".about-content");

        $acPrice = Parser::Price($data->textContent);
        $dcPrice = $acPrice;

        return new CompanyPrice(
            title: $this->title,
            prices: [
                new ChargePrice(
                    type: ChargerType::AC,
                    price: $acPrice,
                    currency: "₺", // TODO
                ),
                new ChargePrice(
                    type: ChargerType::DC,
                    price: $dcPrice,
                    currency: "₺", // TODO
                ),
            ],
        );
    }
}
