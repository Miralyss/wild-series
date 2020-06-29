<?php
// src/Controller/WildController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{
    /**
     * @Route("/wild", name="wild_index")
     */
    public function index() :Response
    {
        return $this->render('wild/index.html.twig', [
            'title' => 'Bienvenue',
        ]);
    }

    /**
     * @Route("/wild/show/{slug}",  name="wild_show")
     */

    public function show(string $slug='Aucune série sélectionnée, veuillez choisir une série'): Response
    {
        if($slug=="MaSerie" || $slug=="ma_serie"){
            return $this->render('wild/show.html.twig', ['slug' => "Error 404"]);
            die;
        }
        if (!strpos($slug, " ")) {
            $newSlug = ucwords(str_replace("-", " ", $slug));
        }
        else {
            $newSlug = $slug;
        }
        return $this->render('wild/show.html.twig', ['slug' => ucwords(str_replace("-", " ", $slug))]);


    }
    
}


