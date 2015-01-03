<?php

namespace Caravane\Bundle\EstateBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Doctrine\Common\Collections\ArrayCollection;




class AreaRepository extends EntityRepository {

	function getClosestArea($lat,$lng) {
		$query = $this->createQueryBuilder('C');
		$query->select('C, GEO(C.lat = :latitude, C.lng = :longitude) as distance');
		//$query->andWhere('GEO(C.lat = :latitude, C.lng = :longitude)<=:distance')
		$query->setParameter('latitude', $lat)
		->setParameter('longitude', $lng);
								//->setParameter('distance', 100);
		$query->orderBy('distance', 'ASC');
		$query->setFirstResult(0);
		$query->setMaxResults(1);

		$area = $query->getQuery()->getSingleResult();

		return $area[0];
	}

}
