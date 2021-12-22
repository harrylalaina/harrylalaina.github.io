<?php

namespace App\Controller;

use App\Entity\NearMiss;
use App\Entity\Traitement;
use App\Form\NearmissType;
use App\Repository\CategorieRepository;
use App\Repository\EmployeRepository;
use App\Repository\NearMissRepository;
use App\Repository\NiveauRepository;
use App\Repository\ServiceRepository;
use App\Repository\StatusRepository;
use App\Repository\TraitementRepository;
use App\Repository\UserRepository;
use App\Repository\YearRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class NearmissController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods="GET")
     */
    public function index(NearMissRepository $nearMissRepo, EmployeRepository $employeRepo, StatusRepository $statusRepository, NearMissRepository $nearmissRepo, EntityManagerInterface $em): Response
    {

        $date = new DateTimeImmutable();
        $d = $date->format('Y-m-j');
        $newDate = date('F', strtotime("$d + 4 month"));
        //$date = $newDate;

        $nearmissInterval = $nearMissRepo->selectInterval("2021-11-01", "2021-11-30");

        $nearmiss = $nearMissRepo->findBy([], ['createdAt' => 'DESC']);
        foreach ($nearmiss as $value) {
            $d1 = $value->getClosedAt();
            //$interval = date_diff($date, $d1);
            //dd($interval, $value);
        }

        $collectionNear = $nearmissRepo->traitementClosetAt($d);

        $status = $statusRepository->findOneBy(['typeStatus' => 'canceled']);

        foreach ($collectionNear as $value) {
            if ($value->getStatus()->getTypeStatus() == 'non traité' || $value->getStatus()->getTypeStatus() == 'en attente') {
                $value->setStatus($status);
                $em->flush();
            }
        }

        return $this->render('nearmiss/index.html.twig', compact('nearmiss', 'collectionNear'));
    }

    /**
     * @Route("/traitement", name="app_traitement", methods="GET")
     */
    public function traitementNearmiss(NearMissRepository $nearMissRepo, EmployeRepository $employeRepo): Response
    {
        $nearmiss = $nearMissRepo->findBy([], ['createdAt' => 'DESC']);

        return $this->render('nearmiss/nearmissListTraitement.html.twig', compact('nearmiss'));
    }

    /**
     * @Route("/nearmiss/create/new", name="app_nearmiss_create", methods={"GET", "POST"})
     */
    public function create(Request $request, EntityManagerInterface $em, YearRepository $yearRepo, EmployeRepository $employeRepo, StatusRepository $statusRepo, NiveauRepository $niveauRepo): Response
    {
        $nearmiss = new NearMiss;
        $date = new DateTimeImmutable();

        $form = $this->createForm(NearmissType::class, $nearmiss);
        $form->handleRequest($request);

        $year = $yearRepo->findBy([]);
        $status = $statusRepo->findOneBy(['typeStatus' => 'non traité']);
        $statusValidated = $statusRepo->findOneBy(['typeStatus' => 'en attente']);
        if ($form->isSubmitted() && $form->isValid()) {
            $employe = $employeRepo->findOneByName($nearmiss->getEmploye()->getName());
            if ($employe != null) {
                $nearmiss->setEmploye($employe);
            }
            if ($nearmiss->getNiveau()->getTypeNiveau() == 'Niveau 2' || $nearmiss->getNiveau()->getTypeNiveau() == 'Niveau 3') {
                $nearmiss->setStatus($statusValidated);
            } else {
                $nearmiss->setStatus($status);
            }
            foreach ($year as $value) {
                if ($date >= $value->getDebut() && $date <= $value->getFin()) {
                    $nearmiss->setYear($value);
                }
            }
            $em->persist($nearmiss);
            $em->flush();

            $this->addFlash('success', 'Near miss successfully created');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('nearmiss/create.html.twig', [
            'formNearmiss' => $form->createView()
        ]);
    }

    /**
     * @Route("/nearmiss/{id<[0-9]+>}", name="app_nearmiss_show", methods="GET")
     */
    public function show(NearMiss $nearmiss, TraitementRepository $traitementRepo, UserRepository $userRepo): Response
    {
        $traitement = $traitementRepo->findBy([]);
        $user = $userRepo->findBy([]);
        foreach ($user as $value) {
            $users = $value->getRoles();
            //dd($users);
        }

        return $this->render('nearmiss/showNearmiss.html.twig', compact('nearmiss', 'traitement', 'user'));
    }

    /**
     * @Route("/nearmiss/{id<[0-9]+>}/validated", name="app_nearmiss_validation", methods={"GET","POST"})
     */
    public function validate(NearMiss $nearmiss, StatusRepository $statusRepo, EntityManagerInterface $em)
    {
        $status = $statusRepo->findOneBy(['typeStatus' => 'non traité']);
        $nearmiss->setStatus($status);

        $em->flush();
        return $this->render('nearmiss/validation.html.twig');
    }

    /**
     * @Route("/show/categorie", name="app_categorie_show", methods="GET")
     */
    public function showCategorie(CategorieRepository $categorieRepo): Response
    {
        $categorie = $categorieRepo->findBy([]);
        return $this->render('layouts/categorie.html.twig', compact('categorie'));
    }

    /**
     * @Route("/profile/nearmiss/traitement/{id<[0-9]+>}", name="app_nearmiss_traitement", methods="GET")
     */
    public function traitement(NearMiss $nearmiss, StatusRepository $statusRepo, EntityManagerInterface $em): Response
    {
        $traitement = new Traitement;
        $traitement->setUser($this->getUser());
        $traitement->setNearmiss($nearmiss);
        $status = $statusRepo->findOneBy(['typeStatus' => 'Traité']);
        $nearmiss->setStatus($status);
        $em->persist($nearmiss);
        $em->persist($traitement);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/admin/nearmiss/traitement/{id<[0-9]+>}", name="admin_nearmiss_traitement", methods="GET")
     */
    public function traitementAdmin(NearMiss $nearmiss, StatusRepository $statusRepo, EntityManagerInterface $em): Response
    {
        $traitement = new Traitement;
        $traitement->setUser($this->getUser());
        $traitement->setNearmiss($nearmiss);
        $status = $statusRepo->findOneBy(['typeStatus' => 'Traité']);
        $nearmiss->setStatus($status);
        $em->persist($nearmiss);
        $em->persist($traitement);
        $em->flush();

        return $this->redirectToRoute('admin_nearmiss_list');
    }

    /**
     * @Route("/admin/nearmiss/{id<[0-9]+>}/edit", name="app_nearmiss_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, NearMiss $nearmiss, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(NearmissType::class, $nearmiss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Near miss successfully updated');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('nearmiss/edit.html.twig', [
            'nearmiss' => $nearmiss,
            'formNearmiss' => $form->createView()
        ]);
    }

    /**
     * @Route("/nearmiss/{id<[0-9]+>}/delete", name="app_nearmiss_delete", methods={"GET"})
     */
    public function delete(NearMiss $nearmiss, EntityManagerInterface $em): Response
    {

        $em->remove($nearmiss);
        $em->flush();

        $this->addFlash('info', 'Near miss successfully deleted');

        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/nearmiss/selection/ohse", name="app_nearmiss_ohse", methods={"GET","POST"})
     */
    public function selectionOHSE(NearMissRepository $nearmissRepo, ServiceRepository $serviceRepo, EmployeRepository $employeRepo): Response
    {
        $service = $serviceRepo->findOneBy(['nomService' => 'OHSE']);
        //dd($service);
        $employe = $employeRepo->findBy(['service' => $service]);
        //dd($employe);
        $nearmiss = $nearmissRepo->findBy(['employe' => $employe], ['createdAt' => 'DESC']);
        //dd($nearmiss);
        return $this->render('nearmiss/selection/ohse.html.twig', compact('nearmiss', 'service'));
    }

    /**
     * @Route("/nearmiss/selection/maintenance", name="app_nearmiss_maintenance", methods={"GET","POST"})
     */
    public function selectionMaintenance(NearMissRepository $nearmissRepo, ServiceRepository $serviceRepo, EmployeRepository $employeRepo): Response
    {
        $service = $serviceRepo->findOneBy(['nomService' => 'Maintenance']);
        //dd($service);
        $employe = $employeRepo->findBy(['service' => $service]);
        //dd($employe);
        $nearmiss = $nearmissRepo->findBy(['employe' => $employe], ['createdAt' => 'DESC']);
        //dd($nearmiss);
        return $this->render('nearmiss/selection/ohse.html.twig', compact('nearmiss', 'service'));
    }

    /**
     * @Route("/nearmiss/selection/qualite", name="app_nearmiss_qualite", methods={"GET","POST"})
     */
    public function selectionQualite(NearMissRepository $nearmissRepo, ServiceRepository $serviceRepo, EmployeRepository $employeRepo): Response
    {
        $service = $serviceRepo->findOneBy(['nomService' => 'Qualité']);
        //dd($service);
        $employe = $employeRepo->findBy(['service' => $service]);
        //dd($employe);
        $nearmiss = $nearmissRepo->findBy(['employe' => $employe], ['createdAt' => 'DESC']);
        //dd($nearmiss);
        return $this->render('nearmiss/selection/ohse.html.twig', compact('nearmiss', 'service'));
    }

    /**
     * @Route("/nearmiss/selection/production", name="app_nearmiss_production", methods={"GET","POST"})
     */
    public function selectionProduction(NearMissRepository $nearmissRepo, ServiceRepository $serviceRepo, EmployeRepository $employeRepo): Response
    {
        $service = $serviceRepo->findOneBy(['nomService' => 'Production']);
        //dd($service);
        $employe = $employeRepo->findBy(['service' => $service]);
        //dd($employe);
        $nearmiss = $nearmissRepo->findBy(['employe' => $employe], ['createdAt' => 'DESC']);
        //dd($nearmiss);
        return $this->render('nearmiss/selection/ohse.html.twig', compact('nearmiss', 'service'));
    }

    /**
     * @Route("/nearmiss/selection/magasin", name="app_nearmiss_magasin", methods={"GET","POST"})
     */
    public function selectionMagasin(NearMissRepository $nearmissRepo, ServiceRepository $serviceRepo, EmployeRepository $employeRepo): Response
    {
        $service = $serviceRepo->findOneBy(['nomService' => 'Magasin']);
        //dd($service);
        $employe = $employeRepo->findBy(['service' => $service]);
        //dd($employe);
        $nearmiss = $nearmissRepo->findBy(['employe' => $employe], ['createdAt' => 'DESC']);
        //dd($nearmiss);
        return $this->render('nearmiss/selection/ohse.html.twig', compact('nearmiss', 'service'));
    }

    /**
     * @Route("/nearmiss/selection/administration", name="app_nearmiss_administration", methods={"GET","POST"})
     */
    public function selectionAdministration(NearMissRepository $nearmissRepo, ServiceRepository $serviceRepo, EmployeRepository $employeRepo): Response
    {
        $service = $serviceRepo->findOneBy(['nomService' => 'Administration']);
        //dd($service);
        $employe = $employeRepo->findBy(['service' => $service]);
        //dd($employe);
        $nearmiss = $nearmissRepo->findBy(['employe' => $employe]);
        //dd($nearmiss);
        return $this->render('nearmiss/selection/ohse.html.twig', compact('nearmiss', 'service'));
    }

    /**
     * @Route("admin/nearmiss_list", name="admin_nearmiss_list", methods={"GET"})
     */
    public function nearmissList(NearMissRepository $nearmissRepo): Response
    {
        $nearmiss = $nearmissRepo->findBy([], ['createdAt' => 'DESC']);
        return $this->render('nearmiss/nearmissList.html.twig', compact('nearmiss'));
    }

    /**
     * @Route("admin/nearmiss/suivi", name="admin_nearmiss_suivi", methods={"GET"})
     */
    public function suiviNearmiss(NearMissRepository $nearmissRepo): Response
    {
        $nearmiss = $nearmissRepo->findBy([], ['createdAt' => 'DESC']);
        return $this->render('nearmiss/suiviNearmiss.html.twig', compact('nearmiss'));
    }

    /**
     * @Route("/error/403", name="app_error_403")
     */
    public function errorPage403()
    {
        return $this->render('error/403.html.twig');
    }

    /**
     * @Route("/error/500", name="app_error_500")
     */
    public function errorPage500()
    {
        return $this->render('error/500.html.twig');
    }
}
