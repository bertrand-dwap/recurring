<?php

namespace App\Controller;

use App\Entity\Log;
use App\Entity\RecurringTask;
use App\Form\LogForm;
use App\Repository\LogRepository;
use App\Repository\RecurringTaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/log')]
final class LogController extends AbstractController
{
    #[Route(name: 'app_log_index', methods: ['GET'])]
    public function index(LogRepository $logRepository, RecurringTaskRepository $recurringTaskRepository, Request $request): Response
    {
        $recurringTaskId = $request->query->get('recurringTaskId');
        if ($recurringTaskId === null) {
            $this->addFlash('danger','Erreur dans la requête');
            return $this->redirectToRoute('app_recurring_task_index');
        }
        $recurringTask = $recurringTaskRepository->find($recurringTaskId);

        return $this->render('log/index.html.twig', [
            'logs' => $logRepository->findBy(
                [
                    'recurringTask' => $recurringTask,
                ],
                [
                    'date' => 'DESC',
                ],
            ),
            'recurringTask' => $recurringTask,
        ]);
    }

    #[Route('/new', name: 'app_log_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, RecurringTaskRepository $recurringTaskRepository): Response
    {
        $recurringTaskId = $request->query->get('id');
        $recurringTask = $entityManager->getRepository(RecurringTask::class)->find($recurringTaskId);
        $log = new Log();
        $log->setRecurringTask($recurringTask);
        $form = $this->createForm(LogForm::class, $log);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($log);
            $recurringTaskRepository->done($recurringTask);
            $entityManager->flush();

            return $this->redirectToRoute('app_recurring_task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('log/new.html.twig', [
            'log' => $log,
            'form' => $form,
            'recurringTask' => $recurringTask,
        ]);
    }

    #[Route('/{id}', name: 'app_log_show', methods: ['GET'])]
    public function show(Log $log): Response
    {
        return $this->render('log/show.html.twig', [
            'log' => $log,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_log_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Log $log, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LogForm::class, $log);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_log_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('log/edit.html.twig', [
            'log' => $log,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_log_delete', methods: ['POST'])]
    public function delete(Request $request, Log $log, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $log->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($log);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_log_index', [], Response::HTTP_SEE_OTHER);
    }
}
