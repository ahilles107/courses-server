<?php

declare(strict_types=1);

namespace App\Controller;

use App\Factory\UserFactoryInterface;
use App\Form\ErrorHandler;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

final class ApiUsersController extends AbstractController
{
    protected $serializer;

    private $userFactory;

    public function __construct(SerializerInterface $serializer, UserFactoryInterface $userFactory)
    {
        $this->serializer = $serializer;
        $this->userFactory = $userFactory;
    }

    public function getCurrentUser(): Response
    {
        return new Response($this->serializer->serialize($this->getUser(), 'json', [
            'groups' => [
                'user_details',
                'course_details',
            ],
        ]));
    }

    public function registerUser(
        Request $request,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->userFactory->create();
        $form = $formFactory->create(RegisterUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return new Response($this->serializer->serialize($user, 'json', ['groups' => ['user_details']]), Response::HTTP_CREATED);
        }

        return new Response($this->serializer->serialize(ErrorHandler::getErrorsFromForm($form), 'json'), Response::HTTP_BAD_REQUEST);
    }
}
