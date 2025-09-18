<?php

namespace App\Entity;

use App\Repository\LanguageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LanguageRepository::class)]
class Language
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10, unique: true)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $isDefault = false;

    #[ORM\Column]
    private ?bool $isActive = true;

    #[ORM\OneToMany(mappedBy: 'language', targetEntity: ServiceTranslation::class, orphanRemoval: true)]
    private Collection $serviceTranslations;

    public function __construct()
    {
        $this->serviceTranslations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isDefault(): ?bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault): static
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection<int, ServiceTranslation>
     */
    public function getServiceTranslations(): Collection
    {
        return $this->serviceTranslations;
    }

    public function addServiceTranslation(ServiceTranslation $serviceTranslation): static
    {
        if (!$this->serviceTranslations->contains($serviceTranslation)) {
            $this->serviceTranslations->add($serviceTranslation);
            $serviceTranslation->setLanguage($this);
        }

        return $this;
    }

    public function removeServiceTranslation(ServiceTranslation $serviceTranslation): static
    {
        if ($this->serviceTranslations->removeElement($serviceTranslation)) {
            // set the owning side to null (unless already changed)
            if ($serviceTranslation->getLanguage() === $this) {
                $serviceTranslation->setLanguage(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }
}
