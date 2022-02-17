<?php

namespace App\Repository;

use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Vehicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicle[]    findAll()
 * @method Vehicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehicleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }
	
	public function getCarList($request) {
		$query = $this->createQueryBuilder('v')
				->where('v.Deleted = 0');
		
		if ($request->get('Id'))  
			$query->andWhere('v.Id = :Id')->setParameter('Id', $request->get('Id'));
				
		if ($request->get('DateAdded')) {
			$query->andWhere('v.DateAdded >= :DateAddedStart')
					->andWhere('v.DateAdded <= :DateAddedEnd')
					->setParameter('DateAddedStart', date('Y-m-d 00:00:00', strtotime($request->get('DateAdded'))))
					->setParameter('DateAddedEnd', date('Y-m-d 23:59:59', strtotime($request->get('DateAdded'))));
		}
		
		if ($request->get('Type'))  
			$query->andWhere('v.Type = :Type')->setParameter('Type', $request->get('Type'));
		
		if ($request->get('Year'))  
			$query->andWhere('v.Year = :Year')->setParameter('Year', $request->get('Year'));
		
		if ($request->get('Model'))  
			$query->andWhere('v.Model = :Model')->setParameter('Model', $request->get('Model'));
		
		if ($request->get('Make'))  
			$query->andWhere('v.Make = :Make')->setParameter('Make', $request->get('Make'));
		
		if ($request->get('Vin'))  
			$query->andWhere('v.Vin = :Vin')->setParameter('Vin', $request->get('Vin'));
		
		if ($request->get('MinYear'))  
			$query->andWhere('v.Year >= :MinYear')->setParameter('MinYear', $request->get('MinYear'));
		
		if ($request->get('MaxYear'))  
			$query->andWhere('v.Year <= :MaxYear')->setParameter('MaxYear', $request->get('MaxYear'));
		
		if ($request->get('MinMsrp'))  
			$query->andWhere('v.Msrp >= :MinMsrp')->setParameter('MinMsrp', $request->get('MinMsrp'));
		
		if ($request->get('MaxMsrp'))  
			$query->andWhere('v.Msrp <= :MaxMsrp')->setParameter('MaxMsrp', $request->get('MaxMsrp'));
		
		if ($request->get('MinMiles'))  
			$query->andWhere('v.Miles >= :MinMiles')->setParameter('MinMiles', $request->get('MinMiles'));
		
		if ($request->get('MaxMiles'))  
			$query->andWhere('v.Miles <= :MaxMiles')->setParameter('MaxMiles', $request->get('MaxMiles'));
		
		$carType = $_ENV['CAR_TYPE'];
		if ($carType)
			$query->andWhere('v.Type = :Type2')->setParameter('Type2', $carType);
		
		if ($request->get('Sort')) {
			$order = $request->get('Order') ? $request->get('Order') : 'asc';
			$query->addOrderBy('v.'.$request->get('Sort'), $order);
		}
		
		if ($request->get('Sort2')) {
			$order2 = $request->get('Order2') ? $request->get('Order2') : 'asc';
			$query->addOrderBy('v.'.$request->get('Sort2'), $order2);
		}
		
		if ($request->get('Sort3')) {
			$order3 = $request->get('Order3') ? $request->get('Order3') : 'asc';
			$query->addOrderBy('v.'.$request->get('Sort3'), $order3);
		}
		
		if ($request->get('Limit')) {
			$query->setMaxResults($request->get('Limit'));
			if ($request->get('Page')) {
				$offset = $request->get('Page') * $request->get('Limit');
				$query->setFirstResult($offset);
			}
		}
				
		$result = $query->getQuery()->getResult();
		
		return $result;
	}

    // /**
    //  * @return Vehicle[] Returns an array of Vehicle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Vehicle
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
