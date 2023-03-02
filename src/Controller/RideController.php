<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\User;
use App\Entity\Car;
use App\Entity\Ride;
use App\Repository\CarRepository;
use App\Repository\RideRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class RideController extends AbstractController
{
    #[Route('/listeTrajet', name: 'liste_trajet', methods: "GET")]
    public function listeTrajet(RideRepository $doctrine, SerializerInterface $serializerInterface): JsonResponse
    {
        $liste = $doctrine->findAll();
        $listeJson = $serializerInterface->serialize($liste, 'json');

        return new JsonResponse($listeJson, 200, [], true);
    }

    #[Route('/deleteTrajet/{id}', name: 'delete_trajet', methods: "DELETE")]
    public function deleteTrajet(int $id, RideRepository $doctrine, EntityManagerInterface $entityManager): JsonResponse
    {
        $trajet = $doctrine->find($id);

        if ($trajet) {
            $entityManager->remove($trajet);
            $entityManager->flush();

            return new JsonResponse("Trajet supprimé", 200, [], true);
        } else {
            return new JsonResponse("Trajet non trouvé", 404, [], true);
        }
    }

    #[Route('/insertTrajet', name: 'insert_trajet', methods: "POST")]

    public function insertTrajet(Request $request, RideRepository $doctrine, EntityManagerInterface $entityManager): JsonResponse
    {
        $date = $request->query->get('date');
        $departurehour = $request->query->get('departurehour');
        $departure = $request->query->get('id_departure');
        $arrival = $request->query->get('id_arrival');
        $user = $request->query->get('id_user');
        $kms = $request->query->get('kms');
        if ($date == null || $departurehour == null || $departure == null || $arrival == null || $user == null || $kms == null) {
            return new JsonResponse("Trajet vide", 404, [], true);
        } else {
            $trajet = new Ride;
            $trajet->setStartHour($departurehour);
            $trajet->setDeparture($departure);
            $trajet->setArrival($arrival);
            $trajet->setKms($kms);
            $personne = $entityManager->getRepository(User::class)->findOneBy(["id" => $user]);
            $trajet->setConducteur($personne);
            $entityManager->persist($trajet);
            $entityManager->flush();
            return new JsonResponse("Trajet ajouté", 200, [], true);
        }
    }
}
