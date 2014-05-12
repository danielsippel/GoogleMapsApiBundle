coding-example
==============

just a short PHP5 coding example - google maps geocoding api request
needs: composer
uses: guzzle http client, phpunit

```shell
cd GoogleMapsApiBundle
composer install
cd src/Geocode
php geocodingExample.php
```

```shell
cd GoogleMapsApiBundle/src/Geocoding/Tests
phpunit GeocodeTest.php
```
