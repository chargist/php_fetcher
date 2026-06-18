<?php
namespace Chargist\Fetcher\Company;

use Dom\HTMLDocument;
use Chargist\Fetcher\{ChargePrice, CompanyPrice, ChargerType};
use Chargist\Fetcher\CompanyInterface;
use Chargist\Fetcher\Parser;

class PowerSarj implements CompanyInterface
{
    public string $title = "Powerşarj";
    public string $priceUrl = "https://powersarj.com/fiyatlandirma/";
    public array $headers = [];
    public function parseData(string $htmlSource): CompanyPrice
    {
        $dom = HTMLDocument::createFromString($htmlSource);
        $data = $dom->querySelectorAll(".pricing-table-price");

        $acPrice = Parser::Price(
            $data->item(0)->querySelector(".price-number")->textContent,
        );
        $dcPrice = Parser::Price(
            $data->item(1)->querySelector(".price-number")->textContent,
        );
        $hpcPrice = Parser::Price(
            $data->item(2)->querySelector(".price-number")->textContent,
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
                new ChargePrice(
                    type: ChargerType::HPC,
                    price: $hpcPrice,
                    currency: "₺", // TODO
                ),
            ],
        );
    }
}
