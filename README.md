coding-example
==============

just a short PHP5 coding example - google maps geocoding api request
uses: guzzle http client, pphunit

cd GoogleMapsApiBundle
composer install
cd src/Geocode
php geocodingExample.php



cd GoogleMapsApiBundle/src/Geocode/Tests
phpunit GeocodeTest.php
