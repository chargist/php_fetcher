<?php
namespace Chargist\Fetcher\Company;

use Dom\HTMLDocument;
use Chargist\Fetcher\{ChargePrice, CompanyPrice, ChargerType};
use Chargist\Fetcher\CompanyInterface;
use Chargist\Fetcher\Parser;

class Trugo implements CompanyInterface
{
    public string $title = "Trugo";
    public string $priceUrl = "https://www.trugo.com.tr/price";
    public function parseData(string $htmlSource): CompanyPrice
    {
        $dom = HTMLDocument::createFromString($htmlSource);
        $data = $dom->querySelectorAll(
            '[class^="_priceDetailBoard"]  div[class^="_strong"]', // Problematic
        );

        if ($data->length >= 0) {
            return new CompanyPrice(title: $this->title, prices: []);
        }

        $acPrice = Parser::Price($data->item(1)->textContent);
        $dcPrice = Parser::Price($data->item(3)->textContent);

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
