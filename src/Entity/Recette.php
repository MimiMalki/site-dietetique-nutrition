<?php

namespace App\Entity;

use App\Repository\RecetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: RecetteRepository::class)]
#[Vich\Uploadable]

class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[Vich\UploadableField(mapping: 'recette_images', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $tempsPrepation = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $tempsRepos = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $tempsCuisson = null;

    #[ORM\Column(length: 255)]
    private ?string $ingredients = null;

    #[ORM\Column(length: 1000)]
    private ?string $etapes = null;

    // #[ORM\Column(length: 255)]
    // private ?string $allergenes = null;

    // #[ORM\Column(length: 255)]
    // private ?string $typeRegime = null;

    #[ORM\ManyToMany(targetEntity: Allergie::class, mappedBy: 'recette')]
    private Collection $allergies;

    #[ORM\ManyToMany(targetEntity: Regime::class, mappedBy: 'recette')]
    private Collection $regimes;

    #[ORM\Column]
    private ?bool $accessiblePatient = null;

    #[ORM\OneToMany(mappedBy: 'recette', targetEntity: Avis::class)]
    private Collection $avis;

    public function __construct()
    {
        $this->allergies = new ArrayCollection();
        $this->regimes = new ArrayCollection();
        $this->avis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): self
    {
        $this->imageFile = $imageFile;
        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;
        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTempsPrepation(): ?\DateTimeInterface
    {
        return $this->tempsPrepation;
    }

    public function setTempsPrepation(\DateTimeInterface $tempsPrepation): self
    {
        $this->tempsPrepation = $tempsPrepation;

        return $this;
    }

    public function getTempsRepos(): ?\DateTimeInterface
    {
        return $this->tempsRepos;
    }

    public function setTempsRepos(\DateTimeInterface $tempsRepos): self
    {
        $this->tempsRepos = $tempsRepos;

        return $this;
    }

    public function getTempsCuisson(): ?\DateTimeInterface
    {
        return $this->tempsCuisson;
    }

    public function setTempsCuisson(\DateTimeInterface $tempsCuisson): self
    {
        $this->tempsCuisson = $tempsCuisson;

        return $this;
    }

    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    public function setIngredients(string $ingredients): self
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    public function getEtapes(): ?string
    {
        return $this->etapes;
    }

    public function setEtapes(string $etapes): self
    {
        $this->etapes = $etapes;

        return $this;
    }

    // public function getAllergenes(): ?string
    // {
    //     return $this->allergenes;
    // }

    // public function setAllergenes(string $allergenes): self
    // {
    //     $this->allergenes = $allergenes;

    //     return $this;
    // }

    // public function getTypeRegime(): ?string
    // {
    //     return $this->typeRegime;
    // }

    // public function setTypeRegime(string $typeRegime): self
    // {
    //     $this->typeRegime = $typeRegime;

    //     return $this;
    // }

    /**
     * @return Collection<int, Allergie>
     */
    public function getAllergies(): Collection
    {
        return $this->allergies;
    }

    public function addAllergy(Allergie $allergy): self
    {
        if (!$this->allergies->contains($allergy)) {
            $this->allergies->add($allergy);
            $allergy->addRecette($this);
        }

        return $this;
    }

    public function removeAllergy(Allergie $allergy): self
    {
        if ($this->allergies->removeElement($allergy)) {
            $allergy->removeRecette($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Regime>
     */
    public function getRegimes(): Collection
    {
        return $this->regimes;
    }

    public function addRegime(Regime $regime): self
    {
        if (!$this->regimes->contains($regime)) {
            $this->regimes->add($regime);
            $regime->addRecette($this);
        }

        return $this;
    }

    public function removeRegime(Regime $regime): self
    {
        if ($this->regimes->removeElement($regime)) {
            $regime->removeRecette($this);
        }

        return $this;
    }

    public function isAccessiblePatient(): ?bool
    {
        return $this->accessiblePatient;
    }

    public function setAccessiblePatient(bool $accessiblePatient): self
    {
        $this->accessiblePatient = $accessiblePatient;

        return $this;
    }

    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): self
    {
        if (!$this->avis->contains($avi)) {
            $this->avis->add($avi);
            $avi->setRecette($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): self
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getRecette() === $this) {
                $avi->setRecette(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->titre ;
    }

//     public function getTempsTotal(): ?int
// public function getTempsTotal(): ?int
// {
//     $tempsPreparation = $this->tempsPrepation ?? new \DateTime('00:00');
//     $tempsRepos = $this->tempsRepos ?? new \DateTime('00:00');
//     $tempsCuisson = $this->tempsCuisson ?? new \DateTime('00:00');

//     $intervalPreparation = $tempsPreparation->diff(new \DateTime('00:00'));
//     $intervalRepos = $tempsRepos->diff(new \DateTime('00:00'));
//     $intervalCuisson = $tempsCuisson->diff(new \DateTime('00:00'));

//     $temptotal = $intervalPreparation->i + $intervalRepos->i + $intervalCuisson->i;

//     return $temptotal;
// }
public function getTempsTotal(): ?string
{
    $tempsPreparation = $this->tempsPrepation ?? new \DateTime('00:00');
    $tempsRepos = $this->tempsRepos ?? new \DateTime('00:00');
    $tempsCuisson = $this->tempsCuisson ?? new \DateTime('00:00');

    $totalMinutes = $tempsPreparation->format('H') * 60 + $tempsPreparation->format('i');
    $totalMinutes += $tempsRepos->format('H') * 60 + $tempsRepos->format('i');
    $totalMinutes += $tempsCuisson->format('H') * 60 + $tempsCuisson->format('i');

    $hours = floor($totalMinutes / 60);
    $minutes = $totalMinutes % 60;

    $tempsTotal = '';
    if ($hours > 0) {
        $tempsTotal .= $hours . 'h ';
    }
    $tempsTotal .= $minutes . 'min';

    return $tempsTotal;
}


}