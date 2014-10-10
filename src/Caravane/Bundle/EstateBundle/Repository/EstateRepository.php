<?php

namespace Caravane\Bundle\EstateBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
/**
 * EstateRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EstateRepository extends EntityRepository
{

	public function findLastUpdated($limit=3) {
		return $this->findBy(
			array(),
			array('updatedOn'=>'desc'),
			$limit
		);
	}


	public function getSearchResult($postDatas=array(), $options=array()) {
		/*if(!$postDatas=$request->query->get('c_s')) {
            $postDatas=array();
		}*/
        $datas=array_merge_recursive($postDatas, $options);


		 $type=($datas['location']==1?'rent':'sale');

         if(isset($datas['reference'])) {
            if($estates=$this->findOneBy(array('reference'=>"030/".$datas['reference']))) {
                return $estates;
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

        //$dql = "SELECT C FROM CaravaneEstateBundle:Estate C ";
        //$dql.=" WHERE 1=1  ";
        //$dql.=" AND C.status='1' ";
        //$dql.=" AND C.location='".$datas['location']."' ";

        if(isset($datas['category'])) {
        	$category=implode(",",$datas['category']);
        	//$dql.=" AND C.category IN(".$category.") ";
            $query->andWhere('C.category IN (:category)')
            ->setParameter('category', $category);
        }
		if(isset($datas['zone'])) {       
        	$zone=implode(",",$datas['zone']);
        	//$dql.=" AND C.zone IN(".$zone.") ";
            $query->andWhere('C.zone IN (:zone)')
            ->setParameter('zone', $zone);
        }
        if(isset($datas['area'])) {
            //$dql.=" AND C.area=".$datas['area']." ";
            $query->andWhere('C.area =:area')
            ->setParameter('area', $datas['area']);
        }
        if(isset($datas['keyword'])) {
            //$dql.=" AND C.description LIKE \"%".$datas['keyword']."%\" ";
            $query->andWhere('C.description LIKE :keyword')
            ->setParameter('keyword', "%".$datas['keyword']."%");
        }
        if(isset($datas['prix'])) {
            $prices=$this->getEntityManager()->getRepository('CaravaneEstateBundle:Price')->findBy(array('type'=>$type), array("price"=>"asc"));
            foreach($prices as $price) {
                $pA[$type."_".$price->getId()]=$price->getPrice();
            }
    //        var_dump($pA);

            
            $dqlA=array();
            foreach($datas['prix'] as $priceCode) {
                $tA=explode('_',$priceCode);
                $id=$tA[0]."_".$tA[1];
                if($tA[2]=="-") {
                   // $dqlA[]=" C.prix <= ".$pA[$id];
                    $dqlA[]=" C.prix <= :p".$tA[1];
                    $query->setParameter("p".$tA[1], $pA[$id]);
                }
                else if($tA[2]=="+") {
                 //   $dqlA[]=" C.prix >= ".$pA[$id];
                    $dqlA[]=" C.prix >= :p".$tA[1];
                    $query->setParameter("p".$tA[1], $pA[$id]);
                }
                else {
                    //$dqlA[]=" ( C.prix >=".$pA[$id]." AND C.prix <= ".$pA[$type."_".$tA[2]].") ";
                    $dqlA[]=" ( C.prix >= :p".$tA[1]." AND C.prix <= :p".$tA[2].") ";
                    $query->setParameter("p".$tA[1], $pA[$id]);
                    $query->setParameter("p".$tA[2], $pA[$type."_".$tA[2]]);
                }
                //$prix=$pA[$id];
                //echo $prix;
                
            }
             $query->andWhere(implode(" OR ", $dqlA));


        //    $dql.=" AND (".implode(" OR ", $dqlA).") ";
        }

        $query->setFirstResult($datas['offset']);
        $query->setMaxResults($datas['limit']);
      // echo $dql;
//echo $datas['offset'];

        //$query = $this->getEntityManager()->createQuery($dql);
        //$entities = $query->getResult();

        //$entities = $query->getQuery()->getResult();
        $entities = new Paginator($query, $fetchJoinCollection = true);

        
        return $entities;

	}
}
