# BlogLite ![main](https://github.com/g737a6b/php-blog/workflows/main/badge.svg)

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
docker run -it --rm -v $(pwd):/app composer:2.6.6 install
```

### Run tests

```sh
docker run -it --rm -v $(pwd):/app -w /app php:8.3 ./vendor/bin/phpunit ./tests
```

## License

[The MIT License](http://opensource.org/licenses/MIT)

Copyright (c) 2023 [Hiroyuki Suzuki](https://mofg-in-progress.com)
