<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/hr/dashboard', name: 'hr_dashboard')]
    public function hrDashboard(): Response
    {
        // Optionally, check the role to restrict access
        $this->denyAccessUnlessGranted('ROLE_HR');

        return $this->render('dashboard/hr.html.twig');
    }


    #[Route('/employee/dashboard', name: 'developer_dashboard')]
    public function employeeDashboard(): Response
    {

        $this->denyAccessUnlessGranted('ROLE_DEVELOPER');
        return $this->render('dashboard/developer.html.twig');

    }

}
?>