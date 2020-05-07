<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PlaceRepository;
use App\Repository\WorkTimeRepository;

class PlaceController extends AbstractController
{
    /**
     * @Route("/place/{id}", name="place")
     */
    public function index(int $id, PlaceRepository $placeRepository, WorkTimeRepository $WorkTimeRepository)
    {
        $place = $placeRepository->findOneBy(['id' => $id]);
        $placeName = $place->getName();
        $placeAddress = $place->getAddress();
        $placeCategory = $place->getCategory()->getName();
        $placePhoneNumber = $place->getPhoneNumber();
        $placeDescription = $place->getDescription();
        $workTimes = $place -> getWorkTimes();

        $placeServices = $place->getPlaceHasServices();
        $services = [];
        foreach ($placeServices as $service) {
            array_push($services, [$service->getService()->getName(), $service->getPrice()]);
        }

        $placeWorkTimes = $place->getWorkTimes();

        return $this->render('place/index.html.twig', [
            'place_name' => $placeName,
            'place_address' => $placeAddress,
            'place_category' => $placeCategory,
            'place_phone_number' => $placePhoneNumber,
            'place_description' => $placeDescription,
            'place_services' => $services,
            'workTimes' => $workTimes
        ]);
    }
}
