<?php
namespace Chargist\Fetcher\Company;

use Chargist\Fetcher\{ChargePrice, CompanyPrice, ChargerType};
use Chargist\Fetcher\CompanyInterface;
use Chargist\Fetcher\Parser;
use Dom\HTMLDocument;

class DCharge implements CompanyInterface
{
    public string $title = "D-Charge";
    public string $priceUrl = "https://dcharge.com.tr/tarifeler";
    public function parseData(string $htmlSource): CompanyPrice
    {
        $dom = HTMLDocument::createFromString($htmlSource);
        $data = $dom->querySelectorAll(".tariffs__list-table--price");

        $acPrice = Parser::Price(
            $data->item(0)->querySelector("span")->textContent,
        );
        $dcPrice = Parser::Price(
            $data->item(1)->querySelector("span")->textContent,
        );

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
