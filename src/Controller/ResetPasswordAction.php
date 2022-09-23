<?php

namespace App\Controller;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;


#[AsController]
class ResetPasswordAction extends AbstractController
{
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var UserPasswordHasherInterface
     */
    private $userPasswordEncoder;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var JWTTokenManagerInterface
     */
    private $tokenManager;

    /**
     * ResetPasswordAction constructor.
     * @param ValidatorInterface $validator
     * @param UserPasswordHasherInterface $userPasswordEncoder
     * @param EntityManagerInterface $entityManager
     * @param JWTTokenManagerInterface $tokenManager
     */
    public function __construct(
        ValidatorInterface $validator,
        UserPasswordHasherInterface $userPasswordEncoder,
        EntityManagerInterface $entityManager,
        JWTTokenManagerInterface $tokenManager

    )
    {

        $this->validator = $validator;

        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->entityManager = $entityManager;
        $this->tokenManager = $tokenManager;
    }
    public function __invoke(User $data)
    {
        $errors = $this->validator->validate($data, null, ['put-reset-password']);

        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
        $data->setPassword(
            $this->userPasswordEncoder->hashPassword($data, $data->getNewPassword())
        );
            // After password change, old tokens are still valid
        $data->setPasswordChangedDate(time());
        $this->entityManager->flush();

        $token = $this->tokenManager->create($data);

        return new JsonResponse(['token' => $token]);
        // $errors = $this->validator->validate($data, null, ['put-reset-password']);
 
        // if (count($errors) > 0) {
        //     throw new ValidationException($errors);
        // }
        //return new JsonResponse($data->getUsername());
       
        // $violations = $this->validator->validate($data, null, ['put-reset-password']);

        // if (count($violations) > 0) {
        //     $errors = array();
        //     foreach ($violations as $error) {

        //         $errors[$error->getPropertyPath()] = $error->getMessage();
        //     }
        //     $data = [
        //         'type' => 'validation_error',
        //         'title' => 'There was a validation error',
        //         'errors' => $errors
        //     ];
        //     return new JsonResponse($data, 400);

        // } 
        //else {
        //     $data->setPassword(
        //         $this->userPasswordEncoder->hashPassword(
        //             $data, $data->getNewPassword()
        //         )
        //     );
        //     // After password change, old tokens are still valid
        //     //$data->setPasswordChangedDate(time());

        //     $this->entityManager->flush();

        //     $token = $this->tokenManager->create($data);

        //     return new JsonResponse(['token' => $token]);
        // }

    }


}