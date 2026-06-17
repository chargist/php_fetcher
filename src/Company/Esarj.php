<?php
namespace Chargist\Fetcher\Company;

use Dom\HTMLDocument;
use Chargist\Fetcher\{ChargePrice, CompanyPrice, ChargerType};
use Chargist\Fetcher\CompanyInterface;
use Chargist\Fetcher\Parser;

class Esarj implements CompanyInterface
{
    public string $title = "Eşarj";
    public string $priceUrl = "https://esarj.com/fiyatlandirma";
    public function parseData(string $htmlSource): CompanyPrice
    {
        $dom = HTMLDocument::createFromString($htmlSource);
        $data = $dom->querySelectorAll(".amount");

        $acPrice = Parser::Price($data->item(0)->textContent);

        $prices = explode(" - ", $data->item(1)->textContent);
        $dcPrice = Parser::Price($prices[0]);
        $hpcPrice = Parser::Price($prices[1]);

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
