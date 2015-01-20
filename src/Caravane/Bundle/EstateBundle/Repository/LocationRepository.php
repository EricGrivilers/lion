<?php

namespace Caravane\Bundle\EstateBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Doctrine\Common\Collections\ArrayCollection;




class LocationRepository extends EntityRepository {

	public function findOneByFr($name) {
		echo $name;

     	$query = $this->createQueryBuilder('L')
        ->where('upper(L.fr) = upper(:name)')
        ->setParameter('name', $name)
        ->setMaxResults(1);

        $location = $query->getQuery()->getOneOrNullResult();

		return $location;
	}
}

