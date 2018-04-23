# MSISDN-INFO

## Description

Dockerized Symfony 4 based microservice that returns basic info about a given [MSISDN](https://en.wikipedia.org/wiki/MSISDN)

## Installation

// to-do

## Usage

Send a GET request to the service in the following format:

```
http://service.url/transform?msisdn=<MSISDN>
```

Response format is JSON with the following fields:
```json
{
    "mno_identifier": "string", // network operator name
    "country_code": integer, // country calling code (e.g. 386 in case of Slovenian number)
    "country_identifier": "string", // ISO 3166-1-alpha-2 formatted
    "subscriber_number": "string" // number without country and operator codes
}
```
