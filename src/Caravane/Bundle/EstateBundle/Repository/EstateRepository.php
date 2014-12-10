<?php

namespace Caravane\Bundle\EstateBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Doctrine\Common\Collections\ArrayCollection;




/**
 * EstateRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EstateRepository extends EntityRepository
{

	public function findLastUpdated($limit=3, $user) {
		$lastSearchResults=array();
		$estates= $this->findBy(
			array(),
			array('updatedOn'=>'desc'),
			$limit
		);

		if($user) {
			if($user->getContact()) {
				$lastSearch=json_decode($user->getContact()->getLastSearch(), true);
				$lastSearch['sort']="updatedOn desc";
				$lastSearch['limit']=12;
				$lastSearch['offset']=0;
				$lastSearchResults=$this->getSearchResult($lastSearch);
				if(!is_array($lastSearchResults)) {
					$lastSearchResults=array();
				}
			}
			$collection = new ArrayCollection(
				array_merge($lastSearchResults,$estates)
			);
			$estates=$collection;
		}
		return $estates;
	}


	public function getSearchResult($postDatas=array(), $options=array()) {

		$datas=array_merge_recursive($postDatas, $options);


		$type=($datas['location']==1?'rent':'sale');
		if($datas['location']!=1) {
			$datas['location']=0;
		}
		$type=($datas['location']==1?'rent':'sale');


		if(isset($datas['reference'])) {
			if($datas['reference']!='') {
				if($estates=$this->findOneBy(array('reference'=>"030/".$datas['reference']))) {
					return $estates;
				}
			}
		}

		if(!isset($datas['offset'])) {
			$datas['offset']=0;
		}
		if(!isset($datas['limit'])) {
			$datas['limit']=24;
		}

		$query=$this->getEntityManager()->getRepository("CaravaneEstateBundle:Estate")->createQueryBuilder('C')
			->where('C.status = :status')
			->andWhere('C.location = :type')
			->setParameter('status', 1)
			->setParameter('type', $datas['location']);

		if(isset($datas['category'])) {
			$category=implode(",",$datas['category']);
			$query->andWhere('C.category IN (:category)')
					->setParameter('category', $category);
		}
		if(isset($datas['zone'])) {
			$zone=implode(",",$datas['zone']);
			$query->andWhere('C.zone IN (:zone)')
					->setParameter('zone', $zone);
		}
		
		if(isset($datas['latlng'])) {
			$latlng=explode(",", $datas['latlng']);
			if(!isset($datas['rayon']) || $datas['rayon']<=0) {
				$datas['rayon']=1;
			}
			$query->andWhere('GEO(C.lat = :latitude, C.lng = :longitude)<=:distance')
				//->having('distance < :distance')
                ->setParameter('latitude', $latlng[0])
                ->setParameter('longitude', $latlng[1])
                ->setParameter('distance', $datas['rayon']);
		}
		else if(isset($datas['area'])) {
			$query->andWhere('C.area =:area')
				->setParameter('area', $datas['area']);
		}

		if(isset($datas['keyword'])) {
			if($datas['keyword']!="") {
				$query->andWhere('C.description LIKE :keyword')
					->setParameter('keyword', "%".$datas['keyword']."%");
			}
		}
		if(isset($datas['prix'])) {
			$prices=$this->getEntityManager()->getRepository('CaravaneEstateBundle:Price')->findBy(array('type'=>$type), array("price"=>"asc"));
			foreach($prices as $price) {
				$pA[$type."_".$price->getId()]=$price->getPrice();
			}

			$dqlA=array();
			
			foreach($datas['prix'] as $priceCode) {
				$tA=explode('_',$priceCode);
				if(isset($tA[1])) {
					$id=$tA[0]."_".$tA[1];
					if($tA[2]=="-") {
						$dqlA[]=" C.prix <= :p".$tA[1];
						$query->setParameter("p".$tA[1], $pA[$id]);
					}
					else if($tA[2]=="+") {
						$dqlA[]=" C.prix >= :p".$tA[1];
						$query->setParameter("p".$tA[1], $pA[$id]);
					}
					else {
						$dqlA[]=" ( C.prix >= :p".$tA[1]." AND C.prix <= :p".$tA[2].") ";
						$query->setParameter("p".$tA[1], $pA[$id]);
						$query->setParameter("p".$tA[2], $pA[$type."_".$tA[2]]);
					}
				}
				
			}
			if(count($dqlA)>0) {
				$query->andWhere(implode(" OR ", $dqlA));
			}
			
		}
		if(isset($datas['isNewBuilding'])) {
			if($datas['isNewBuilding']==true) {
				$query->andWhere("C.isNewBuilding=1");
			}
		}
		$query->setFirstResult($datas['offset']);
		$query->setMaxResults($datas['limit']);
		if(!isset($datas['sort'])) {
			$datas['sort']="updatedOn desc";
		}
		$sort=explode(" ",$datas['sort']);
		$query->orderBy("C.".$sort[0],$sort[1]);

		$entities = $query->getQuery()->getResult();

		return $entities;
	}



	public function findByAreaGrouped($type) {
		$location=0;
		if($type=='rent') {
			$location=1;
		}
		if($type=="new") {
			$location=0;
		}
		$query=$this->getEntityManager()->getRepository("CaravaneEstateBundle:Estate")->createQueryBuilder('C')
			->select('COUNT(C), C.id, area.id, area.latlng')
			->leftJoin('C.area', 'area')
			->where('C.status = 1');
			if($type=="new") {
				$query->andWhere('C.isNewBuilding = 1');
			}
			$query->andWhere('C.location = :location')
			->setParameter('location',$location)
			->groupBy('C.area');
		$entities = $query->getQuery()->getResult();

		return $entities;

	}
}
