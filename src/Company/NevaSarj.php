<?php
namespace Chargist\Fetcher\Company;

use Dom\HTMLDocument;
use Chargist\Fetcher\{ChargePrice, CompanyPrice, ChargerType};
use Chargist\Fetcher\CompanyInterface;
use Chargist\Fetcher\Parser;

class NevaSarj implements CompanyInterface
{
    public string $title = "NevaSarj";
    public string $priceUrl = "https://www.nevasarj.com/sayfalar/istasyonlarimiz/arac-sarj-ac-dc-kwh-fiyat-tarifeleri.html";
    public array $headers = [];
    public function parseData(string $htmlSource): CompanyPrice
    {
        $dom = HTMLDocument::createFromString($htmlSource);
        $dcPriceD = $dom->querySelector(
            "table:nth-child(8) tbody > tr > td:nth-child(2) > b",
        );
        $acPriceD = $dom->querySelector(
            "table:nth-child(9) tbody > tr > td:nth-child(2) > b",
        );

        $acPrice = Parser::Price($acPriceD->textContent);
        $dcPrice = Parser::Price($dcPriceD->textContent);

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
