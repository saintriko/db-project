<?php

namespace App\Controller;

use App\Form\LoginForm;
use App\Entity\User;
use App\Repository\RoleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function index(Request $request, roleRepository $roleRepository)
    {
// 1) постройте форму
        $user = new User();
        $form = $this->createForm(LoginForm::class, $user);

// 2) обработайте отправку (произойдёт только в POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $role = $roleRepository
                ->find(1);

            $user->setRole($role);
            $user->setToken(sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535)));

// 4) сохраните Пользователя!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            var_dump($user);
            $em->flush();


            return new Response();
//return $this->redirectToRoute('/success');
        }

//return $this->render('login/index.html.twig', [
// 'controller_name' => 'LoginController',
//]);

        return $this->render(
            'login/index.html.twig',
            array('form' => $form->createView())
        );
    }
}
