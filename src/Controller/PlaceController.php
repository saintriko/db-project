<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(Request $request, int $id, PlaceRepository $placeRepository, WorkTimeRepository $WorkTimeRepository, UserRepository $UserRepository)
    {
        $place = $placeRepository->findOneBy(['id' => $id]);
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
            $userFeedBack = new UserFeedbackPlace();
            $userFeedBack->setPlace($place);
            $userFeedBack->setUser($UserRepository->findOneBy(['id' => 1]));  // TODO: заменить
            $userFeedBack->setFeedback($request->get('feedback_text'));
            $userFeedBack->setDate(new DateTime('NOW'));
            $userFeedBack->setRate($request->get('rate'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($userFeedBack);
            $em->flush();
            return $this->redirectToRoute('place', ['id'=> $id]);
        }

        return $this->render('place/index.html.twig', [
            'place' => $place,
            'workTimes' => $placeWorkTimes,
            'argRate' => $argRate,
            'imagesPaths' => $imagesPaths
        ]);
    }
}
