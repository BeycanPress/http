# BeycanPress HTTP Package

The BeycanPress HTTP package is a small utility package designed to be used in simple projects. With this package you can create HTTP requests, return responses and easily capture many types of parameters sent in the HTTP request.

The package contains 3 classes and they are:

Client`
Request`
Response`

Classes. Below we will try to explain which class does what with examples.

## Installation

You can install the package with the following command.

```bash
composer require beycanpress/http
```

## Usage

### Client

With the Client class, you can connect with APIs by creating HTTP requests.

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use BeycanPress\Http\Client;

$client = new Client();

// Methods

// If you are going to operate with specific endpoints on a single API, you can define a base URL.
$client->setBaseUrl(/* string API base url */);

// The package uses cURL, so if you want to add or delete any cURL option, you can use the following methods. Also, "CURLOPT_RETURNTRANSFER" in the example is "true" by default. If you need to make it "false", you need to use it as follows.
$client->addOption(CURLOPT_RETURNTRANSFER, false);
// or
$client->addOptions([
    CURLOPT_RETURNTRANSFER => false,
]);

// or if you want to delete an option
$client->deleteOption(CURLOPT_RETURNTRANSFER);
// or
$client->deleteOptions([
CURLOPT_RETURNTRANSFER
]);

// For example, many APIs require a bearer token in the header to allow access to the API. If you want to add or delete a header data, you can use the following methods.

$client->addHeader('Authorization', 'Bearer ' . $token);
// or
$client->addHeaders([
    'Authorization' => 'Bearer ' . $token,
]);

$client->deleteHeader('Authorization');
// or
$client->deleteHeaders([
    'Authorization'
]);

// If you wanna get cURL info or error, you can use the following methods.
$client->getInfo();
$client->getError();

// Request Methods

// You can use the following method to find out which request methods are accepted.
$client->getMethods();

// But we can say it directly here. The following methods are supported.

`GET`
`HEAD`
`POST`
`PUT`
`DELETE`
`CONNECT`
`OPTIONS`
`TRACE`
`PATCH`

// When creating a request, all you need to do is call the method of the request type, for example POST and GET are given below.

$client->post(/* string $url */, /* array $data */);

$client->get(/* string $url */, /* array $data */);

// if you want send raw data, just add true as third parameter.

$client->post(/* string $url */, /* array $data */, true);
```