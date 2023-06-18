<?php

namespace App\Controller\Admin;

use App\Entity\NationalTeam;
use App\Entity\Player;
use App\Form\NationalTeamType;
use App\Form\PlayerType;
use App\Repository\NationalTeamRepository;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\ByteString;

class NationalTeamsController extends AbstractController
{

    public function __construct(private NationalTeamRepository $nationalTeamRepository, private EntityManagerInterface $entityManager, private PlayerRepository $playerRepository, private RequestStack $requestStack,
    )
    {

    }

    //show all national Team
    #[Route('/admin/national-teams', name: 'admin.national.index')]
    public function index():Response
    {
        $entities = $this->nationalTeamRepository->findAll();
        return $this->render('admin/nationalTeams/index.html.twig', [
            'entities'=>$entities
        ]);
    }

    //add or edit(name/flag) a national Team
    #[Route('/admin/national-teams/add/nationalTeam', name: 'admin.national.AddNationalTeam')]
    #[Route('/admin/national-teams/edit/nationalTeam/{name}', name: 'admin.national.editNationalTeam')]
    public function AddNationalTeam(string $name = null):Response
    {
        $type = NationalTeamType::class;
        $model = $name ? $this->nationalTeamRepository->findOneBy(['name'=>$name]) :new NationalTeam();

        $model->prevFlag = $name? $model->getDrapeau(): null;
        $form = $this->createForm($type,$model);
        $form->handleRequest($this->requestStack->getCurrentRequest());
        if($form->isSubmitted() && $form->isValid()){
            if($form['drapeau']->getData() instanceof UploadedFile){
              //  dd($form['drapeau']->getData());
                $file = $form['drapeau']->getData();
                $randName = ByteString::fromRandom(32)->lower();
                $fileExt= $file->guessClientExtension();
                $fullName = "$randName.$fileExt";

                $file->move('img/nationalFlags/', $fullName);
                $model->setDrapeau($fullName);

                if($name){
                   unlink("img/nationalFlags/{$model->prevFlag}" );
                }
            }else{
                $model->setDrapeau($model->prevFlag);
            }
            $this->entityManager->persist($model);
            $message = $name ? 'National Team Has been updated': 'New National Team has been added';
            $this->entityManager->flush();

            $this->addFlash('notice', $message);

            return $this->redirectToRoute('admin.national.index');

        }
        return $this->render('admin/nationalTeams/formAddNationalTeam.html.twig', [
            'form'=>$form->createView(),
            'name'=>$name,
        ]);
    }

    //remove national Team
    #[Route('/player/remove/national/{id}', name: 'admin.nationalTeam.form.remove')]
    public function  removeNationalTeam(int $id):Response
    {
        $entity = $this->nationalTeamRepository->find($id);
        $nationalTeam = $entity->getName();
        $allPlayers = $this->playerRepository->findAll(['nationalTeam'=>$nationalTeam]);
        foreach($allPlayers as $player){
            if($player->getNationalTeam() !=null &&$player->getNationalTeam()->getName() === $nationalTeam){
                $player->setNationalTeam(null);
            }
        }
        $this->entityManager->remove($entity);
        $this->entityManager->flush();

        $this->addFlash('notice', 'player has been removed');
        return $this->redirectToRoute('admin.national.index');
    }

    //see the national Team and all its players of the National team
    #[Route('/admin/national-team/{name}', name: 'admin.national.show')]
    public function show(string $name):Response
    {
        $array = [];
        $players = $this->nationalTeamRepository->findAll();
        foreach($players as $palyer ) {
            foreach ($palyer->getPlayers() as $pl){
                echo($pl->getNationalTeam()->getName());
                if ($pl->getNationalTeam()->getName() === $name){
                   array_push($array, $pl);
                  // array_push($array, ['name'=>$pl->getfirstname(), 'last'=>$pl->getlastname()]);
                }
            }
        }
        return $this->render('admin/nationalTeams/show.html.twig', [
            'array'=>$array,
            'players'=>$players,
            'name'=>$name,
        ]);
    }

    //add a player to this national Team
    //note that you dont have to select the national Team when you click on add(this field has been removed from thr form)
    //because if you are editing a national team so you want to add a player to this national team so it set the national
    //team of the player automatically to the current national team that you ara editing
    //if the player exists and he don't belong to no National Team it will only update his national Team from none to
    // the current national Team that you are editing else if the player does't existe yet in the players table it will
    //create the player in the table player and set his National Team as the current National Team that you are editing
    /**
     * @param string $name
     * @return Response
     */
    #[Route('/player/form/national/{name}/add', name: 'admin.player.form.national.add')]
    public function form(string $name): Response
    {

        $type = PlayerType::class;
        $players = $this->playerRepository->findAll();
        $model = new Player();
        $form = $this->createForm($type, $model);
        $form->handleRequest($this->requestStack->getCurrentRequest());

        $ArrayReferer = explode('/',  $this->requestStack->getCurrentRequest()->headers->get('referer'));
        $CurrentNationalTeam = end($ArrayReferer);
        //dd($CurrentNationalTeam);

        if($form->isSubmitted() && $form->isValid()){

            foreach($players as $player ){
                //dd($player->getNationalTeam(),$player->getLastname(),$form['lastname']->getData(), $form['lastname']->getData() === $player->getLastname(), $form['firstname']->getData() === $player->getfirstname());
                if($form['firstname']->getData() === $player->getfirstname()
                    && $form['lastname']->getData() === $player->getLastname()
                    && $player->getNationalTeam() ===null){
                    $player->setNationalTeam($this->nationalTeamRepository->findOneBy(['name' => $name]));
                    $this->entityManager->flush();
                    $this->addFlash('notice', 'Player has been updated');
                    return $this->redirectToRoute('admin.national.show',[
                        'name' =>$name,
                        ]);
                }
            }
                $file = $form['portrait']->getData();
                $randomName= ByteString::fromRandom(32)->lower();
                $fileExtention = $file->guessClientExtension();
                $fullFileName = "$randomName.$fileExtention";

                $model->setPortrait($fullFileName);
                $model->setNationalTeam($this->nationalTeamRepository->findOneBy(['name' => $name]));

                $file->move('img/', $fullFileName);
            $this->entityManager->persist($model);
            $this->entityManager->flush();

            $this->addFlash('notice', 'Player has been added');

            return $this->redirectToRoute('admin.national.show',[
                'name'=> $name,
            ]);
        }
        //dd($entities);
        return $this->render('admin/nationalTeams/formNational.html.twig',[
            'form'=> $form->createView(),
        ]);
    }


    // the finction remove dont remove the player from the table player it just set his national Team to none
    #[Route('/player/national/remove/{id}', name: 'admin.player.form.national.remove')]
    public function  remove(int $id):Response
    {
        $entity = $this->playerRepository->find($id);
        $name = $entity->getNationalTeam()->getName();
        $entity->setNationalTeam(null);
        $this->entityManager->flush();

        $this->name=$name;

        $this->addFlash('notice', 'player has been removed');
        return $this->redirectToRoute('admin.national.show',[
            'name' => $name,
        ]);
    }

}