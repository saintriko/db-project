<?php

namespace App\Controller;

use DateTime;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PlaceRepository;
use App\Repository\WorkTimeRepository;
use Symfony\Component\HttpFoundation\Response;
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
        foreach ($placeImages as $image) {
            $filePath = "images/" . $image->getPath();
            if (file_exists($filePath)) {
                array_push($imagesPaths, $filePath);
            }
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

        if ($placeAllRates) {
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

    /**
     * @Route("/place/{id}/editServices", name="edit services")
     */
    public function editServices(int $id, PlaceRepository $placeRepository, CategoryRepository $categoryRepository)
    {
        $place = $placeRepository->findOneBy(['id' => $id]);

        $placeServices = $place->getPlaceHasServices();
        $services = [];
        foreach ($placeServices as $service) {
            array_push($services, [$service->getId(), $service->getService()->getName(), $service->getPrice()]);
        }

        return $this->render('place/editService.html.twig', [
            'services' => $services,
            'place' => $place,
            'id' => $id
        ]);
    }

    /**
     * @Route("/place/{id}/deleteService/{idService}", name="delete service action")
     */
    public function deleteService(int $id, PlaceRepository $placeRepository, WorkTimeRepository $WorkTimeRepository)
    {
        $place = $placeRepository->findOneBy(['id' => $id]);
        $services = $place -> getPlaceHasServices();

        $placeServices = $place->getPlaceHasServices();
        $services = [];
        foreach ($placeServices as $service) {
            array_push($services, [$service->getId(), $service->getService()->getName(), $service->getPrice()]);
        }

        $placeWorkTimes = $place->getWorkTimes();

        return $this->render('place/index.html.twig', [
            'services' => $services
        ]);
    }

    /**
     * @Route("/place/{id}/addService", name="add service action")
     */
    public function addService(int $id, PlaceRepository $placeRepository, WorkTimeRepository $WorkTimeRepository)
    {
        $place = $placeRepository->findOneBy(['id' => $id]);
        $services = $place -> getPlaceHasServices();

        $placeServices = $place->getPlaceHasServices();
        $services = [];
        foreach ($placeServices as $service) {
            array_push($services, [$service->getService()->getName(), $service->getPrice()]);
        }

        return $this->render('place/index.html.twig', [
            'services' => $services
        ]);
    }

    /**
     * @Route("/place/{id}/edit", name="edit place")
     */
    public function editPlace(int $id, PlaceRepository $placeRepository, CategoryRepository $categoryRepository)
    {
        $place = $placeRepository->findOneBy(['id' => $id]);

        $placeServices = $place->getPlaceHasServices();
        $services = [];
        foreach ($placeServices as $service) {
            array_push($services, [$service->getId(), $service->getService()->getName(), $service->getPrice()]);
        }

        return $this->render('place/editService.html.twig', [
            'services' => $services,
            'place' => $place,
            'id' => $id
        ]);
    }

    /**
     * @Route("/place/{id}/editAction", name="edit place action")
     */
    public function editPlaceAction(int $id, PlaceRepository $placeRepository, CategoryRepository $categoryRepository, Request $request)
    {
        $place = $placeRepository->findOneBy(['id' => $id]);

        $params = $request->request->all();
        $place -> setName($params["name"]);
        $place -> setAddress($params["address"]);
        $place -> setPhoneNumber($params["phone"]);
        $place -> setCategory($categoryRepository
            ->findOneBy(['id' => $params["category"]]));
        $place -> setDescription($params["description"]);
        $place -> setLatitude($params["latitude"]);
        $place -> setLongitude($params["longitude"]);

        $em = $this->getDoctrine()->getManager();
        $em->persist($place);
        $em->flush();

        $placeWorkTimes = $place->getWorkTimes();

        for($i = 0; $i <= 6; $i++) {
            $placeWorkTimes[$i] ->setStartTime(\DateTime::createFromFormat('H:i', $params[$i . "_start_time"]));
            $placeWorkTimes[$i] ->setEndTime(\DateTime::createFromFormat('H:i', $params[$i . "_start_time"]));
            $em = $this->getDoctrine()->getManager();
            $em->persist($placeWorkTimes[$i]);
            $em->flush();
        }

        return new Response('success');
    }
}
