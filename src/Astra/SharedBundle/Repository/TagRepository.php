<?php

namespace Astra\SharedBundle\Repository;

use Astra\SharedBundle\Entity\Tag;
use Astra\SharedBundle\Utils\Values;

/**
 * TagRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TagRepository extends \Doctrine\ORM\EntityRepository
{
    public function generateByWord($word)
    {
        $word = Values::getString($word);
        if(empty($word))throw new \Exception('Empty tag value');
        $tag = $this->findOneBy(['name'=>$word]);
        if($tag) return $tag;
        $tag = new Tag();
        $tag->setName($word);
        $this->_em->persist($tag);
        return $tag;
    }
}
