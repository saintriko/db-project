<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PlaceRepository;
use App\Repository\WorkTimeRepository;
use Symfony\Component\HttpFoundation\Response;

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
