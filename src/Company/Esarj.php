<?php
namespace Chargist\Fetcher\Company;

use Chargist\Fetcher\{ChargePrice, CompanyPrice, ChargerType};
use Chargist\Fetcher\CompanyInterface;

class Esarj implements CompanyInterface
{
    public string $title = "Eşarj";
    public string $priceUrl = "https://cms.esarj.com/api/pricing/tariffs";
    public array $headers = [
        "Authorization" =>
            "Bearer b4de6079fe9186f37175e393ade94bfb4586c8a18f8041db046947c4ea32fbb1a289c42bebfe7037f9ef03a366a9565d1a63815cef2d587b6e034e4ff4c609786de2d8311fff70e13f48bffa8150b197b0d5d29d765452c2706b12f3543982df82c0b7259078a330f01296202dd9570adac92b9a4654bdf74aa1cf0a06fca483",
    ];
    public function parseData(string $source): CompanyPrice
    {
        $data = json_decode($source);
        $acPrice = 0;
        $dcPrice = 0;
        $hpcPrice = 0;

        foreach ($data->data as $d) {
            if ("ACK1" == $d->serviceCode) {
                $acPrice = $d->price;
                continue;
            }

            if (0 == $dcPrice) {
                $dcPrice = $d->price;
                $hpcPrice = $d->price;
                continue;
            }

            if ($dcPrice > $d->price) {
                $dcPrice = $d->price;
            }

            if (
                str_starts_with($d->serviceCode, "DCK") &&
                $d->price > $hpcPrice
            ) {
                $hpcPrice = $d->price;
            }
        }

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

readonly class EsarjData
{
    public static function fromJson(string $jsonString): self
    {
        $data = json_decode($jsonString);

        return new self($data["name"] ?? "", $data["role"] ?? "");
    }
}
