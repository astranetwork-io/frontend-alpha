<?php
namespace Astra\SharedBundle\Services;
use Astra\SharedBundle\Entity\Tag;
use Astra\SharedBundle\Utils\Values;
use Doctrine\ORM\EntityManager;

class TagService
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param $word
     * @return Tag|null
     */
    public function getTagByWord($word)
    {
        $word = Values::getString($word);
        if (empty($word)) return null;
        return $this->entityManager->getRepository('AstraSharedBundle:Tag')->generateByWord($word);
    }
}