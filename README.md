# DataCacheHandler

I've got a handful of scripts that function as notifiers. They run on a schedule and consume RSS feeds or API endpoints or scrape web pages. Then they compare the 
data retrieved with what was last retrieved and notify me of any changes.

To do that, they need a way of caching whatever "last retrieved" was. This is a simple helper class for doing that. It works for string data and anything that can 
be JSON encoded/decoded or serialized/unserialized.

## Usage

```php
use cjrasmussen\DataCacheHandler\DataCacheHandler;

$dataCacheHelper = new DataCacheHandler('/home/cjrasmussen/scripts/.cache/');

$dataCacheHelper->initialize(__FILE__);

$cache = $dataCache->readSerialized();

$cache[] = 'Some new item to cache';

$dataCacheHelper->writeSerialized();
```

## Installation

Simply add a dependency on cjrasmussen/DataCacheHandler to your composer.json file if you use [Composer](https://getcomposer.org/) to manage the dependencies of your project:

```sh
composer require cjrasmussen/DataCacheHandler
```

Although it's recommended to use Composer, you can actually include the file(s) any way you want.


## License

DataCacheHandler is [MIT](http://opensource.org/licenses/MIT) licensed.