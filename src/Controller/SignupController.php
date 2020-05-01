<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SignupForm;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignupController extends AbstractController
{
    /**
     * @Route("/signup", name="signup")
     */
    public function index(Request $request, roleRepository $roleRepository, userRepository $userRepository)
    {
        $user = new User();
        $form = $this->createForm(SignupForm::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!is_null($userRepository->findOneBy(['email' => $user->getEmail()]))) {
                return $this->render(
                    'signup/index.html.twig',
                    array('form' => $form->createView(),
                        'error' => "email already exist")
                );
            }

            $role = $roleRepository
                ->findOneBy(['name' => 'user']);
            $user->setRole($role);
            $user->setToken(sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535)));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return new Response('success');
        }

        return $this->render(
            'signup/index.html.twig',
            array('form' => $form->createView(),
                'error' => "")
        );
    }
}
