<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Factory;
use App\Form\FactoryType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;



class FactoryController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/factory', name: 'app_factory')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $data = $entityManager->getRepository(Factory::class)->findAll();
        return $this->render('factory/index.html.twig', [
            'list' => $data
        ]);
    }

    /**
     * @Route("/createf", name="create")
     */
    public function create(Request $request)
    {
        $factory = new Factory();
        $form = $this->createForm(\App\Form\FactoryType::class, $factory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($factory);
            $this->entityManager->flush();
            $this->addFlash('notice', 'Create Successfully!!');

            return $this->redirectToRoute('app_factory');
        }

        return $this->render('factory/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/updatefactory/{id}", name="update")
     */
    public function update(Request $request, $id){
        
        $factory = $this->entityManager->getRepository(Factory::class)->find($id);
        $form = $this->createForm(FactoryType::class, $factory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($factory);
            $this->entityManager->flush();

            $this->addFlash('notice', 'Update Successfully!!');

            return $this->redirectToRoute('app_factory');
        }

        return $this->render('factory/update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/deletefactory/{id}", name="delete")
     */
    public function delete($id){
        $data = $this->entityManager->getRepository(Factory::class)->find($id);
        if (!$data) {
            throw $this->createNotFoundException('Factory not found with ID ' . $id);
        }
    
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    
        $this->addFlash('notice', 'Deleted Successfully!!');
    
        return $this->redirectToRoute('app_factory');
        
    }






}
