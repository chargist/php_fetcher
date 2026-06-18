<?php

namespace Chargist\Fetcher;

use Raza\PHPImpersonate\{PHPImpersonate, ClientInterface};
use Raza\PHPImpersonate\Exception\RequestException;

class Fetch
{
    /**
     * @param Client $client
     * @param array<string, string> $headers
     */
    public function __construct(
        protected ClientInterface $client = new PHPImpersonate(
            browser: "chrome136",
            timeout: 4,
        ),
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
        $addFailed = function (CompanyInterface $company, string $reason) use (
            $failedCompanies,
        ): void {
            $failedCompanies[] = [
                "company" => $company->title,
                "reason" => "Unsuccessful request : {$reason}",
            ];
        };
        $filteredCompanies = $this->filter_companies($companies);

        foreach ($filteredCompanies as $company) {
            try {
                $res = $this->client->sendGet(
                    $company->priceUrl,
                    $company->headers,
                );

                if (!$res->isSuccess()) {
                    $addFailed(
                        $company,
                        "Unsuccessful request : {$res->status()}",
                    );
                    continue;
                }

                $companyPrices[] = $company->parseData($res->body());
            } catch (RequestException $e) {
                $addFailed($company, "Failed request : {$e->getMessage()}");
            }
        }

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
