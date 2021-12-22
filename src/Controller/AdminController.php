<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use DateTimeImmutable;
use App\Entity\Categorie;
use App\Entity\Year;
use App\Form\CategorieType;
use App\Form\YearType;
use App\Repository\UserRepository;
use App\Repository\EmployeRepository;
use App\Repository\ServiceRepository;
use App\Repository\NearMissRepository;
use App\Repository\TraitementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\Chartjs\Model\Chart;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="control", methods={"GET"})
     */
    public function index(UserRepository $userRepo, EmployeRepository $employeRepo, NearMissRepository $nearmissRepo, ChartBuilderInterface $chartBuilder): Response
    {
        $nearmiss = $nearmissRepo->findBy([]);
        $user = $userRepo->findBy([]);
        $employe = $employeRepo->findBy([]);

        $labelBar = [];
        $dataBar = [];

        $label = [];
        $data = [];

        $date = new DateTimeImmutable();
        $d = $date->format('W');

        $nearmissEm = $nearmissRepo->countNearmissEmploye();
        foreach ($nearmissEm as $nearmisses) {
            foreach ($nearmisses as $i => $value) {
                if ($i == "nomEmploye") {
                    $labelBar[] = $value;
                } elseif ($i == "count") {
                    $dataBar[] = $value;
                }
            }
        }


        //$nearmiss = $nearmissRepo->nearmissByWeek($d - 3);

        $nearmiss2 = $nearmissRepo->countByDate();
        foreach ($nearmiss2 as $nearmisses) {
            foreach ($nearmisses as $i => $value) {
                if ($i == "dateNearmiss") {
                    $label[] = "week " . $value;
                } elseif ($i == "count") {
                    $data[] = $value;
                }
            }
        }

        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => $label,
            'datasets' => [
                [
                    'label' => 'Near miss',
                    'fill' => false,
                    'backgroundColor' => '#75c181',
                    'borderColor' => '#75c181',
                    'data' => $data,
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'yAxes' => [
                    ['ticks' => ['min' => 0, 'max' => 40]],
                ],
            ],
            'responsive' => true,
            'aspectRatio' => 1.5,
            'legend' => [
                'display' => true,
                'position' => 'bottom',
                'align' => 'end'
            ],
            'title' => [
                'display' => true,
                'text' => 'Nombre de near miss par semaine'
            ],
            'tooltips' => [
                'mode' => 'index',
                'intersect' => false,
                'titleMarginBottom' => 10,
                'bodySpacing' => 10,
                'xPadding' => 16,
                'yPadding' => 16,
                'borderColor' => '#e7e9ed',
                'borderWidth' => 1,
                'backgroundColor' => '#fff',
                'bodyFontColor' => '#252930',
                'titleFontColor' => '#252930'
            ]
        ]);

        $chartBar = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chartBar->setData([
            'labels' => $labelBar,
            'datasets' => [
                [
                    'label' => 'Near miss',
                    'backgroundColor' => '#75c181',
                    'borderColor' => '#75c181',
                    'borderWidth' => 1,
                    'data' => $dataBar,
                ],
            ],
        ]);

        $chartBar->setOptions([
            'scales' => [
                'yAxes' => [
                    ['ticks' => ['min' => 0, 'max' => 40]],
                ],
            ],
            'responsive' => true,
            'aspectRatio' => 1.5,
            'legend' => [
                'position' => 'bottom',
                'align' => 'end'
            ],
            'title' => [
                'display' => true,
                'text' => 'Nombre de near miss des employés'
            ],
            'tooltips' => [
                'mode' => 'index',
                'intersect' => false,
                'titleMarginBottom' => 10,
                'bodySpacing' => 10,
                'xPadding' => 16,
                'yPadding' => 16,
                'borderColor' => '#e7e9ed',
                'borderWidth' => 1,
                'backgroundColor' => '#fff',
                'bodyFontColor' => '#252930',
                'titleFontColor' => '#252930'
            ]
        ]);


        return $this->render('admin/index.html.twig', compact('user', 'employe', 'nearmiss', 'chart', 'chartBar'));
    }

    /**
     * @Route("/user/edit/{id<[0-9]+>}", name="user_edit", methods={"GET","POST"})
     */
    public function editUser(User $user, EntityManagerInterface $em, Request $request): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'User successfully updated');

            return $this->redirectToRoute('admin_user_list');
        }
        return $this->render('registration/edituser.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/{id<[0-9]+>}/delete", name="user_delete", methods="GET")
     */
    public function deleteUser(EntityManagerInterface $em, User $user, TraitementRepository $traitementRepo, NearMissRepository $nearmissRepo): Response
    {
        $traitement = $traitementRepo->findBy(['user' => $user]);
        //dd($traitement);
        foreach ($traitement as $value) {
            $em->remove($value);
        }
        $em->remove($user);
        $em->flush();

        $this->addFlash('info', 'User deleted successfully');

        return $this->redirectToRoute('admin_user');
    }

    /**
     * @Route("/categorie/add", name="categorie_add", methods={"GET", "POST"})
     */
    public function addCategorie(Request $request, EntityManagerInterface $em): Response
    {
        $categorie = new Categorie;

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($categorie);
            $em->flush();

            $this->addFlash('info', 'Categorie successfully added');
            return $this->redirectToRoute('admin_categorie_add');
        }

        return $this->render('admin/categorie.html.twig', [
            'categorie' => $categorie,
            'formCategorie' => $form->createView()
        ]);
    }

    /**
     * @Route("/categorie/{id<[0-9]+>}/edit", name="categorie_edit", methods={"GET","POST"})
     */
    public function editCategorie(Request $request, Categorie $categorie, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('info', 'Categorie successfully updated');
            return $this->redirectToRoute('admin_user');
        }
        return $this->render('admin/categorie_edit.html.twig', [
            'formCategorie' => $form->createView()
        ]);
    }

    /**
     * @Route("/employe/list", name="employe_list", methods={"GET"})
     */
    public function employeList(EmployeRepository $employRepo, NearMissRepository $nearmissRepo): Response
    {
        $date = new DateTimeImmutable();
        $d = $date->format('W');
        $employe = $employRepo->findBy([]);
        $nearmiss = $nearmissRepo->nearmissByWeek($d);
        //dd($nearmiss);
        return $this->render('admin/employeList.html.twig', compact('employe', 'nearmiss'));
    }

    /**
     * @Route("/nearmiss/selection/OHSE", name="nearmiss_ohse", methods={"GET","POST"})
     */
    public function selectionOHSE(NearMissRepository $nearmissRepo, ServiceRepository $serviceRepo, EmployeRepository $employeRepo): Response
    {
        $service = $serviceRepo->findOneBy(['nomService' => 'OHSE']);
        //dd($service);
        $employe = $employeRepo->findBy(['service' => $service]);
        //dd($employe);
        $nearmiss = $nearmissRepo->findBy(['employe' => $employe], ['createdAt' => 'DESC']);
        //dd($nearmiss);
        return $this->render('admin/service.html.twig', compact('nearmiss', 'service'));
    }

    /**
     * @Route("/nearmiss/selection/maintenance", name="nearmiss_maintenance", methods={"GET","POST"})
     */
    public function selectionMaintenance(NearMissRepository $nearmissRepo, ServiceRepository $serviceRepo, EmployeRepository $employeRepo): Response
    {
        $service = $serviceRepo->findOneBy(['nomService' => 'Maintenance']);
        //dd($service);
        $employe = $employeRepo->findBy(['service' => $service]);
        //dd($employe);
        $nearmiss = $nearmissRepo->findBy(['employe' => $employe], ['createdAt' => 'DESC']);
        //dd($nearmiss);
        return $this->render('admin/service.html.twig', compact('nearmiss', 'service'));
    }

    /**
     * @Route("/nearmiss/selection/qualite", name="nearmiss_qualite", methods={"GET","POST"})
     */
    public function selectionQualite(NearMissRepository $nearmissRepo, ServiceRepository $serviceRepo, EmployeRepository $employeRepo): Response
    {
        $service = $serviceRepo->findOneBy(['nomService' => 'Qualité']);
        //dd($service);
        $employe = $employeRepo->findBy(['service' => $service]);
        //dd($employe);
        $nearmiss = $nearmissRepo->findBy(['employe' => $employe], ['createdAt' => 'DESC']);
        //dd($nearmiss);
        return $this->render('admin/service.html.twig', compact('nearmiss', 'service'));
    }

    /**
     * @Route("/nearmiss/selection/production", name="nearmiss_production", methods={"GET","POST"})
     */
    public function selectionProduction(NearMissRepository $nearmissRepo, ServiceRepository $serviceRepo, EmployeRepository $employeRepo): Response
    {
        $service = $serviceRepo->findOneBy(['nomService' => 'Production']);
        //dd($service);
        $employe = $employeRepo->findBy(['service' => $service]);
        //dd($employe);
        $nearmiss = $nearmissRepo->findBy(['employe' => $employe], ['createdAt' => 'DESC']);
        //dd($nearmiss);
        return $this->render('admin/service.html.twig', compact('nearmiss', 'service'));
    }

    /**
     * @Route("/nearmiss/selection/magasin", name="nearmiss_magasin", methods={"GET","POST"})
     */
    public function selectionMagasin(NearMissRepository $nearmissRepo, ServiceRepository $serviceRepo, EmployeRepository $employeRepo): Response
    {
        $service = $serviceRepo->findOneBy(['nomService' => 'Magasin']);
        //dd($service);
        $employe = $employeRepo->findBy(['service' => $service]);
        //dd($employe);
        $nearmiss = $nearmissRepo->findBy(['employe' => $employe], ['createdAt' => 'DESC']);
        //dd($nearmiss);
        return $this->render('admin/service.html.twig', compact('nearmiss', 'service'));
    }

    /**
     * @Route("/nearmiss/selection/administration", name="nearmiss_administration", methods={"GET","POST"})
     */
    public function selectionAdministration(NearMissRepository $nearmissRepo, ServiceRepository $serviceRepo, EmployeRepository $employeRepo): Response
    {
        $service = $serviceRepo->findOneBy(['nomService' => 'Administration']);
        //dd($service);
        $employe = $employeRepo->findBy(['service' => $service]);
        //dd($employe);
        $nearmiss = $nearmissRepo->findBy(['employe' => $employe]);
        //dd($nearmiss);
        return $this->render('admin/service.html.twig', compact('nearmiss', 'service'));
    }


    /**
     * @Route("/employe/add", name="employe_add", methods={"GET","POST"})
     */
    public function addEmploye()
    {
    }

    /**
     * @Route("/year/add", name="year_add", methods={"GET","POST"})
     */
    public function addYear(EntityManagerInterface $em, Request $request)
    {
        $year = new Year();
        $form = $this->createForm(YearType::class, $year);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($year);
            $em->flush();

            return $this->redirectToRoute('admin_year_add');
        }
        return $this->render('admin/yearAdd.html.twig', [
            'formYear' => $form->createView()
        ]);
    }
}
