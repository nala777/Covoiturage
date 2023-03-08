<?php

namespace App\Controller;

use App\Repository\CarRepository;
use App\Repository\RideRepository;
use OpenApi\Serializer;
use App\Entity\User;
use App\Entity\Ride;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class RerservationController extends AbstractController
{
    #[Route('/insertInscription', name: 'insertInscription', methods: "POST")]
    public function insertIncription(Request $request, SerializerInterface $serializerInterface, EntityManagerInterface $entitymanager): JsonResponse
    {
        $idpers = $request->query->get('id_pers');
        $idride = $request->query->get('id_ride');

        $pers = $entitymanager->getRepository(User::class)->findOneBy(["id" => $idpers]);
        $trajet = $entitymanager->getRepository(Ride::class)->findOneBy(["id" => $idride]);
        if (!$trajet) {
            return new JsonResponse("Trajet non trouvé", 404, [], true);
        } else if ($trajet->getPlaceAvailable() > 0) {
            $trajet->setPlaceAvailable($trajet->getPlaceAvailable() - 1);
            $pers->addUserRide($trajet);
            $entitymanager->persist($trajet);
            $entitymanager->persist($pers);
            $entitymanager->flush();
            return new JsonResponse("Inscription réussie", 200, [], true);
        } else if ($trajet->getPlaces() == 0) {
            return new JsonResponse("Plus de place disponible", 404, [], true);
        } else {
            return new JsonResponse("Erreur", 404, [], true);
        }
    }
}
