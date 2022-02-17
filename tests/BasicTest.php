<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Doctrine\Persistence\ManagerRegistry;

class BasicTest extends ApiTestCase
{
    	public function testAddCar() {
		$data['Type'] = 'new';
		$data['Msrp'] = '10000';
		$data['Year'] = '2011';
		$data['Make'] = 'Honda';
		$data['Model'] = 'CRV';
		$data['Miles'] = '8000';
		$data['Vin'] = 'ABCD123456789999';
		
		$data['TestUserName'] = $_ENV['TEST_USERNAME'];
		$data['TestToken'] = $_ENV['TEST_TOKEN'];
		
		$input['query'] = $data;
		
        	$response = static::createClient()->request('POST', '/addCar', $input);
		
        	$this->assertResponseIsSuccessful();
		$this->assertJson($response->getContent());		
		$result = json_decode($response->getContent(), true);		
		$this->assertArrayHasKey('Id', $result);
		
		global $carId;
		$carId = $result['Id'];
    	}
	
	public function testGetCar() {
		global $carId;
		
		$data['TestUserName'] = $_ENV['TEST_USERNAME'];
		$data['TestToken'] = $_ENV['TEST_TOKEN'];
		
		$input['query'] = $data;
		
		$response = static::createClient()->request('GET', "/getCar/$carId", $input);
		
        	$this->assertResponseIsSuccessful();
		$this->assertJson($response->getContent());		
		$result = json_decode($response->getContent(), true);		
		$this->assertArrayHasKey('Id', $result);
	}
	
	public function testGetCarList() {
		global $carId;
		
		$data['Id'] = $carId;
		$data['TestUserName'] = $_ENV['TEST_USERNAME'];
		$data['TestToken'] = $_ENV['TEST_TOKEN'];
		
		$input['query'] = $data;
		
		$response = static::createClient()->request('GET', "/getCarList", $input);
		
       		$this->assertResponseIsSuccessful();
		$this->assertJson($response->getContent());		
		$result = json_decode($response->getContent(), true);	
		$this->assertArrayHasKey('0', $result);
		$this->assertArrayHasKey('Id', $result[0]);
	}
	
	public function testEditCar() {
		global $carId;
		
		$data['Vin'] = 'AAAAA5555544444';
		$data['TestUserName'] = $_ENV['TEST_USERNAME'];
		$data['TestToken'] = $_ENV['TEST_TOKEN'];
		
		$input['query'] = $data;
		
		$response = static::createClient()->request('PATCH', "/editCar/$carId", $input);
		
        	$this->assertResponseIsSuccessful();
		$this->assertJson($response->getContent());		
		$result = json_decode($response->getContent(), true);	
		$this->assertArrayHasKey('Vin', $result);
		$this->assertEquals($result['Vin'], $data['Vin']);
	}
	
	public function testDelCar() {
		global $carId;
		
		$data['TestUserName'] = $_ENV['TEST_USERNAME'];
		$data['TestToken'] = $_ENV['TEST_TOKEN'];
		
		$input['query'] = $data;
		
		$response = static::createClient()->request('DELETE', "/delCar/$carId", $input);
		$this->assertResponseIsSuccessful();
		$this->assertJsonContains(['status' => 'success']);	
	}
}
