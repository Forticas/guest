<?php

namespace App\Controller;

use App\Entity\Guest;
use App\Form\GuestType;
use App\Repository\GuestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class GuestController extends AbstractController
{
    #[Route('/', name: 'app_guest_index', methods: ['GET', 'POST'])]
    public function index(Request $request, GuestRepository $guestRepository): Response
    {

        $guests = $guestRepository->findAll();
        // count from $guests guests who are accepted
        $accepted = 0;
        $pendings = 0;
        $rejected = 0;
        foreach ($guests as $guest) {
            if ($guest->getStatus() == 'Accepted') {
                $accepted++;
            } elseif ($guest->getStatus() == 'Pending') {
                $pendings++;
            } elseif ($guest->getStatus() == 'Rejected') {
                $rejected++;
            }
        }

        // count from $guests guests who their owner username is houssem
        $houssem = 0;
        $zbaida = 0;
        foreach ($guests as $guest) {
            if ($guest->getOwner()->getUsername() == 'houssem') {
                $houssem++;
            }elseif ($guest->getOwner()->getUsername() == 'zbaida') {
                $zbaida++;
            }
        }


        $guest = new Guest();
        $form = $this->createForm(GuestType::class, $guest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $guest->setOwner($this->getUser());
            $guestRepository->add($guest, true);

            return $this->redirectToRoute('app_guest_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('guest/index.html.twig', [
            'guests' => $guests,
            // count Pendings guests
            'pending_guests' => $pendings,
            // count Accepted guests
            'accepted_guests' => $accepted,
            // count Rejected guests
            'rejected_guests' => $rejected,
            'houssem_guests' => $houssem,
            'zbaida_guests' => $zbaida,
            'guest' => $guest,
            'form' => $form,
        ]);
    }

    #[Route('/guest/new', name: 'app_guest_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GuestRepository $guestRepository): Response
    {
        $guest = new Guest();
        $form = $this->createForm(GuestType::class, $guest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $guestRepository->add($guest, true);

            return $this->redirectToRoute('app_guest_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('guest/new.html.twig', [
            'guest' => $guest,
            'form' => $form,
        ]);
    }

    #[Route('/guest/{id}', name: 'app_guest_show', methods: ['GET'])]
    public function show(Guest $guest): Response
    {
        return $this->render('guest/show.html.twig', [
            'guest' => $guest,
        ]);
    }

    #[Route('/guest/{id}/edit', name: 'app_guest_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Guest $guest, GuestRepository $guestRepository): Response
    {
        $form = $this->createForm(GuestType::class, $guest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $guestRepository->add($guest, true);

            return $this->redirectToRoute('app_guest_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('guest/edit.html.twig', [
            'guest' => $guest,
            'form' => $form,
        ]);
    }

    #[Route('/guest/{id}', name: 'app_guest_delete', methods: ['POST'])]
    public function delete(Request $request, Guest $guest, GuestRepository $guestRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $guest->getId(), $request->request->get('_token'))) {
            $guestRepository->remove($guest, true);
        }

        return $this->redirectToRoute('app_guest_index', [], Response::HTTP_SEE_OTHER);
    }
}
