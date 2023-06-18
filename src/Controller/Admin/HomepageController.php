<?php

namespace App\Controller\Admin;


use App\Repository\PlayerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class HomepageController extends AbstractController
{

    public function __construct(private PlayerRepository $playerRepository)
    {
    }

    /**
     * @return Response
     */
    #[Route('/', name: 'admin.homepage.index')]
    public function index(): Response
    {
        $entities = $this->playerRepository->findAll();
        //dd($entities);
        return $this->render('admin/homepage/index.html.twig',[
            'entities' => $entities
        ]);
    }

}

