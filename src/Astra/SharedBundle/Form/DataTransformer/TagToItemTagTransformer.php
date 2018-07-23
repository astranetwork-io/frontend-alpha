<?php
namespace Astra\SharedBundle\Form\DataTransformer;

use Astra\SharedBundle\Entity\Tag;
use Astra\SharedBundle\Model\NewTag;
use Astra\SharedBundle\Services\TagService;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;

class TagToItemTagTransformer implements DataTransformerInterface
{

    private $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    public function transform($value)
    {
        $result = [];
        foreach ($value as $key=>$item)
        {
            if($item instanceof NewTag)
            {
                $result[] = $item;
            }
            else
            {
                $newTag = new NewTag();
                $newTag->setName($item);
                $result[] = $newTag;
            }
        }
        return $result;
    }

    public function reverseTransform($value)
    {print_r($value); die();

        if (!is_array($value)) return [];
        $result = [];
        foreach ($value as $id=>$text)
        {
            //$tag = $this->tagService->getTagByWord($text);
            //if(!$tag)continue;
            //$result[] = $tag;
        }
       return $result;
    }
}