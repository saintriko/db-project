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
        if ($request->isMethod('POST')) {
            $params = $request->request->all();
            $user->setName($params['name']);
            $user->setEmail($params['email']);
            $user->setPassword($params['password']);

            if (!is_null($userRepository->findOneBy(['email' => $params['email']]))) {
                return $this->render(
                    'signup/index.html.twig',
                    array(
                        'error' => "email already registered", 'email' => "")
                );
            } else {

                    $role = $roleRepository
                        ->findOneBy(['name' => 'ROLE_USER']);
                    $user->setRole($role);
                    $user->setToken(sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535)));

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();
                    return new Response('success');
            }
        }
        return $this->render(
            'signup/index.html.twig',
            array(
                'error' => "", 'email' => "")
        );
    }
}
