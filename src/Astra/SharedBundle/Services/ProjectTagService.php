<?php
namespace Astra\SharedBundle\Services;
use Astra\SharedBundle\Entity\Project;
use Astra\SharedBundle\Entity\Tag;
use Astra\SharedBundle\Entity\User;
use Astra\SharedBundle\Entity\Wallet;
use Astra\SharedBundle\Entity\WalletTransaction;
use Astra\SharedBundle\Entity\WalletTransactionItem;
use Astra\SharedBundle\Utils\Values;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProjectTagService
{
    private $entityManager;
    private $tagService;

    public function __construct(EntityManager $entityManager, TagService $tagService)
    {
        $this->entityManager = $entityManager;
        $this->tagService = $tagService;
    }

    public function generateProjectTagByWord(Project $project, $word)
    {
        $tag = $this->tagService->getTagByWord($word);
        if (empty($tag)) return false;
        return $this->entityManager->getRepository('AstraSharedBundle:ProjectTag')->generateByProjectAndTag($project,$tag);
    }


}