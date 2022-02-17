<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Vehicle;
use App\Entity\User;

class MainController extends AbstractController
{
	public function __construct(RequestStack $requestStack, ManagerRegistry $doctrine)
    {
		$request = $requestStack->getCurrentRequest();
        $username = $request->headers->get('UserName') ? $request->headers->get('UserName') : '';
		$token = $request->headers->get('Token') ? $request->headers->get('Token') : '';
		
		$data['UserName'] = $username;
		$data['Token'] = $token;
		$user = $doctrine->getRepository(User::class)->findOneBy($data);
		
		if (!$user) {
			header('Content-Type: application/json; charset=UTF-8');
			header('Status: 501');
			echo json_encode(['status' => '501', 'message' => 'Unauthorized']);				
			exit;
		}
    }
	
    public function getCarList(Request $request, ValidatorInterface $validator, ManagerRegistry $doctrine, SerializerInterface $serializer) {
		//print_r($request->query->all()); exit;	

		$constraints = new Assert\Collection([
			'Id' => [new Assert\Optional([ new Assert\Regex(['pattern' => '/^[0-9]+$/']), new Assert\NotBlank ])],
			'DateAdded' => [new Assert\Optional([ new Assert\Regex(['pattern' => '/^\d{4}\-\d{2}\-\d{2}$/']), new Assert\NotBlank ])],
			'Type' => [new Assert\Optional([ new Assert\Choice(['used', 'new']), new Assert\NotBlank ])],
			'Year' => [new Assert\Optional([ new Assert\Length(['min' => 4, 'max' => 4]), new Assert\NotBlank ])],
			'Model' => [new Assert\Optional([ new Assert\NotBlank ])],
			'Make' => [new Assert\Optional([ new Assert\NotBlank ])],
			'Vin' => [new Assert\Optional([ new Assert\NotBlank ])],
			'Sort' => [new Assert\Optional([ new Assert\Choice(['Id', 'DateAdded', 'Type', 'Year', 'Model', 'Make', 'Vin']), new Assert\NotBlank ])],
			'Order' => [new Assert\Optional([ new Assert\Choice(['asc', 'desc']) ])],
			'Sort2' => [new Assert\Optional([ new Assert\Choice(['Id', 'DateAdded', 'Type', 'Year', 'Model', 'Make', 'Vin']), new Assert\NotBlank ])],
			'Order2' => [new Assert\Optional([ new Assert\Choice(['asc', 'desc']) ])],
			'Sort3' => [new Assert\Optional([ new Assert\Choice(['Id', 'DateAdded', 'Type', 'Year', 'Model', 'Make', 'Vin']), new Assert\NotBlank ])],
			'Order3' => [new Assert\Optional([ new Assert\Choice(['asc', 'desc']), new Assert\NotBlank ])],
			'Page' => [new Assert\Optional([ new Assert\Regex(['pattern' => '/^[0-9]+$/']), new Assert\NotBlank ])],
			'Limit' => [new Assert\Optional([ new Assert\Regex(['pattern' => '/^[0-9]+$/']), new Assert\NotBlank ])],
		]);

		$violations = $validator->validate($request->query->all(), $constraints);
		
		if (count($violations) > 0) {		
			$result['status'] = '500';   
			$result['message'] = $violations[0]->getPropertyPath().': '.$violations[0]->getMessage();
			return new JsonResponse($result, 500);
		}
		
		$result = $doctrine->getRepository(Vehicle::class)->getCarList($request);
		
		$json = $serializer->serialize($result, 'json');
		return new JsonResponse($json, 200, [], true);
    }
	
	public function getCar($Id, Request $request, ValidatorInterface $validator, ManagerRegistry $doctrine, SerializerInterface $serializer) {
		$constraints = new Assert\Collection([
			'Id' => [new Assert\Regex(['pattern' => '/^[0-9]+$/']), new Assert\NotBlank ]
		]);
		
		$input['Id'] = $Id;
		
		$violations = $validator->validate($input, $constraints);
		
		if (count($violations) > 0) {		
			$result['status'] = '500';   
			$result['message'] = $violations[0]->getPropertyPath().': '.$violations[0]->getMessage();
			return new JsonResponse($result, 500);
		}
		
		$data['Id'] = $Id;
		$data['Deleted'] = 0;
		$carType = $_ENV['CAR_TYPE'];
		if ($carType)
			$data['Type'] = $carType;
		
		$result = $doctrine->getRepository(Vehicle::class)->findOneBy($data);
		
		if (!$result)
			$json = '{}';
		else
			$json = $serializer->serialize($result, 'json');
		return new JsonResponse($json, 200, [], true);
	}
	
