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

    // #[Route('/insertTrajet', name: 'insert_trajet', methods: "POST")]
    // public function insertTrajet(Request $request, RideRepository $doctrine, EntityManagerInterface $entityManager): JsonResponse
    // {
    //     $fdate = date("d.m.y");
    //     $date = $fdate($request->query->get('date'));

    //     if ($date == null) {
    //         return new JsonResponse("Trajet vide", 404, [], true);
    //     } else {
    //         $trajet = new Ride();
    //         $trajet->setDate($date);
    //         $depart_city = $entityManager->getRepository(City::class)->findOneBy(["id" => $request->query->get("id_vd")]);
    //         $arrival_city = $entityManager->getRepository(City::class)->findOneBy(["id" => $request->query->get("id_va")]);
    //         $personne = $entityManager->getRepository(User::class)->findOneBy(["id" => $request->query->get("id_pers")]);
    //         $trajet->setStartCity($depart_city);
    //         $trajet->setArrivalCity($);
    //         $trajet->setConducteur($personne);
    //         $trajet->setKms($request->query->get('kms'));

    //         $entityManager->persist($trajet);
    //         $entityManager->flush();

    //         return new JsonResponse("Trajet ajouté", 200, [], true);
    //     }
    // }
}
