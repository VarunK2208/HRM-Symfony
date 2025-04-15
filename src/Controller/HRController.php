<?php

namespace App\Controller;

use App\Document\User;
use App\Document\Attendance;
use App\Document\Payslip;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security; // ✅ CORRECT
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class HRController extends AbstractController
{
    #[Route('/hr/employees', name: 'hr_view_employees')]
    public function viewEmployees(DocumentManager $dm): Response
    {
        $employees = $dm->getRepository(User::class)->findBy(['role' => 'DEVELOPER']);
        return $this->render('hr/view_employees.html.twig', [
            'employees' => $employees,
        ]);
    }

    #[Route('/hr/employee/add', name: 'hr_add_employee')]
    public function addEmployee(Request $request, DocumentManager $dm): Response
    {
        if ($request->isMethod('POST')) {
            $data = $request->request;
            $user = new User();
            $user->setName($data->get('name'));
            $user->setEmail($data->get('email'));
            $user->setPassword(password_hash($data->get('password'), PASSWORD_BCRYPT));
            $user->setRole('DEVELOPER');

            $dm->persist($user);
            $dm->flush();

            return $this->redirectToRoute('hr_view_employees');
        }

        return $this->render('hr/add_employee.html.twig');
    }

    #[Route('/hr/employee/edit/{id}', name: 'hr_edit_employee')]
    public function editEmployee(Request $request, DocumentManager $dm, string $id): Response
    {
        $user = $dm->getRepository(User::class)->find($id);
        if (!$user) {
            throw $this->createNotFoundException('Employee not found');
        }

        if ($request->isMethod('POST')) {
            $data = $request->request;
            $user->setName($data->get('name'));
            $user->setEmail($data->get('email'));
            $dm->flush();

            return $this->redirectToRoute('hr_view_employees');
        }

        return $this->render('hr/edit_employee.html.twig', ['employee' => $user]);
    }

    #[Route('/hr/employee/delete/{id}', name: 'hr_delete_employee')]
    public function deleteEmployee(DocumentManager $dm, string $id): RedirectResponse
    {
        $user = $dm->getRepository(User::class)->find($id);
        if ($user) {
            $dm->remove($user);
            $dm->flush();
        }
        return $this->redirectToRoute('hr_view_employees');
    }

    #[Route('/hr/attendance', name: 'hr_view_attendance')]
    public function viewAttendance(DocumentManager $dm): Response
    {
        $records = $dm->getRepository(Attendance::class)->findAll();
        return $this->render('hr/view_attendance.html.twig', [
            'records' => $records,
        ]);
    }

    #[Route('/hr/attendance/{id}', name: 'hr_employee_attendance')]
    public function employeeAttendance(DocumentManager $dm, string $id): Response
    {
        $records = $dm->getRepository(Attendance::class)->findBy(['user.$id' => new \MongoDB\BSON\ObjectId($id)]);
        return $this->render('hr/employee_attendance.html.twig', [
            'records' => $records,
        ]);
    }

    #[Route('/hr/payslips', name: 'hr_view_payslips')]
    public function viewPayslips(DocumentManager $dm): Response
    {
        $payslips = $dm->getRepository(Payslip::class)->findAll();
        return $this->render('hr/view_payslips.html.twig', [
            'payslips' => $payslips,
        ]);
    }

    #[Route('/hr/payslips/{id}', name: 'hr_employee_payslips')]
    public function employeePayslips(DocumentManager $dm, string $id): Response
    {
        $records = $dm->getRepository(Payslip::class)->findBy(['user.$id' => new \MongoDB\BSON\ObjectId($id)]);
        return $this->render('hr/employee_payslips.html.twig', [
            'records' => $records,
        ]);
    }
}
?>