<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

class AuthController extends AbstractController
{
    public function __construct(RequestStack $requestStack, ManagerRegistry $doctrine)
    	{
		$request = $requestStack->getCurrentRequest();
        	$username = $request->headers->get('UserName') ? $request->headers->get('UserName') : '';
		$token = $request->headers->get('Token') ? $request->headers->get('Token') : '';
		
		$unit_test = false;
		if (!$username && $request->get('TestUserName') == $_ENV['TEST_USERNAME'] && $request->get('TestToken') == $_ENV['TEST_TOKEN']) {
			$unit_test = true;
			$request->query->remove('TestUserName');
			$request->query->remove('TestToken');
		}
		
		if (!$unit_test) {
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
   	}
}
