<?php
namespace Chargist\Fetcher\Company;

use Dom\HTMLDocument;
use Chargist\Fetcher\{ChargePrice, CompanyPrice, ChargerType};
use Chargist\Fetcher\CompanyInterface;
use Chargist\Fetcher\Parser;

class Epsis implements CompanyInterface
{
    public string $title = "EPSIS";
    public string $priceUrl = "https://www.epsis.net/elektrikli-arac-sarj-tarifeleri";
    public function parseData(string $htmlSource): CompanyPrice
    {
        $dom = HTMLDocument::createFromString($htmlSource);
        $data = $dom->querySelectorAll("div[role=cell]");

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
