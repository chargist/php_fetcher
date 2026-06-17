<?php
namespace Chargist\Fetcher\Company;

use Dom\HTMLDocument;
use Chargist\Fetcher\{ChargePrice, CompanyPrice, ChargerType};
use Chargist\Fetcher\CompanyInterface;
use Chargist\Fetcher\Parser;

class Monokon implements CompanyInterface
{
    public string $title = "monokon";
    public string $priceUrl = "https://www.monokonev.com/Price";
    public function parseData(string $htmlSource): CompanyPrice
    {
        $dom = HTMLDocument::createFromString($htmlSource);
        $data = $dom->querySelectorAll(".contant_form strong");

        $acPrice = Parser::Price($data->item(1)->textContent);
        $dcPrice = Parser::Price($data->item(0)->textContent);

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
