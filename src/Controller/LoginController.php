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
    public function index(Request $request, AuthenticationUtils $utils, userRepository $userRepository)
    {
        $error = "";
        $userEmail = "";
        if ($request->isMethod('POST')) {
            $user = new User();
            $params = $request->request->all();
            $userEmail = $params['email'];
            $userPassword = $params['password'];
            if ($params && $userEmail) {
                $userInBD = $userRepository->findOneBy(['email' => $userEmail]);
                if ($userInBD && $userPassword == $userInBD->getPassword())
                {
                    return $this->redirectToRoute('default');
                } else {
                    $error = "incorrect email or password";
                    return $this->render(
                        'login/index.html.twig',
                        array('error' => $error, 'email' =>$userEmail)
                    );
                }
            } else {
                $error = "email can't be empty";
                return $this->render(
                    'login/index.html.twig',
                    array('error' => $error, 'email' =>$userEmail)
                );
            }
        }
        return $this->render(
            'login/index.html.twig',
            array('error' => $error, 'email' =>$userEmail)
        );
    }
}