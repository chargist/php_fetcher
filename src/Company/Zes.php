<?php
namespace Chargist\Fetcher\Company;

use Dom\HTMLDocument;
use Chargist\Fetcher\{ChargePrice, CompanyPrice, ChargerType};
use Chargist\Fetcher\CompanyInterface;
use Chargist\Fetcher\Parser;

class Zes implements CompanyInterface
{
    public string $title = "ZES";
    public string $priceUrl = "https://zes.net/tr/fiyatlandirma";
    public array $headers = [];
    public function parseData(string $htmlSource): CompanyPrice
    {
        $dom = HTMLDocument::createFromString($htmlSource);
        $data = $dom->querySelectorAll(".pricing-card");

        $acPrice = Parser::Price(
            $data->item(0)->querySelector("div.leading-8:nth-child(1)")
                ->textContent,
        );
        $dcPrices = $data
            ->item(1)
            ->querySelectorAll("div.leading-8:nth-child(1)");

        $dcPrice = Parser::Price($dcPrices->item(0)->textContent);
        $hpcPrice = Parser::Price($dcPrices->item(1)->textContent);

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
                new ChargePrice(
                    type: ChargerType::HPC,
                    price: $hpcPrice,
                    currency: "₺", // TODO
                ),
            ],
        );
    }
}