	public function addCar(Request $request, ValidatorInterface $validator, ManagerRegistry $doctrine, SerializerInterface $serializer) {
		$constraints = new Assert\Collection([
			'Type' => [new Assert\Choice(['used', 'new']), new Assert\NotBlank],
			'Year' => [new Assert\Length(['min' => 4, 'max' => 4]), new Assert\NotBlank],
			'Model' => [new Assert\NotBlank],
			'Make' => [new Assert\NotBlank],
			'Vin' => [new Assert\NotBlank],
			'Msrp' => [new Assert\Optional([ new Assert\Regex(['pattern' => '/^\d*\.?\d*$/']), new Assert\NotBlank ])],
			'Miles' => [new Assert\Optional([ new Assert\Regex(['pattern' => '/^[0-9]+$/']), new Assert\NotBlank ])],
		]);

		$violations = $validator->validate($request->query->all(), $constraints);
		
		if (count($violations) > 0) {		
			$result['status'] = '500';   
			$result['message'] = $violations[0]->getPropertyPath().': '.$violations[0]->getMessage();
			return new JsonResponse($result, 500);
		}
		
		$car = new Vehicle();
		$car->setType($request->get('Type'));
		$car->setYear($request->get('Year'));
		$car->setModel($request->get('Model'));
		$car->setMake($request->get('Make'));
		$car->setVin($request->get('Vin'));
		$msrp = $request->get('Msrp') ? $request->get('Msrp') : 0;
		$car->setMsrp($msrp);
		$miles = $request->get('Miles') ? $request->get('Miles') : 0;
		$car->setMiles($miles);
		$car->setDateAdded(\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')));
		$car->setDeleted(0);
		
		$entityManager = $doctrine->getManager();
		$entityManager->persist($car);
		$entityManager->flush();
		
		$result['Id'] = $car->getId();
		return new JsonResponse($result, 200);
	}
	
	public function editCar($Id, Request $request, ValidatorInterface $validator, ManagerRegistry $doctrine, SerializerInterface $serializer) {
		$constraints = new Assert\Collection([
			'Id' => [new Assert\Regex(['pattern' => '/^[0-9]+$/']), new Assert\NotBlank],
			'Type' => [new Assert\Optional([ new Assert\Choice(['used', 'new']), new Assert\NotBlank ])],
			'Year' => [new Assert\Optional([ new Assert\Length(['min' => 4, 'max' => 4]), new Assert\NotBlank ])],
			'Model' => [new Assert\Optional([ new Assert\NotBlank ])],
			'Make' => [new Assert\Optional([ new Assert\NotBlank ])],
			'Vin' => [new Assert\Optional([ new Assert\NotBlank ])],
			'Msrp' => [new Assert\Optional([ new Assert\Regex(['pattern' => '/^\d*\.?\d*$/']), new Assert\NotBlank ])],
			'Miles' => [new Assert\Optional([ new Assert\Regex(['pattern' => '/^[0-9]+$/']), new Assert\NotBlank ])],
		]);
		
		$input = $request->query->all();
		
		if (!count($input)) {
			$result['status'] = '500';   
			$result['message'] = 'Please enter a value to update';
			return new JsonResponse($result, 500);
		}
		
		$input['Id'] = $Id;
		
		$violations = $validator->validate($input, $constraints);
		
		if (count($violations) > 0) {		
			$result['status'] = '500';   
			$result['message'] = $violations[0]->getPropertyPath().': '.$violations[0]->getMessage();
			return new JsonResponse($result, 500);
		}
		
		$car = $doctrine->getRepository(Vehicle::class)->find($Id);
        if (!$car) {
            $result['status'] = '500';
			$result['message'] = "No car found with ID: $Id.";
			return new JsonResponse($result, 500);
        }
		
		if ($request->get('Type'))
			$car->setType($request->get('Type'));
		if ($request->get('Year'))
			$car->setYear($request->get('Year'));
		if ($request->get('Model'))
			$car->setModel($request->get('Model'));
		if ($request->get('Make'))
			$car->setMake($request->get('Make'));
		if ($request->get('Vin'))
			$car->setVin($request->get('Vin'));
		if ($request->get('Msrp'))
			$car->setMsrp($request->get('Msrp'));
		if ($request->get('Miles'))
			$car->setMiles($request->get('Miles'));
		
		$entityManager = $doctrine->getManager();
        $entityManager->flush();
		
		$json = $serializer->serialize($car, 'json');
		return new JsonResponse($json, 200, [], true);
	}
	
	public function delCar($Id, Request $request, ValidatorInterface $validator, ManagerRegistry $doctrine, SerializerInterface $serializer) {
		$constraints = new Assert\Collection([
			'Id' => [new Assert\Regex(['pattern' => '/^[0-9]+$/']), new Assert\NotBlank ]
		]);
		
		$input['Id'] = $Id;
		
		$violations = $validator->validate($input, $constraints);
		
		if (count($violations) > 0) {		
			$result['status'] = '500';   
			$result['message'] = $violations[0]->getPropertyPath().': '.$violations[0]->getMessage();
			return new JsonResponse($result, 500);
		}
		
		$car = $doctrine->getRepository(Vehicle::class)->find($Id);
        if (!$car) {
            $result['status'] = '500';
			$result['message'] = "No car found with ID: $Id.";
			return new JsonResponse($result, 500);
        }
		
		$car->setDeleted(1);
		$entityManager = $doctrine->getManager();
        $entityManager->flush();

		$result['status'] = 'success';

		$json = $serializer->serialize($result, 'json');
		return new JsonResponse($json, 200, [], true);
	}
}
