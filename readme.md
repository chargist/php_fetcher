# Chargist Fetcher ⚡

A lightweight, efficient PHP library designed to crawl and aggregate real-time EV (Electric Vehicle) charging station prices from various providers in Turkey. It transforms raw web data into clean, structured PHP objects, making price comparison seamless.

## Features

- **Multi-Provider Support:** Scrapes pricing data from top charging networks (Astor, Enyakıt, Otopriz, etc.).
- **Robust Error Handling:** Returns data in a decoupled format, separating successfully fetched prices from failed ones to prevent app crashes.
- **PSR-4 Compliant:** Fully compatible with modern PHP standards and Composer autoloading.
- **Clean Data Structures:** Standardizes disparate website layouts into unified PHP objects.

---

## Supported Providers

The library currently supports crawling for the following companies:

| Company          | Namespace                              |
| :--------------- | :------------------------------------- |
| **Astor Şarj**   | `Chargist\Fetcher\Company\Astor`       |
| **ZES**          | `Chargist\Fetcher\Company\Zes`         |
| **Otopriz**      | `Chargist\Fetcher\Company\Otopriz`     |
| **EnYakit**      | `Chargist\Fetcher\Company\Enyakit`     |
| **PowerŞarj**    | `Chargist\Fetcher\Company\PowerSarj`   |
| **Trugo**        | `Chargist\Fetcher\Company\Trugo`       |
| **Epsis**        | `Chargist\Fetcher\Company\Epsis`       |
| **KŞarj**        | `Chargist\Fetcher\Company\Ksarj`       |
| **D-Charge**     | `Chargist\Fetcher\Company\DCharge`     |
| **Lumicle**      | `Chargist\Fetcher\Company\Lumicle`     |
| **Monokon**      | `Chargist\Fetcher\Company\Monokon`     |
| **Ovolt**        | `Chargist\Fetcher\Company\Ovolt`       |
| **Voltrun**      | `Chargist\Fetcher\Company\Voltrun`     |
| **Wat Mobilite** | `Chargist\Fetcher\Company\Watmobilite` |

---

## Installation

Run the following command in your project directory (ensure your `composer.json` is configured to map the `Chargist` namespace):

```bash
composer require chargist/php_fetcher
```

## Usage Example

The library utilizes a simple, fluent Fetch engine that executes the crawlers and returns an array destructuring pair: `[$successfulResults, $failedResults]`.

```php
<?php

require_once "./src/vendor/autoload.php";

use Chargist\Fetcher\Fetch;
use Chargist\Fetcher\Company\{
    Astor, Zes, Otopriz, Enyakit, PowerSarj,
    Trugo, Epsis, Ksarj, DCharge, Lumicle,
    Monokon, Ovolt, Voltrun, Watmobilite
};

// Execute
[$companyPrices, $failedCompanies] = new Fetch()->it([new Astor(), new Otopriz()]); // only astor & otopriz
[$companyPrices, $failedCompanies] = new Fetch()->it(); // all companies
```

## License

This project is open-source and licensed under the AGPL v3 License.

## How It Works Behind the Scenes

Disclaimer: This library relies on web scraping. If a provider fundamentally changes their website architecture, the respective company crawler may fail. The library isolates these failures into the $failedCompanies array so your main application never breaks.

## Legal Disclaimer ⚖️

This library is intended **solely for educational, research, and personal use**. It performs automated web scraping (crawling) to retrieve publicly available pricing data from third-party websites.

By using this library, you acknowledge and agree to the following terms:

- **User Responsibility:** The developer of this library accepts no responsibility or liability for how this tool is utilized. Any and all legal, financial, or technical liabilities arising from the use of this software belong strictly to the end-user.
- **Compliance with Terms of Service:** You are solely responsible for ensuring that your use of this library complies with the Terms of Service (ToS), `robots.txt` files, and copyright policies of the respective charging providers' websites.
- **Prohibition of Misuse:** You must not use this library in a manner that causes excessive traffic, inflicts Denial of Service (DoS), or disrupts the normal operations of the target servers.
- **No Guarantees:** This software is provided "as is," without any warranty of any kind, express or implied, including but not limited to accuracy, completeness, or availability.
