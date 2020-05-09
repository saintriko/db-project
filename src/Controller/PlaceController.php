<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PlaceRepository;
use App\Repository\WorkTimeRepository;
use App\Entity\UserFeedbackPlace;

use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;

use App\Repository\UserRepository;

class PlaceController extends AbstractController
{
    /**
     * @Route("/place/{id}", name="place")
     */
    public function index(int $id, PlaceRepository $placeRepository, WorkTimeRepository $WorkTimeRepository, UserRepository $UserRepository)
    {
        $place = $placeRepository->findOneBy(['id' => $id]);
        $placeName = $place->getName();
        $placeAddress = $place->getAddress();
        $placeCategory = $place->getCategory()->getName();
        $placePhoneNumber = $place->getPhoneNumber();
        $placeDescription = $place->getDescription();
        $placeWorkTimes = $place->getWorkTimes();
        $placeLatitude = $place->getLatitude();
        $placeLongitude = $place->getLongitude();
        $placeServices = $place->getPlaceHasServices();

        $placeImages = $place->getImages();
        $imagesPaths = [];
        foreach($placeImages as $image) {
            array_push($imagesPaths, "images/".$image->getPath());
        }

        $placeAllRates = [];
        $argRate = 0;
        $placeFeedback = $place->getUserFeedbackPlaces();
        foreach ($placeFeedback as $Feedback) {
            array_push($placeAllRates, $Feedback->getRate());
        }

        $services = [];
        foreach ($placeServices as $service) {
            array_push($services, [$service->getService()->getName(), $service->getPrice()]);
        }

        if($placeAllRates) {
            $argRate = round(array_sum($placeAllRates) / count($placeAllRates), 2);
        }


        //fixture

//        for ($i = 0; $i < 19000; $i= $i + 1) {
//            $feedback = new UserFeedbackPlace();
//            $feedback->setUser($UserRepository->find(1));
//            $feedback->setPlace($placeRepository->find(1));
//            $feedback->setDate(new DateTime('NOW'));
//            $feedback->setFeedback("good");
//            $feedback->setRate(rand(1, 10));
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($feedback);
//            $em->flush();
//        }

        return $this->render('place/index.html.twig', [
            'place_name' => $placeName,
            'place_address' => $placeAddress,
            'place_category' => $placeCategory,
            'place_phone_number' => $placePhoneNumber,
            'place_description' => $placeDescription,
            'place_services' => $services,
            'workTimes' => $placeWorkTimes,
            'argRate' => $argRate,
            'placeLongitude' => $placeLongitude,
            'placeLatitude' => $placeLatitude,
            'imagesPaths' => $imagesPaths
        ]);
    }
}
