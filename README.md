# Fork
This is a fork of https://github.com/ErickTamayo/laravel-scout-elastic with a few changes:

https://github.com/ErickTamayo/laravel-scout-elastic/compare/master...bethinkpl:master

# Laravel Scout Elasticsearch Driver

[![Latest Stable Version](https://poser.pugx.org/bethinkpl/elastic-scout/v)](//packagist.org/packages/bethinkpl/elastic-scout) [![Total Downloads](https://poser.pugx.org/bethinkpl/elastic-scout/downloads)](//packagist.org/packages/bethinkpl/elastic-scout) [![License](https://poser.pugx.org/bethinkpl/elastic-scout/license)](//packagist.org/packages/bethinkpl/elastic-scout)

This package provides an [Elasticsearch](https://www.elastic.co/products/elasticsearch) driver for Laravel Scout.

## Contents

- [Installation](#installation)
- [Usage](#usage)
- [Credits](#credits)
- [License](#license)

## Installation

You can install the package via composer:

``` bash
composer require bethinkpl/elastic-scout
```

Laravel will automatically register the driver service provider.

#### Install elasticsearch-php client

For use this library we recomend using the latest version at this time `(^7.9)`

```bash
composer require elasticsearch/elasticsearch
```

Then you should publish the Elasticsearch configuration using the `vendor:publish` Artisan command.

```
php artisan vendor:publish --provider="ScoutEngines\Elasticsearch\LaravelElasticsearchProvider"
```

### Setting up Elasticsearch configuration

After you've published the Laravel Scout package configuration, you need to set your driver to `elasticsearch` and add its configuration:
You must have a Elasticsearch server up and running, indices can be created with an Artisan command; see below.

After you've published the Laravel Scout package configuration:

```php
// config/scout.php
...
    // Set your driver to elasticsearch
    'driver' => env('SCOUT_DRIVER', 'elasticsearch'),
...
    /*
    |--------------------------------------------------------------------------
    | Elasticsearch Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Elasticsearch settings.
    |
    */
    'elasticsearch' => [
        'hosts' => [
            env('ELASTICSEARCH_HOST', 'localhost'),
            // [
            //     'host'   => env('ELASTICSEARCH_HOST', 'localhost'),
            //     'port'   => env('ELASTICSEARCH_PORT', '9200'),
            //     'scheme' => env('ELASTICSEARCH_SCHEME', 'https'),
            //     'path'   => env('ELASTICSEARCH_PATH', '/elastic'),
            //     'user'   => env('ELASTICSEARCH_USER', 'username'),
            //     'pass'   => env('ELASTICSEARCH_PASS', 'password'),
            // ]
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Elasticsearch indices
    |--------------------------------------------------------------------------
    |
    | Here you can define your indices, with separate settings and mappings.
    | You can choose which index a model belongs to my overriding the
    | searchableWithin() method. A model will, by default, belong to the first
    | index listed here.
    |
    | You may specify your mappings in the model if you like that approach,
    | just make a static method, e.g. mapping() and refer to it here, like:
    |
    | 'mappings' => [
    |     'articles' => \App\Article::mapping()
    | ]
    |
    */
    'indices' => [

        'laravel' => [
            'settings' => [
                "number_of_shards" => 1,
                "number_of_replicas" => 0,
            ],
            'mappings' => [
                'articles' => [
                    'title' => [
                        'type' => 'string'
                    ]
                ]
            ]
        ],

        'another_index' => [
            'settings' => [
                "number_of_shards" => 1,
                "number_of_replicas" => 0,
            ],
            'mappings' => [
                'articles' => [
                    'title' => [
                        'type' => 'string'
                    ]
                ]
            ]
        ]

    ]
...
```

For host configuration you can refer to the official [Elasticsearch documentation](https://www.elastic.co/guide/en/elasticsearch/reference/current/index.html)

## Usage

Now you can use Laravel Scout as described in the [Laravel Scout official documentation](https://laravel.com/docs/8.x/scout)

### Creating Elasticsearch indexes with proper mapping
Elasticsearch can set default types to model fields on the first insert if you do not explicitly define them.
However; sometimes the defaults are not what you're looking for, or you need to define additional mapping properties.

In that case, we strongly recommend creating indices with proper mappings before inserting any data.
For that purpose, there is an Artisan command, called `elastic:make-indices {index}` which creates an index based on
the settings in your configuration file.

To create all indices from your config just ignore the `{index}` parameter and run:

```
php artisan elastic:make-indices
```

If the index exists you will be asked if you want to delete and recreate it, or you can use the `--force` flag.

To get information about your existing Elasticsearch indices you may want to use the following command:

```
php artisan elastic:indices
```

## Limitations

**Identifying Users**
Currrently user identification is not supported.

## Credits

- [Erick Tamayo](https://github.com/ericktamayo)
- [All Contributors](../../contributors)

## License

The MIT License (MIT).
