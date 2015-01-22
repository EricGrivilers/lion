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


	function getAreas() {
		return $this->getAreasQuery()->getQuery()->getResult();
	}

	function getAreasQuery() {
		$query = $this->createQueryBuilder('A');

		$query->leftJoin("A.estate","E")
				->orderBy("A.nomQuartier","ASC")
                ->groupBy("A.id")
                ->having("count(E.id) > 0");


		/*$query->innerJoin('A.estate', 'e')
		->having(
	        $query->expr()->gt(
	            $query->expr()->count('e'), 1
	        )
	    );*/

	    return $query;
	}

}
