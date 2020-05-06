<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PlaceController extends AbstractController
{
    /**
     * @Route("/place/{id}", name="place")
     */
    public function index(int $id)
    {
        return $this->render('place/index.html.twig', [
            'controller_name' => "Place".$id,
        ]);
    }
}
