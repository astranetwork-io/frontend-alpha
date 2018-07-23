<?php

namespace Astra\SharedBundle\Repository;

use Astra\SharedBundle\Entity\Project;
use Astra\SharedBundle\Entity\ProjectTag;
use Astra\SharedBundle\Entity\Tag;

/**
 * ProjectTagRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProjectTagRepository extends \Doctrine\ORM\EntityRepository
{
    public function generateByProjectAndTag(Project $project, Tag $tag)
    {
        $projectTag = $this->findOneBy(['project'=>$project,'tag'=>$tag]);
        if($projectTag) return $projectTag;
        $projectTag = new ProjectTag();
        $projectTag->setProject($project);
        $projectTag->setTag($tag);

        $this->_em->persist($projectTag);

        return $projectTag;
    }
}
