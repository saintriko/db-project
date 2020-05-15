<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserSavedPlace;
use App\Repository\UserRepository;
use App\Repository\PlaceRepository;
use App\Repository\UserSavedPlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FavouritePlacesController extends AbstractController
{
    /**
     * @Route("/favourite", name="favourite_places")
     */
    public function index(placeRepository $placeRepository, UserSavedPlaceRepository $userSavedPlaceRepository)
    {
        $user = $this->getUser();
        $places = $userSavedPlaceRepository->findBy(['user'=>$user]);
        return $this->render('favourite_places/index.html.twig', [
            'places' => $places
        ]);
    }

    /**
     * @Route("/addFavourite/{id}", name="add_to_favourite")
     */
    public function add(int $id, UserRepository $userRepository, placeRepository $placeRepository, UserSavedPlaceRepository $userSavedPlaceRepository)
    {
        $user = $this->getUser();
        $user = $userRepository->findOneBy(['id'=>$user->getId()]);
        $place = $placeRepository->findOneBy(['id' => $id]);
        if (!$place) {
            throw $this->createNotFoundException('The place does not exist');
        }

        if (!$userSavedPlaceRepository->findOneBy(['user' => $user, 'place' => $place])) {
            $savedPlace = new UserSavedPlace();
            $savedPlace->setPlace($place);
            $savedPlace->setUser($user);
            $savedPlace->setDate(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($savedPlace);
            $em->flush();
        }
        return $this->redirectToRoute('place', ['id'=> $id]);
    }

    /**
     * @Route("/removeFavourite/{id}", name="remove_from_favourite")
     */
    public function remove(int $id, UserRepository $userRepository, placeRepository $placeRepository, UserSavedPlaceRepository $userSavedPlaceRepository)
    {
        $user = $this->getUser();
        $user = $userRepository->findOneBy(['id'=>$user->getId()]);

        $place = $placeRepository->findOneBy(['id' => $id]);
        if (!$place) {
            throw $this->createNotFoundException('The place does not exist');
        }

        $favourite = $userSavedPlaceRepository->findOneBy(['user' => $user, 'place' => $place]);

        $em = $this->getDoctrine()->getManager();
        $em->remove($favourite);
        $em->flush();

        return $this->redirectToRoute('place', ['id'=> $id]);
    }
}
