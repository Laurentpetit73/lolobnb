<?php

namespace App\Controller;

use App\Service\Stats;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function index(EntityManagerInterface $manager, Stats $stats)
    {
        $statsData = $stats->getStats();
        $bestAds = $stats->getAdsStats();
        $worstAds = $stats->getAdsStats('ASC');

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $statsData,
            'bestAds' => $bestAds,
            'worstAds' => $worstAds
        ]);
    }
}
