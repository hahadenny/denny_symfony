<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="User")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="Id")
     */
    private $Id;

    /**
     * @ORM\Column(type="string", length=50, name="UserName")
     */
    private $UserName;

    /**
     * @ORM\Column(type="string", length=255, name="Token")
     */
    private $Token;

    public function getId(): ?int
    {
        return $this->Id;
    }

    public function getUserName(): ?string
    {
        return $this->UserName;
    }

    public function setUserName(string $UserName): self
    {
        $this->UserName = $UserName;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->Token;
    }

    public function setToken(string $Token): self
    {
        $this->Token = $Token;

        return $this;
    }
	
    public function getUserIdentifier(): ?string {
	return '';
    }
	
    public function getRoles(): ?array
    {
	$roles[] = 'ROLE_USER';
        return array_unique($roles);
    }
	
    public function getPassword(): ?string
    {
        return '';
    }
	
    public function getSalt(): ?string
    {
        return '';
    }
	
    public function eraseCredentials()
    {
        return true;
    }
}
