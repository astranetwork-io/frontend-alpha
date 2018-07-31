<?php

namespace Astra\SharedBundle\Twig;
use Astra\SharedBundle\Entity\File;
use Astra\SharedBundle\Entity\User;
use Astra\SharedBundle\Entity\UserRole;
use Astra\SharedBundle\Services\Access;
use Liip\ImagineBundle\Templating\ImagineExtension;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class Extension extends \Twig_Extension
{
    private $assetExtension;
    private $publicFileDirectory;
    private $privateFileDirectory;
    private $imagineExtension;
    private $tokenStorage;
    private $accessService;
    public function __construct
    (
        ImagineExtension $imagineExtension,
        AssetExtension $assetExtension,
        $publicFileDirectory,
        $privateFileDirectory,
        Access $accessService,
        TokenStorageInterface $tokenStorage = null
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->assetExtension = $assetExtension;
        $this->publicFileDirectory = $publicFileDirectory;
        $this->privateFileDirectory = $privateFileDirectory;
        $this->imagineExtension = $imagineExtension;
        $this->accessService = $accessService;
    }

    public function getName()
    {
        return 'astra';
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('publicImg', [$this, 'getPublicImageSrc']),
            new \Twig_SimpleFunction('publicFile', [$this, 'getPublicFileSrc']),
            new \Twig_SimpleFunction('UserPhoto', [$this, 'getUserPhotoSrc']),
            new \Twig_SimpleFunction('CurrentUser', [$this, 'getCurrentUser']),
            new \Twig_SimpleFunction('print_r', [$this, 'print_r']),
            new \Twig_SimpleFunction('accessRole', [$this, 'accessRole']),
            new \Twig_SimpleFunction('access', [$this, 'access']),
        ];
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('date_format', [$this, 'formatDate']),
            new \Twig_SimpleFilter('date_time_format', [$this, 'formatDateTime']),
            new \Twig_SimpleFunction('print_r', [$this, 'print_r']),
            ];
    }

    public function getPublicFileSrc($baseUrl)
    {
        $baseUrl = trim($baseUrl,' /');
        $url = $this->assetExtension->getAssetUrl($this->publicFileDirectory.'/'.$baseUrl);
        return $url;
    }

    public function getPublicImageSrc($baseUrl,$thumbnailFilter = null)
    {
        $url = $this->getPublicFileSrc($baseUrl);
        if (empty($thumbnailFilter)) return $url;
        return $this->imagineExtension->filter($url,$thumbnailFilter);
    }

    public function getUserPhotoSrc(User $user = null, $thumbnailFilter = null)
    {
        $src = 'shared/noavatar.jpg';
        if (($user) && ($user->getPhoto()) && ($user->getPhoto()->getType() == File::TYPE_IMAGE) && (!empty($user->getPhoto()->getAsset()))){
            $src = $user->getPhoto()->getAsset();
        }
        return $this->getPublicImageSrc($src,$thumbnailFilter);
    }

    public function getCurrentUser()
    {
        if(!$this->tokenStorage)return null;
        if(!$this->tokenStorage->getToken())return null;
        return $this->tokenStorage->getToken()->getUser();
    }

    public function formatDate(\DateTime $date = null, $format = null)
    {
        if(empty($format))$format = 'd.m.Y';
        if(!$date)return '';
        return $date->format($format);
    }

    public function formatDateTime(\DateTime $date = null, $format = null)
    {
        if(empty($format))$format = 'd.m.Y H:i';
        if(!$date)return '';
        return $date->format($format);
    }

    public function print_r($data)
    {
        return "<pre>".print_r($data,true)."</pre>";
    }

    public function access($zone, $action, User $user = null)
    {
        return $this->accessService->checkAccess($zone, $action, $user ? $user->getRoles() : null);
    }

    public function accessRole($zone, $action, UserRole $role = null)
    {
        return $this->accessService->checkAccess($zone, $action, $role ? [$role] : null);
    }
}
