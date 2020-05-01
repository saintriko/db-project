<?php

namespace App\Controller;

use App\Form\LoginForm;
use App\Entity\User;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function index(Request $request, AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        var_dump($_POST);
        $lastUsername = $utils->getLastUsername();
        return $this->render(
            'login/index.html.twig',
            array('error' => $error,
                'last_username' => $lastUsername)
        );
    }
}