<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function home()
    {
        return $this->render("main/home.html.twig");
    }

    /**
     * @Route("/aboutus", name="main_about_us")
     */
    public function aboutUs()
    {
        $file = '../var/data/team.json';
        $data = file_get_contents($file);
        $team = json_decode($data);

        return $this->render("main/aboutUs.html.twig", [
            'team' => $team,
        ]);
    }
}