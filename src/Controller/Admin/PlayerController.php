<?php

namespace App\Controller\Admin;

use App\Entity\Player;
use App\Form\PlayerType;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\ByteString;

#[Route('/admin')]
class PlayerController extends AbstractController
{

    public function __construct(private PlayerRepository $playerRepository, private  RequestStack $requestStack, private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @return Response
     */
    #[Route('/player', name: 'admin.player.index')]
    public function index(): Response
    {
        $entities = $this->playerRepository->findAll();
        //dd($entities);
        return $this->render('admin/player/index.html.twig',[
            'entities' => $entities
        ]);
    }

    /**
     * @param int|null $id
     * @return Response
     */
    #[Route('/player/form/add', name: 'admin.player.form.add')]
    #[Route('/player/form/edit/{id}', name: 'admin.player.form.edit')]
    public function form(int $id=null): Response
    {
        $type = PlayerType::class;
        $model = $id ? $this->playerRepository->find($id): new Player();

        $model->prevImage= $id? $model->getPortrait() : null;

        $form = $this->createForm($type, $model);
        $form->handleRequest($this->requestStack->getCurrentRequest());

        if($form->isSubmitted() && $form->isValid()){
           if($form['portrait']->getData() instanceof UploadedFile){
               $file = $form['portrait']->getData();
               $randomName= ByteString::fromRandom(32)->lower();
               $fileExtention = $file->guessClientExtension();
               $fullFileName = "$randomName.$fileExtention";

               $model->setPortrait($fullFileName);

               $file->move('img/', $fullFileName);
           }else{
               $model->setPortrait($model->prevImage);
           }
            $this->entityManager->persist($model);
            $this->entityManager->flush();

            $message = $id ? 'Player has been updated':'Player has been added';
            $this->addFlash('notice', $message);

            return $this->redirectToRoute('admin.player.index');
        }
        //dd($entities);
        return $this->render('admin/player/form.html.twig',[
            'form'=> $form->createView(),
        ]);
    }

    #[Route('/player/remove/{id}', name: 'admin.player.form.remove')]
    public function  remove(int $id):Response
    {
        $entity = $this->playerRepository->find($id);
        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        $this->addFlash('notice', 'player has been removed');
        return $this->redirectToRoute('admin.player.index');
    }



}

