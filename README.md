# JSend

[![Build Status](https://travis-ci.org/EvanDarwin/JSend.svg?branch=master)](https://travis-ci.org/EvanDarwin/JSend)
[![Code Climate](https://codeclimate.com/github/EvanDarwin/JSend/badges/gpa.svg)](https://codeclimate.com/github/EvanDarwin/JSend)
[![Test Coverage](https://codeclimate.com/github/EvanDarwin/JSend/badges/coverage.svg)](https://codeclimate.com/github/EvanDarwin/JSend)

A tiny PHP library that generates JSON responses based on the original [JSend specification](http://labs.omniti.com/labs/jsend).

The specification has been slightly modified to make use for APIs, in which the are now optional `code` and `message` attributes. Both are meant to be human readable, and will not show up if not supplied.

## Installation

Install JSend via Composer:

```sh
$ composer require evandarwin/jsend
```

## Usage

You can use JSend like so:

```php
<?php

use EvanDarwin\JSend\JSendBuilder;

$builder = new JSendBuilder();

// This will return a JSendResponse
$response = $builder->success()->data(['id' => 3])->code(12)->message("Hello")->get();

// Output the JSON
header('Content-Type: application/json');
echo $response->getResponse();
```

And the response will be formed like so:

```json
{
  "status": "success",
  "message": "Hello",
  "code": 12,
  "data": {
    "id": 3
  }
}
```

Alternative statuses include:
 - **fail** - For when the user has done something wrong, and they should fix it before requesting again.
 - **error** - An internal server error or something that's not the issue of the user's request

```php
<?php
// These alternatives statuses can be set like so

// For failure
$builder->failed();

// For error
$builder->error();
```

## License

Licensed under the MIT license. See the LICENSE file for information.
