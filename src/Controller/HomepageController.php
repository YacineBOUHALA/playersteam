<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/', name:'homepage.index')]
    public function index():Response
    {
        $value = true;
        $array = ['value0', 'value1', 'value2'];
        $arrayAssoc =
            [
              'key0' => 'value0',
              'key1' => 'value1',
              'key2' => 'value2',
            ];
        return $this->render('homepage/index.html.twig',[
            'myarray'=> $array,
            'myAssocArray'=> $arrayAssoc,
            'value'=> $value,
            'now'=> new \DateTime(),
        ]);
    }

    #[Route('/hello/{name}-{age}', name: 'homepage.hello')]
    public function hello(string $name = null, int $age=null,):Response
    {
        return $this->render('/homepage/hello.html.twig', [
            'name'=> $name,
            'age'=> $age,
        ]
        );
    }
}