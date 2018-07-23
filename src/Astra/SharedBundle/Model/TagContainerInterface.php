<?php
namespace Astra\SharedBundle\Model;
use Astra\SharedBundle\Entity\Tag;

interface TagContainerInterface
{
    public function setTag(Tag $tag);

    /**
     * @return Tag
     */
    public function getTag();
}