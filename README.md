# MSISDN-INFO

## Description

Dockerized Symfony 4 based microservice that returns basic info about a given [MSISDN](https://en.wikipedia.org/wiki/MSISDN)

## Installation

You will need PHP >7.1.3 and composer installed. Then clone the repo:
```
git clone https://github.com/DekiChan/msisdn-info.git
```

Install the dependencies:
```
cd msisdn-info
composer install
```

Now all it remains is to run the application. There are two ways of doing it:

1. With Docker
    Make sure you have Docker installed. Then in the root directory of the project just run:
    ```
    docker-compose up
    ```
    The application url will be `http://localhost:8080`

2. Locally
    ```
    php -S localhost:8080 -t public
    ```
    The application url will be `http://localhost:8080`

## Usage

Send a GET request to the service in the following format:

```
http://localhost:8080/transform?msisdn=<MSISDN>
```

Response format is JSON with the following fields:
```
{
    "mno_identifier": "string", // network operator name
    "country_code": integer, // country calling code (e.g. 386 in case of Slovenian number)
    "country_identifier": "string", // ISO 3166-1-alpha-2 formatted
    "subscriber_number": "string" // number without country and operator codes
}
```

In case of error response JSON will contain 'message' property with error explanation.
```
{
    "message": "string"
}
```
