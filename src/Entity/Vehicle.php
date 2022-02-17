<?php

namespace App\Entity;

use App\Repository\VehicleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VehicleRepository::class)
 * @ORM\Table(name="Vehicle")
 */
class Vehicle
{
    /**
	 * @ORM\Id
	 * @ORM\Column(type="integer", name="Id")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $Id;

    /**
     * @ORM\Column(type="datetime", name="DateAdded")
     */
    private $DateAdded;

    /**
     * @ORM\Column(type="string", length=4, name="Type")
     */
    private $Type;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=2, nullable=true, name="Msrp")
     */
    private $Msrp;

    /**
     * @ORM\Column(type="integer", name="Year")
     */
    private $Year;

    /**
     * @ORM\Column(type="string", length=50, name="Make")
     */
    private $Make;

    /**
     * @ORM\Column(type="string", length=50, name="Model")
     */
    private $Model;

    /**
     * @ORM\Column(type="integer", nullable=true, name="Miles")
     */
    private $Miles;

    /**
     * @ORM\Column(type="string", length=50, nullable=true, name="Vin")
     */
    private $Vin;

    /**
     * @ORM\Column(type="boolean", name="Deleted")
     */
    private $Deleted;

    public function getId(): ?int
    {
        return $this->Id;
    }

    public function setId(int $Id): self
    {
        $this->Id = $Id;

        return $this;
    }

    public function getDateAdded(): ?\DateTimeInterface
    {
        return $this->DateAdded;
    }

    public function setDateAdded(\DateTimeInterface $DateAdded): self
    {
        $this->DateAdded = $DateAdded;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function getMsrp(): ?string
    {
        return $this->Msrp;
    }

    public function setMsrp(?string $Msrp): self
    {
        $this->Msrp = $Msrp;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->Year;
    }

    public function setYear(int $Year): self
    {
        $this->Year = $Year;

        return $this;
    }

    public function getMake(): ?string
    {
        return $this->Make;
    }

    public function setMake(string $Make): self
    {
        $this->Make = $Make;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->Model;
    }

    public function setModel(string $Model): self
    {
        $this->Model = $Model;

        return $this;
    }

    public function getMiles(): ?int
    {
        return $this->Miles;
    }

    public function setMiles(?int $Miles): self
    {
        $this->Miles = $Miles;

        return $this;
    }

    public function getVin(): ?string
    {
        return $this->Vin;
    }

    public function setVin(?string $Vin): self
    {
        $this->Vin = $Vin;

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->Deleted;
    }

    public function setDeleted(bool $Deleted): self
    {
        $this->Deleted = $Deleted;

        return $this;
    }
}
