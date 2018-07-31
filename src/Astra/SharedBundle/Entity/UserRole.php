<?php

namespace Astra\SharedBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * UserRole
 *
 * @ORM\Table(name="user_role")
 * @ORM\Entity(repositoryClass="Astra\SharedBundle\Repository\UserRoleRepository")
 * @UniqueEntity("name")
 */
class UserRole
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="isRoot", type="boolean")
     */
    private $isRoot = false;

    /**
     * @Doctrine\ORM\Mapping\Column(type="array", nullable=true)
     */
    private $access = [];

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return UserRole
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set isRoot
     *
     * @param boolean $isRoot
     *
     * @return UserRole
     */
    public function setIsRoot($isRoot)
    {
        $this->isRoot = $isRoot;

        return $this;
    }

    /**
     * Get isRoot
     *
     * @return bool
     */
    public function getIsRoot()
    {
        return $this->isRoot;
    }

    /**
     * @param string $zone
     * @param string $action
     * @return self
     */
    public function openAccess ($zone, $action) {
        if (!empty($this->access[$zone][$action])) return $this;
        $this->access[$zone][$action] = true;
        return $this;
    }

    /**
     * @param string $zone
     * @param string $action
     * @return self
     */
    public function closeAccess ($zone, $action) {
        if (empty($this->access[$zone][$action])) return $this;
        unset($this->access[$zone][$action]);
        return $this;
    }

    /**
     * @param string $zone
     * @param string $action
     * @return boolean
     */
    public function checkAccess ($zone, $action) {
        return !empty($this->access[$zone][$action]);
    }

    public function resetAccess() {
        $this->access = [];
    }
}

