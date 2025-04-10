<?php

namespace App\Controller;

use App\Document\User;
use App\Document\Attendance;
use App\Document\Payslip;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class EmployeeController extends AbstractController
{
    #[Route('/employee/profile/edit', name: 'employee_profile_edit')]
    public function editProfile(Request $request, DocumentManager $dm, Security $security): Response
    {
        $user = $security->getUser();

        if ($request->isMethod('POST')) {
            $user->setName($request->request->get('name'));
            $user->setEmail($request->request->get('email'));
            $dm->flush();

            $this->addFlash('success', 'Profile updated successfully!');
            return $this->redirectToRoute('employee_profile_edit');
        }

        return $this->render('employee/edit_profile.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/employee/attendance', name: 'employee_attendance')]
    public function viewAttendance(Security $security, DocumentManager $dm): Response
    {
        $user = $security->getUser();

        $attendanceRecords = $dm->getRepository(Attendance::class)->findBy(['user' => $user]);



        return $this->render('employee/attendance.html.twig', [
            'user' => $user,
            'attendance' => $attendanceRecords,
        ]);
    }

    #[Route('/employee/payslips', name: 'employee_payslips')]
    public function viewPayslips(Security $security, DocumentManager $dm): Response
    {
        $user = $security->getUser();

        $payslips = $dm->getRepository(Payslip::class)->findBy(['user' => $user]);

        return $this->render('employee/payslips.html.twig', [
            'user' => $user,
            'payslips' => $payslips,
        ]);
    }
    #[Route('/test-insert-attendance', name: 'test_insert_attendance')]
public function testInsertAttendance(DocumentManager $dm, Security $security): Response
{
    $user = $security->getUser();

    $attendance = new \App\Document\Attendance();
    $attendance->setUser($user);
    $attendance->setDate(new \DateTime('2025-04-10'));
    $attendance->setStatus('Present');

    $dm->persist($attendance);
    $dm->flush();

    return new Response('Test attendance inserted successfully!');
}
#[Route('/test-insert-payslip', name: 'test_insert_payslip')]
public function testInsertPayslip(DocumentManager $dm, Security $security): Response
{
    $user = $security->getUser();

    $payslip = new \App\Document\Payslip();
    $payslip->setUser($user);
    $payslip->setMonth('April 2025');
    $payslip->setAmount('₹1000');

    $dm->persist($payslip);
    $dm->flush();

    return new Response('Test payslip inserted successfully!');
}


}
