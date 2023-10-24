<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Car;
use App\Form\CarType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class CarController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'main')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $data = $entityManager->getRepository(Car::class)->findAll();
        return $this->render('Car/index.html.twig', [
            'list' => $data
        ]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function create(Request $request)
    {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($car);
            $this->entityManager->flush();
            $this->addFlash('notice', 'Create Successfully!!');

            return $this->redirectToRoute('main');
        }

        return $this->render('Car/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/update/{id}", name="update")
     */
    public function update(Request $request, $id){
        $car = $this->entityManager->getRepository(Car::class)->find($id);
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($car);
            $this->entityManager->flush();

            $this->addFlash('notice', 'Update Successfully!!');

            return $this->redirectToRoute('main');
        }

        return $this->render('Car/update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete($id){
        $data = $this->entityManager->getRepository(Car::class)->find($id);
        if (!$data) {
            throw $this->createNotFoundException('Car not found with ID ' . $id);
        }
    
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    
        $this->addFlash('notice', 'Deleted Successfully!!');
    
        return $this->redirectToRoute('main');
        
    }






}
