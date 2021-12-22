<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile/user", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/list", name="user_list", methods={"GET"})
     */
    public function userList(UserRepository $userRepo): Response
    {
        $user = $userRepo->findBy([]);
        return $this->render('user/user.html.twig', compact('user'));
    }

    /**
     * @Route("/myaccount", name="account", methods={"GET"})
     */
    public function account(UserRepository $userRepo)
    {
        $user = $userRepo->findBy([]);
        return $this->render('user/account.html.twig', compact('user'));
    }
}
