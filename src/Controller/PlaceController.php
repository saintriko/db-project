<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\PlaceHasService;
use App\Entity\UserSavedPlace;
use App\Repository\ImageRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserFeedbackPlaceRepository;
use App\Repository\UserSavedPlaceRepository;
use DateTime;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PlaceRepository;
use App\Repository\WorkTimeRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\UserFeedbackPlace;
use App\Entity\Service;
use App\Repository\PlaceHasServiceRepository;

use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use App\Repository\UserRepository;

class PlaceController extends AbstractController
{
    /**
     * @Route("/place/{id}", name="place")
     */
    public function index(Request $request, int $id, UserRepository $userRepository, UserSavedPlaceRepository $userSavedPlaceRepository, PlaceRepository $placeRepository, WorkTimeRepository $WorkTimeRepository, UserRepository $UserRepository, UserFeedbackPlaceRepository $userFeedbackPlaceRepository)
    {
        $user = $this->getUser();
        if ($user)
            $user = $userRepository->findOneBy(['id'=>$user->getId()]);

        $place = $placeRepository->findOneBy(['id' => $id]);

        if (!$place) {
            throw $this->createNotFoundException('The place does not exist');
        }

        $placeWorkTimes = $place->getWorkTimes();

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

        if ($placeAllRates) {
            $argRate = round(array_sum($placeAllRates) / count($placeAllRates), 2);
        }

        if ($request->isMethod('POST')) {
            $userFeedBack = $userFeedbackPlaceRepository->findOneBy(['user' => $user, 'place' => $place]);
            if (!$userFeedBack)
                $userFeedBack = new UserFeedbackPlace();
            $userFeedBack->setPlace($place);
            $userFeedBack->setUser($user);
            $userFeedBack->setFeedback($request->get('feedback_text'));
            $userFeedBack->setDate(new DateTime('NOW'));
            $userFeedBack->setRate($request->get('rate'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($userFeedBack);
            $em->flush();
            return $this->redirectToRoute('place', ['id'=> $id]);
        }

        $commentaries = $place -> getUserFeedbackPlaces();

        $savedPlace = ($user != null) ? $userSavedPlaceRepository->findOneBy(['user' => $user, 'place' => $place]) : null;

        return $this->render('place/index.html.twig', [
            'place' => $place,
            'workTimes' => $placeWorkTimes,
            'argRate' => $argRate,
            'imagesPaths' => $imagesPaths,
            'commentaries' => $commentaries,
            'saved' => $savedPlace
        ]);
    }

    /**
     * @Route("/place/{id}/editServicesremove_from_favourite", name="edit services")
     */
    public function editServices(int $id, PlaceRepository $placeRepository, CategoryRepository $categoryRepository)
    {
        $place = $placeRepository->findOneBy(['id' => $id]);
        if (!$place) {
            throw $this->createNotFoundException('The place does not exist');
        }

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
    public function deleteService(int $id, int $idService, PlaceHasServiceRepository $placeHasServiceRepository)
    {
        $placeHasService = $placeHasServiceRepository->findOneBy(['id' => $idService]);

        $em = $this->getDoctrine()->getManager();
        $em->remove($placeHasService);
        $em->flush();

        return $this->redirectToRoute('edit services', ['id'=> $id]);
    }

    /**
     * @Route("/place/{id}/addService", name="add service action")
     */
    public function addService(int $id, PlaceRepository $placeRepository, ServiceRepository $serviceRepository, Request $request)
    {
        $params = $request->request->all();
        $place = $placeRepository->findOneBy(['id' => $id]);

        $service = $serviceRepository->findOneBy(['name' => $params["name"]]);
        if ($service == NULL)
        {
            $service = new Service();
            $service -> setName($params["name"]);
            $em = $this->getDoctrine()->getManager();
            $em->persist($service);
            $em->flush();
        }

        $placeHasService = new PlaceHasService();
        $placeHasService -> setPlace($place);
        $placeHasService -> setService($service);
        $placeHasService -> setPrice($params["price"]);
        $em = $this->getDoctrine()->getManager();
        $em->persist($placeHasService);
        $em->flush();

        return $this->redirectToRoute('edit services', ['id'=> $id]);
    }

    /**
     * @Route("/place/{id}/deleteImage/{idImage}", name="delete image")
     */
    public function deleteImage(int $id, int $idImage, PlaceHasServiceRepository $placeHasServiceRepository, ImageRepository $imageRepository)
    {
        $image = $imageRepository->findOneBy(['id' => $idImage]);
        $em = $this->getDoctrine()->getManager();
        $em->remove($image);
        $em->flush();

        return $this->redirectToRoute('edit place', ['id'=> $id]);
    }

    /**
     * @Route("/place/{id}/edit", name="edit place")
     */
    public function editPlace(int $id, PlaceRepository $placeRepository, CategoryRepository $categoryRepository)
    {
        $place = $placeRepository->findOneBy(['id' => $id]);
        if (!$place) {
            throw $this->createNotFoundException('The place does not exist');
        }

        $categories = $categoryRepository
            ->findAll();

        $placeWorkTimes = $place->getWorkTimes();
        $placeImages = $place->getImages();

        return $this->render('place/editPlace.html.twig', [
            'categories' => $categories,
            'place' => $place,
            'workTimes' => $placeWorkTimes,
            'id' => $id,
            'placeImages' => $placeImages
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

        $files = $request->files->all();
        foreach ($files as $file) {
            print_r ($file);
            if (!is_null($file)) {
                try {
                    $filename = uniqid($params["name"]) . "." . $file->guessExtension();
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

        $placeWorkTimes = $place->getWorkTimes();

        for($i = 0; $i <= 6; $i++) {
            $placeWorkTimes[$i] ->setStartTime(\DateTime::createFromFormat('H:i', $params[$i . "_start_time"]));
            $placeWorkTimes[$i] ->setEndTime(\DateTime::createFromFormat('H:i', $params[$i . "_start_time"]));
            $em = $this->getDoctrine()->getManager();
            $em->persist($placeWorkTimes[$i]);
            $em->flush();
        }

        return $this->redirectToRoute('place', ['id'=> $id]);
    }
}
