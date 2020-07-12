<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Program;
use App\Entity\Category;
use App\Entity\Season;
use App\Repository\CategoryRepository;
use Psr\Container\ContainerInterface;
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
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();
        if (!$programs){
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }
        return $this->render('wild/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    /**
     * Getting a program with a formatted slug for title
     *
     * @param string $slug The slugger
     * @Route("wild/show/{slug<^[a-z0-9-]+$>}", defaults={"slug" = null}, name="show")
     * @return Response
     */
    public function show(?string $slug):Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug'  => $slug,
        ]);
    }
    /**
     *
     * @Route("/wild/category/{categoryName}", name="show_category")
     */
    public function showByCategory(string $categoryName)
    {

        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => ucwords($categoryName)]);
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' => $category],['id'=>'DESC'],3);



        return $this->render('wild/category.html.twig', [
            'programs' => $programs,


        ]);
    }
    /**
     * @Route("/wild/program/{slug}", name="show_program")
     */
    public function showByProgram(string $slug)
    {

        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title'=>$slug]);

        $seasons = '';
        if($program){
            $seasons = $program->getSeasons();
        }


        return $this->render('wild/program.html.twig', [
            'program' => $program,
            'seasons' => $seasons,

        ]);
    }
    /**
     * @Route("/wild/season/{id}", name="show_season")
     */
    public function showBySeason(int $id)
    {

        
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['id'=>$id]);


        $episodes = '';

        if($season){
            $episodes = $season->getEpisodes();
        }

        //var_dump($episodes);

        return $this->render('wild/season.html.twig', [
            'season' => $season,
            'episodes' => $episodes,
        ]);
    }
    
}


