<?php

namespace App\Controller;

use App\Entity\Place;
use App\Repository\CategoryRepository;
use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index(placeRepository $placeRepository)
    {
        $places = $placeRepository
            ->findBy(
                [],
                ['id' => 'ASC'],
                5,
                5
            );
        //var_dump($places);

        return $this->render('default/index.html.twig', [
            'places' => $places
        ]);
    }

    /**
     * @Route("/add", name="add place")
     */
    public function add(categoryRepository $categoryRepository, Request $request)
    {
        $params = $request->request->all();
        var_dump($params);
        $categories = $categoryRepository
            ->findAll();
        return $this->render('default/addPlace.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/addFormAction", name="add place action")
     */
    public function addFormAction(categoryRepository $categoryRepository, Request $request)
    {
        $params = $request->request->all();
        var_dump($params);
        $place = new Place();
        $place -> setName($params["name"]);
        $place -> setAddress($params["address"]);
        $place -> setPhoneNumber($params["phone"]);
        $place -> setCategory($categoryRepository
                ->findOneBy(['id' => $params["category"]]));
        $place -> setDescription($params["description"]);

        $em = $this->getDoctrine()->getManager();
        $em->persist($place);
        $em->flush();
        return new Response('success');
    }
}
