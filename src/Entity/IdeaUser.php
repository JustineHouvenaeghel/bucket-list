<?php

namespace App\Entity;

use App\Repository\IdeaUserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IdeaUserRepository::class)
 */
class IdeaUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Orm\ManyToOne(targetEntity="App\Entity\User", inversedBy="listOfIdeas")
     */
    private $user;

    /**
     * @Orm\ManyToOne(targetEntity="App\Entity\Idea", inversedBy="ideaUsers")
     */
    private $idea;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAdded;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $isCompleted;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getIdea()
    {
        return $this->idea;
    }

    /**
     * @param mixed $idea
     */
    public function setIdea($idea): void
    {
        $this->idea = $idea;
    }

    /**
     * @return mixed
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * @param mixed $dateAdded
     */
    public function setDateAdded($dateAdded): void
    {
        $this->dateAdded = $dateAdded;
    }

    /**
     * @return mixed
     */
    public function getIsCompleted()
    {
        return $this->isCompleted;
    }

    /**
     * @param mixed $isCompleted
     */
    public function setIsCompleted($isCompleted): void
    {
        $this->isCompleted = $isCompleted;
    }

}
