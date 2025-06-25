<?php

namespace App\Controller;

use App\Entity\LinkedFile;
use App\Entity\RecurringTask;
use App\Form\RecurringTaskForm;
use App\Repository\RecurringTaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
final class RecurringTaskController extends AbstractController
{
    #[Route(name: 'app_recurring_task_index', methods: ['GET'])]
    public function index(RecurringTaskRepository $recurringTaskRepository): Response
    {
        return $this->render('recurring_task/index.html.twig', [
            'recurring_tasks' => $recurringTaskRepository->getList(),
        ]);
    }

    #[Route('/new', name: 'app_recurring_task_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recurringTask = new RecurringTask();
        $form = $this->createForm(RecurringTaskForm::class, $recurringTask, [
            'existing_files' => [],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($recurringTask);
            // Ajout
            $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $uploads = $form->get('uploads')->getData();
            foreach ($uploads as $upload) {
                /** @var UploadedFile $upload */
                $linkedFile = new LinkedFile();
                $linkedFile
                    ->setRecurringTask($recurringTask)
                    ->setOriginalName($upload->getClientOriginalName())
                    ->setMimeType($upload->getMimeType())
                    ->setUniqid(uniqid())
                ;
                $upload->move($uploadDir, $linkedFile->getUniqid());
                $recurringTask->addLinkedFile($linkedFile);
                $entityManager->persist($linkedFile);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_recurring_task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recurring_task/new.html.twig', [
            'recurring_task' => $recurringTask,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recurring_task_show', methods: ['GET'])]
    public function show(RecurringTask $recurringTask): Response
    {
        return $this->render('recurring_task/show.html.twig', [
            'recurring_task' => $recurringTask,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recurring_task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RecurringTask $recurringTask, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecurringTaskForm::class, $recurringTask, [
            'existing_files' => $recurringTask->getLinkedFiles()->toArray(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Suppression
            foreach ($form->get('remove_files')->getData() as $id => $checked) {
                if ($checked) {
                    $linkedFile = $recurringTask->getLinkedFiles()->filter(fn(LinkedFile $f) => $f->getId() == $id)->first();
                    if ($linkedFile) {
                        $recurringTask->removeLinkedFile($linkedFile);
                        unlink('uploads/' . $linkedFile->getUniqid());
                    }
                }
            }
            // Ajout
            $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $uploads = $form->get('uploads')->getData();
            foreach ($uploads as $upload) {
                /** @var UploadedFile $upload */
                $linkedFile = new LinkedFile();
                $linkedFile
                    ->setRecurringTask($recurringTask)
                    ->setOriginalName($upload->getClientOriginalName())
                    ->setMimeType($upload->getMimeType())
                    ->setUniqid(uniqid())
                ;
                $upload->move($uploadDir, $linkedFile->getUniqid());
                $recurringTask->addLinkedFile($linkedFile);
                $entityManager->persist($linkedFile);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_recurring_task_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recurring_task/edit.html.twig', [
            'recurring_task' => $recurringTask,
            'form' => $form,
            'existing_files' => $recurringTask->getLinkedFiles()->toArray(),
        ]);
    }

    #[Route('/{id}/procrastinate', name: 'app_recurring_task_procrastinate', methods: ['POST'])]
    public function procrastinate(Request $request, RecurringTask $recurringTask, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('procrastinate' . $recurringTask->getId(), $request->getPayload()->getString('_token'))) {
            $recurringTask->setProcrastinated(new \DateTime('tomorrow'));
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recurring_task_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_recurring_task_delete', methods: ['POST'])]
    public function delete(Request $request, RecurringTask $recurringTask, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $recurringTask->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($recurringTask);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recurring_task_index', [], Response::HTTP_SEE_OTHER);
    }
}
