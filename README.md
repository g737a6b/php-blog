# BlogLite [![CircleCI](https://circleci.com/gh/g737a6b/php-blog.svg?style=svg)](https://circleci.com/gh/g737a6b/php-blog)

PHP blog library.

## Examples of use

## Installation

### Composer

Add a dependency to your project's `composer.json` file.

```json
{
	"require": {
		"g737a6b/php-blog": "*"
	}
}
```

## Development

### Install dependencies

```sh
docker run -it --rm -v $(pwd):/app composer:2.0 install
```

### Run tests

```sh
docker run -it --rm -v $(pwd):/app composer:2.0 run-script tests
```

## License

[The MIT License](http://opensource.org/licenses/MIT)

Copyright (c) 2021 [Hiroyuki Suzuki](https://mofg.net)
