<?php

namespace App\Controller;

use App\Entity\LocationOption;
use App\Entity\LocationVehicule;
use App\Entity\Option;
use App\Form\LocationVehiculeType;
use App\Repository\LocationVehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/location')]
final class LocationVehiculeController extends AbstractController
{
    #[Route(name: 'app_location_vehicule_index', methods: ['GET'])]
    public function index(LocationVehiculeRepository $locationVehiculeRepository): Response
    {
        return $this->render('location_vehicule/index.html.twig', [
            'location_vehicules' => $locationVehiculeRepository->findBy([], ['dateDebut' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'app_location_vehicule_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $locationVehicule = new LocationVehicule();
        $form = $this->createForm(LocationVehiculeType::class, $locationVehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // La location doit avoir son id avant de créer les lignes location_option
            $entityManager->persist($locationVehicule);
            $entityManager->flush();

            $this->syncOptions($form, $locationVehicule, $entityManager);
            $entityManager->flush();

            return $this->redirectToRoute('app_location_vehicule_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('location_vehicule/new.html.twig', [
            'location_vehicule' => $locationVehicule,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_location_vehicule_show', methods: ['GET'])]
    public function show(LocationVehicule $locationVehicule): Response
    {
        return $this->render('location_vehicule/show.html.twig', [
            'location_vehicule' => $locationVehicule,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_location_vehicule_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LocationVehicule $locationVehicule, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LocationVehiculeType::class, $locationVehicule);
        $form->get('options')->setData(
            $locationVehicule->getLocationOptions()->map(fn (LocationOption $lo) => $lo->getOption())->toArray()
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->syncOptions($form, $locationVehicule, $entityManager);
            $entityManager->flush();

            return $this->redirectToRoute('app_location_vehicule_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('location_vehicule/edit.html.twig', [
            'location_vehicule' => $locationVehicule,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_location_vehicule_delete', methods: ['POST'])]
    public function delete(Request $request, LocationVehicule $locationVehicule, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$locationVehicule->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($locationVehicule);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_location_vehicule_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Ajoute les options cochées avec le tarif actuel et retire les options décochées.
     * Les options déjà présentes gardent leur prix appliqué d'origine (historique).
     */
    private function syncOptions(FormInterface $form, LocationVehicule $location, EntityManagerInterface $entityManager): void
    {
        /** @var Option[] $selected */
        $selected = $form->get('options')->getData();
        $selectedIds = array_map(fn (Option $o) => $o->getId(), is_array($selected) ? $selected : iterator_to_array($selected));

        $existingIds = [];
        foreach ($location->getLocationOptions() as $locationOption) {
            $optionId = $locationOption->getOption()->getId();
            if (!in_array($optionId, $selectedIds, true)) {
                $location->removeLocationOption($locationOption);
                $entityManager->remove($locationOption);
            } else {
                $existingIds[] = $optionId;
            }
        }

        foreach ($selected as $option) {
            if (!in_array($option->getId(), $existingIds, true)) {
                $locationOption = new LocationOption($location, $option, $option->getPrix());
                $location->addLocationOption($locationOption);
                $entityManager->persist($locationOption);
            }
        }
    }
}
