<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Place;
use App\Entity\WorkTime;
use App\Repository\CategoryRepository;
use App\Repository\ImageRepository;
use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index(placeRepository $placeRepository, categoryRepository $categoryRepository)
    {
        return $this->redirectToRoute('page');
    }

    /**
     * @Route("/p/{page}", name="page", defaults={"page": 1})
     */
    public function pages(placeRepository $placeRepository, categoryRepository $categoryRepository, $page)
    {
        $categories = $categoryRepository
            ->findAll();

        $placesOnPage = 10;
        if ($this->getUser()) {
            $placesByAvg = $placeRepository->findAvgRatePlace($this->getUser()->getId());
        } else {
            $placesByAvg = $placeRepository->findAvgRatePlaceWithoutUserRate();
        }
        $countOfPlaces = count($placesByAvg) - 1;
        $countOfPages = $countOfPlaces / $placesOnPage;

        $placesByAvg = array_slice($placesByAvg, $placesOnPage * ($page - 1), $placesOnPage);


        return $this->render('default/index.html.twig', [
            'places' => $placesByAvg,
            'categories' => $categories,
            'currentCategory' => "",
            'countOfPages' => $countOfPages,
            'currentPage' => $page
        ]);
    }


    /**
     * @Route("/category/{category}/{page}", name="category", defaults={"page": 1})
     */
    public function category(placeRepository $placeRepository, categoryRepository $categoryRepository, $category, $page)
    {
        $categories = $categoryRepository
            ->findAll();

        $placesOnPage = 10;
        if ($this->getUser()) {
            $placesByAvg = $placeRepository->findAvgRatePlaceByCategory($categoryRepository->findOneBy(['name' => $category])->getId(), $this->getUser()->getId());
        } else {
            $placesByAvg = $placeRepository->findAvgRatePlaceByCategoryWithoutUserRate($categoryRepository->findOneBy(['name' => $category])->getId());
        }
        $countOfPlaces = count($placesByAvg) - 1;
        $countOfPages = $countOfPlaces / $placesOnPage;
        $placesByAvg = array_slice($placesByAvg, $placesOnPage * ($page - 1), $placesOnPage);

        return $this->render('default/index.html.twig', [
            'places' => $placesByAvg,
            'categories' => $categories,
            'currentCategory' => $category,
            'countOfPages' => $countOfPages,
            'currentPage' => $page
        ]);
    }

    /**
     * @Route("/admin/add", name="add place")
     */
    public function add(categoryRepository $categoryRepository, Request $request)
    {
        $params = $request->request->all();

        $categories = $categoryRepository
            ->findAll();


        return $this->render('default/addPlace.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/addFormAction", name="add place action")
     */
    public function addFormAction(categoryRepository $categoryRepository, Request $request, ImageRepository $ImageRepository)
    {
        $params = $request->request->all();

        $place = new Place();
        $place->setName($params["name"]);
        $place->setAddress($params["address"]);
        $place->setPhoneNumber($params["phone"]);
        $place->setCategory($categoryRepository
            ->findOneBy(['id' => $params["category"]]));
        $place->setDescription($params["description"]);
        $place->setLatitude($params["latitude"]);
        $place->setLongitude($params["longitude"]);
        $em = $this->getDoctrine()->getManager();
        $em->persist($place);
        $em->flush();

        $files = $request->files->all();
        foreach ($files as $file) {
            if (!is_null($file)) {
                try {
                    $filename = uniqid("") . "." . $file->guessExtension();
                    $file->move($this->getParameter('kernel.project_dir') . '/public/images', $filename);  //TODO путь может не работать в онлайне
                    $photo = new Image();
                    $photo->setPlace($place);
                    $photo->setPath($filename);
                    $emPhoto = $this->getDoctrine()->getManager();
                    $emPhoto->persist($photo);
                    $emPhoto->flush();
                } catch (FileException $e) {
                }
            }
        }

        for ($i = 0; $i <= 6; $i++) {
            $workTime = new WorkTime();
            $workTime->setPlace($place);
            $workTime->setStartTime(\DateTime::createFromFormat('H:i', $params[$i . "_start_time"]));
            $workTime->setEndTime(\DateTime::createFromFormat('H:i', $params[$i . "_end_time"]));
            $workTime->setWeekDay($i);
            $em = $this->getDoctrine()->getManager();
            $em->persist($workTime);
            $em->flush();
        }


        return $this->redirectToRoute('default');
    }
}
