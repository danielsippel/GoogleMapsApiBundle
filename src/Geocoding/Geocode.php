<?php

namespace GoogleMapsApiBundle\Geocoding;

use GoogleMapsApiBundle\Geocoding\Exception\GeocodeException;
use GuzzleHttp\Client;

class Geocode {

    /**
     * @var Client
     */
    private $client;

    /**
     * @var int
     */
    private $timeoutSeconds = 3;

    /**
     * @var string
     */
    private $region;

    const REQUEST_URL = 'https://maps.googleapis.com/maps/api/geocode/json?sensor=%s&address=%s&region=%s';

    /**
     * @param Client $client
     */
    public function __construct(Client $client) {
        $this->client = $client;
    }

    /**
     * @param string $address
     * @return array
     * @throws GeocodeException
     */
    public function getApiResponse($address) {
        try {
            $url = $this->getRequestUrl($address);
            $response = $this->client->get($url, array('timeout' => $this->timeoutSeconds));

            $data = $response->json();
            $this->throwExceptionOnError($data);
            return $data;

        } catch (\Exception $exception) {
            throw new GeocodeException($exception->getMessage());
        }
    }

    /**
     * @param int $timeoutSeconds
     */
    public function setTimeoutSeconds($timeoutSeconds)
    {
        $this->timeoutSeconds = (int)$timeoutSeconds;
    }

    /**
     * @return int
     */
    public function getTimeoutSeconds()
    {
        return $this->timeoutSeconds;
    }

    /**
     * https://developers.google.com/maps/documentation/geocoding/#RegionCodes
     *
     * @param string $region
     */
    public function setRegion($region)
    {
        $this->region = (string)$region;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param array $data
     * @throws GeocodeException
     */
    private function throwExceptionOnError($data) {
        if ($data['status'] != 'OK') {
            throw new GeocodeException($data['status'] . ': ' . $data['error_message']);
        }
    }

    /**
     * @param string $address
     * @return string
     */
    private function getRequestUrl($address)
    {
        return sprintf(
            self::REQUEST_URL,
            urlencode('false'),
            urlencode($address),
            urlencode($this->region)
        );
    }
}