<?php
namespace GoogleMapsApiBundle\Geocoding;

use GoogleMapsApiBundle\Geocoding\Exception\GeocodeException;
use GuzzleHttp\Client;

require '../../vendor/autoload.php';

$httpClient = new Client();

try {
    $googleMapsApiGeocode = new Geocode($httpClient);
    $googleMapsApiGeocode->setRegion('de');
    $result = $googleMapsApiGeocode->getApiResponse('Berlin TU');

    var_dump($result);

} catch (GeocodeException $exception) {

    var_dump($exception);
}
