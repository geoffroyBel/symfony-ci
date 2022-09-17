<?php

 namespace App\Entity;

// use ApiPlatform\Core\Annotation\ApiResource;
// use App\Repository\HoraireRepository;
// use Doctrine\Common\Collections\ArrayCollection;
// use Doctrine\Common\Collections\Collection;
// use Doctrine\DBAL\Types\Types;
// use Doctrine\ORM\Mapping as ORM;
// use Symfony\Component\Serializer\Annotation\Groups;


// #[ORM\Entity(repositoryClass: HoraireRepository::class)]
// #[ApiResource(
//     subresourceOperations: [
//         'api_prestations_horaires_get_subresource' => [
//             'method' => 'GET',
//             'normalization_context' => 
//                 ["groups"=> ["get_horaire"]]
//             ,
//         ],
//     ],
//     collectionOperations: [
//         "post" => ["security" => "is_granted('IS_AUTHENTICATED_FULLY')"],
//         "get"
       
//     ],
//     itemOperations: [
//         "get",
//         "put" => ["security" => "is_granted('IS_AUTHENTICATED_FULLY')"],

//     ],
// )]
// class Horaire
// {
//     #[ORM\Id]
//     #[ORM\GeneratedValue]
//     #[ORM\Column()]
//     #[Groups(["get_horaire", "get_prestation"])]
//     private ?int $id = null;

//     #[ORM\Column(length: 255, nullable: true)]
//     private ?string $owner = null;

//     #[ORM\Column(length: 255, nullable: true)]
//     #[Groups(["get_horaire"])]
//     private ?string $rrule = null;

//     #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
//     #[Groups(["get_horaire"])]
//     private ?\DateTimeInterface $startDate = null;

//     #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
//     #[Groups(["get_horaire"])]
//     private ?\DateTimeInterface $endDate = null;

//     #[ORM\ManyToOne(inversedBy: 'horaires')]
//     private ?Prestation $prestation = null;

//     #[ORM\OneToMany(mappedBy: 'horaire', targetEntity: Reservation::class)]
//     private Collection $reservations;

//     public function __construct()
//     {
//         $this->reservations = new ArrayCollection();
//     }

//     public function getId(): ?int
//     {
//         return $this->id;
//     }

//     public function getOwner(): ?string
//     {
//         return $this->owner;
//     }

//     public function setOwner(?string $owner): self
//     {
//         $this->owner = $owner;

//         return $this;
//     }

//     public function getRrule(): ?string
//     {
//         return $this->rrule;
//     }

//     public function setRrule(?string $rrule): self
//     {
//         $this->rrule = $rrule;

//         return $this;
//     }

//     public function getStartDate(): ?\DateTimeInterface
//     {
//         return $this->startDate;
//     }

//     public function setStartDate(?\DateTimeInterface $startDate): self
//     {
//         $this->startDate = $startDate;

//         return $this;
//     }

//     public function getEndDate(): ?\DateTimeInterface
//     {
//         return $this->endDate;
//     }

//     public function setEndDate(?\DateTimeInterface $endDate): self
//     {
//         $this->endDate = $endDate;

//         return $this;
//     }

//     public function getPrestation(): ?Prestation
//     {
//         return $this->prestation;
//     }

//     public function setPrestation(?Prestation $prestation): self
//     {
//         $this->prestation = $prestation;

//         return $this;
//     }

//     /**
//      * @return Collection<int, Reservation>
//      */
//     public function getReservations(): Collection
//     {
//         return $this->reservations;
//     }

//     public function addReservation(Reservation $reservation): self
//     {
//         if (!$this->reservations->contains($reservation)) {
//             $this->reservations[] = $reservation;
//             $reservation->setHoraire($this);
//         }

//         return $this;
//     }

//     public function removeReservation(Reservation $reservation): self
//     {
//         if ($this->reservations->removeElement($reservation)) {
//             // set the owning side to null (unless already changed)
//             if ($reservation->getHoraire() === $this) {
//                 $reservation->setHoraire(null);
//             }
//         }

//         return $this;
//     }
// }
