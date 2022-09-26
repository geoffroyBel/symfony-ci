<?php


namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;


#[ApiResource(
    collectionOperations: [
        "post" => [ "path" => "/users/confirmation"]
    ],
    itemOperations: []
)]
class UserConfirmation
{
    #[Assert\NotBlank()]
    #[Assert\Length(30)]
    public $confirmationToken;
}