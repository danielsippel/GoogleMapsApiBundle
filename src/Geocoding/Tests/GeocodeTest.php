<?php

namespace GoogleMapsApiBundle\Geocoding;

require '../../../vendor/autoload.php';

class GeocodeTest extends \PHPUnit_Framework_TestCase {

    public function testSetGetTimeout() {

        $timeout = 12;
        $client = $this->getMockBuilder('\GuzzleHttp\Client')->disableOriginalConstructor()->getMock();
        $geocode = new Geocode($client);
        $geocode->setTimeoutSeconds($timeout);
        $this->assertEquals($timeout, $geocode->getTimeoutSeconds());
    }

    public function testSetGetRegion() {

        $region = 'de';
        $client = $this->getMockBuilder('\GuzzleHttp\Client')->disableOriginalConstructor()->getMock();
        $geocode = new Geocode($client);
        $geocode->setRegion($region);
        $this->assertEquals($region, $geocode->getRegion());
    }

    public function testGetApiResponseThrowsExceptionOnBadStatus() {

        $region = 'de';
        $address = 'Berlin TU';
        $requestUrlExpected = 'https://maps.googleapis.com/maps/api/geocode/json'.
            '?sensor=false&address=' . urlencode($address) . '&region=' . $region;

        $response = $this->getMockBuilder('\GuzzleHttp\Message\Response')->disableOriginalConstructor()->getMock();
        $response->expects($this->once())->method('json')->will($this->returnValue(
            array(
                'status' => 'INVALID_REQUEST'
            )
        ));

        $client = $this->getMockBuilder('\GuzzleHttp\Client')->disableOriginalConstructor()->getMock();
        $client->expects($this->once())->method('get')->with($requestUrlExpected)->will($this->returnValue($response));
        $geocode = new Geocode($client);
        $geocode->setRegion($region);

        $this->setExpectedException('GoogleMapsApiBundle\Geocoding\Exception\GeocodeException');
        $geocode->getApiResponse($address);
    }

    public function testGetApiResponseThrowsExceptionOnGuzzleException() {

        $region = 'de';
        $address = 'Berlin TU';
        $requestUrlExpected = 'https://maps.googleapis.com/maps/api/geocode/json'.
            '?sensor=false&address=' . urlencode($address) . '&region=de';

        $response = $this->getMockBuilder('\GuzzleHttp\Message\Response')->disableOriginalConstructor()->getMock();
        $response->expects($this->once())->method('json')->will($this->throwException(new \Exception()));

        $client = $this->getMockBuilder('\GuzzleHttp\Client')->disableOriginalConstructor()->getMock();
        $client->expects($this->once())->method('get')->with($requestUrlExpected)->will($this->returnValue($response));
        $geocode = new Geocode($client);
        $geocode->setRegion($region);

        $this->setExpectedException('GoogleMapsApiBundle\Geocoding\Exception\GeocodeException');
        $geocode->getApiResponse($address);
    }

    public function testGetApiResponse() {

        $region = 'de';
        $address = 'Berlin TU';
        $requestUrlExpected = 'https://maps.googleapis.com/maps/api/geocode/json'.
            '?sensor=false&address=' . urlencode($address) . '&region=de';

        $response = $this->getMockBuilder('\GuzzleHttp\Message\Response')->disableOriginalConstructor()->getMock();
        $response->expects($this->once())->method('json')->will($this->returnValue(
                array(
                    'status' => 'OK',
                    'result' => array(0 =>
                        array('formatted_address' => 'Technische Universität Berlin, 10623 Berlin, Deutschland')
                    )
                )
            ));

        $client = $this->getMockBuilder('\GuzzleHttp\Client')->disableOriginalConstructor()->getMock();
        $client->expects($this->once())->method('get')->with($requestUrlExpected)->will($this->returnValue($response));
        $geocode = new Geocode($client);
        $geocode->setRegion($region);

        $response = $geocode->getApiResponse($address);
        $this->assertEquals(
            $response['result'][0]['formatted_address'],
            'Technische Universität Berlin, 10623 Berlin, Deutschland'
        );
    }
}