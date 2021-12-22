<?php

namespace App\Controller;

use App\Entity\NearMiss;
use Symfony\UX\Chartjs\Model\Chart;
use App\Repository\NearMissRepository;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChartjsController extends AbstractController
{
    /**
     * @Route("admin/chartjs", name="admin_chartjs")
     */
    public function index(NearMissRepository $nearmissRepo, ChartBuilderInterface $chartBuilder): Response
    {
        $label = [];
        $data = [];

        $date = new DateTimeImmutable();
        $d = $date->format('W');

        $nearmiss = $nearmissRepo->countNearmissEmploye();

        $nearmiss2 = $nearmissRepo->countByDate();
        //dd($nearmiss2);

        //$nearmiss = $nearmissRepo->nearmissByWeek($d - 3);

        foreach ($nearmiss as $nearmisses) {
            foreach ($nearmisses as $i => $value) {
                if ($i == "nomEmploye") {
                    $label[] = $value;
                } elseif ($i == "count") {
                    $data[] = $value;
                }
            }
        }

        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);
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
        ]);

        return $this->render('chartjs/index.html.twig', [
            'controller_name' => 'ChartjsController',
            'chart' => $chart,
        ]);
    }
}
