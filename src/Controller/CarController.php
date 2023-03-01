<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\User;
use App\Entity\Car;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class CarController extends AbstractController
{
    #[Route('listeVoitures', name: 'liste_voiture', methods: "GET")]
    public function listeVoiture(CarRepository $doctrine, SerializerInterface $serializerInterface): JsonResponse
    {
        $liste = $doctrine->findAll();
        $listeJson = $serializerInterface->serialize($liste, 'json', ['groups' => 'GetCar']);

        return new JsonResponse($listeJson, 200, [], true);
    }

    #[Route('deleteVoiture/{id}', name: 'delete_voiture', methods: "DELETE")]
    public function deleteVoiture(int $id, CarRepository $doctrine, EntityManagerInterface $entityManager): JsonResponse
    {
        $voiture = $doctrine->find($id);

        if ($voiture) {
            $entityManager->remove($voiture);
            $entityManager->flush();

            return new JsonResponse("Voiture supprimée", 200, [], true);
        } else {
            return new JsonResponse("Voiture non trouvée", 404, [], true);
        }
    }

    #[Route('insertVoiture', name: 'insert_voiture', methods: "POST")]
    public function insertVoiture(Request $request, CarRepository $doctrine, EntityManagerInterface $entityManager): JsonResponse
    {
        $plaque = $request->query->get('plaque');
        $p = $doctrine->findOneBy(['registration_car' => $plaque]);

        if ($p) {

            return new JsonResponse("Voiture déjà existante", 404, [], true);
        } else {

            $voiture = new Car;
            $personne = $entityManager->getRepository(User::class)->findOneBy(["id" => $request->query->get("id_pers")]);
            $voiture->setUserId($personne);
            $voiture->setRegistrationCar($plaque);
            $voiture->setColor($request->query->get('color'));
            $voiture->setPlaces($request->query->get('places'));
            $brand = $entityManager->getRepository(Brand::class)->findOneBy(['name' => strtoupper($request->query->get('brand'))]);
            $voiture->setTypeOf($brand);
            $voiture->setModel($request->query->get('model'));
            $personne->addCar($voiture);
            $entityManager->persist($personne);
            $entityManager->persist($voiture);
            $entityManager->flush();

            return new JsonResponse("Voiture ajoutée", 200, [], true);
        }
    }
}
