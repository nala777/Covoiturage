<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Ride;
use App\Entity\City;
use App\Entity\Car;
use App\Repository\CarRepository;
use App\Repository\RideRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\SerializerInterface;

class RideController extends AbstractController
{
    #[Route('/listeTrajet', name: 'liste_trajet', methods: "GET")]
    public function listeTrajet(RideRepository $doctrine, SerializerInterface $serializerInterface): JsonResponse
    {
        $liste = $doctrine->findAll();
        $listeJson = $serializerInterface->serialize($liste, 'json', ['groups' => 'GetRide']);

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
        $departurehour = $request->query->get('departurehour');
        $dh = DateTime::createFromFormat('d-m-Y H:i', $departurehour);
        $departure = $entityManager->getRepository(City::class)->findOneBy(["id" => $request->query->get("id_departure")]);
        $arrival = $entityManager->getRepository(City::class)->findOneBy(["id" => $request->query->get("id_arrival")]);
        $user = $entityManager->getRepository(User::class)->findOneBy(["id" => $request->query->get('id_user')]);
        $kms = $request->query->get('kms');
        if (!$departurehour || !$departure || !$arrival || !$user || !$kms) {
            return new JsonResponse("Certains champs sont vides", 404, [], true);
        } else if (!$departure || !$arrival) {
            return new JsonResponse("Ville non trouvé ou vide", 404, [], true);
        } else if ($kms < 0) {
            return new JsonResponse("Distance invalide", 404, [], true);
        } else if (!$dh) {
            return new JsonResponse("Date invalide", 404, [], true);
        } else {

            $trajet = new Ride;
            $trajet->setStartHour($dh);
            $trajet->setStartCity($departure);
            $trajet->setArrivalCity($arrival);
            $trajet->setKms($kms);
            $personne = $entityManager->getRepository(User::class)->findOneBy(["id" => $user]);
            $place_avalable = $entityManager->getRepository(Car::class)->findOneBy(["user_id" => $personne])->getPlaces();
            $trajet->setPlaceAvailable($place_avalable);
            $trajet->setConducteur($personne);
            $entityManager->persist($trajet);
            $entityManager->flush();
            return new JsonResponse("Trajet ajouté", 200, [], true);
        }
    }
}
