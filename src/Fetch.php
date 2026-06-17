<?php

namespace Chargist\Fetcher;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\{Client, Pool};
use GuzzleHttp\Psr7\{Request, Response};

class Fetch
{
    /**
     * @param Client $client
     * @param array<string, string> $headers
     */
    public function __construct(
        protected Client $client = new Client(),
        protected array $headers = [
            "User-Agent" =>
                "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36",
        ],
    ) {
        return $this;
    }

    /**
     * @param array<CompanyInterface> $companies
     * @param int $cc
     * @return array<array<CompanyPrice>>
     */
    public function it(array $companies = [], int $cc = 5): array
    {
        $companyPrices = [];
        $failedCompanies = [];
        $filteredCompanies = $this->filter_companies($companies);

        $reqs = function () use ($filteredCompanies) {
            foreach ($filteredCompanies as $company) {
                yield new Request("GET", $company->priceUrl, $this->headers);
            }
        };

        $pool = new Pool($this->client, $reqs(), [
            "concurrency" => $cc,
            "fulfilled" => function (Response $response, $index) use (
                $filteredCompanies,
                &$companyPrices,
            ) {
                $compData = $filteredCompanies[$index]->parseData(
                    $response->getBody()->getContents(),
                );
                $companyPrices[] = $compData;
            },
            "rejected" => function (RequestException $reason, $index) use (
                $filteredCompanies,
                &$failedCompanies,
            ) {
                $failedCompanies[] = [
                    "company" => $filteredCompanies[$index]->title,
                    "reason" => $reason,
                ];
            },
        ]);
        $promise = $pool->promise();
        $promise->wait();

        return [$companyPrices, $failedCompanies];
    }

    /**
     * @param array<CompanyInterface> $companies
     * @return array<CompanyInterface>
     */
    protected function filter_companies(array $companies = []): array
    {
        // TODO
        if (count($companies) == 0) {
            $allCompanies = $this->findImplementationsInDir(
                __DIR__ . "/Company",
                CompanyInterface::class,
            );
            return $allCompanies;
        }

        return $companies;
    }

    /**
     * @return array
     */
    protected function findImplementationsInDir(
        string $dir,
        string $interface,
    ): array {
        $implementations = [];

        // Recursive directory iterator to find all PHP files
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir),
        );

        foreach ($iterator as $file) {
            if ($file->isDir() || $file->getExtension() !== "php") {
                continue;
            }

            // Require the file to load the class definition into memory
            require_once $file->getPathname();
        }

        // Filter out loaded classes that implement the interface
        foreach (get_declared_classes() as $className) {
            if (is_subclass_of($className, $interface)) {
                $implementations[] = new $className();
            }
        }

        return $implementations;
    }
}
