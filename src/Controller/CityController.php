<?php

namespace App\Controller;


use App\Entity\City;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class CityController extends AbstractController
{

    #[Route('/listeVilles', name: 'liste_ville', methods: "GET")]
    public function listeVilles(CityRepository $doctrine, SerializerInterface $serializerInterface): JsonResponse
    {
        $liste = $doctrine->findAll();
        $listeJson = $serializerInterface->serialize($liste, 'json');

        return new JsonResponse($listeJson, 200, [], true);
    }

    #[Route('/deleteVille/{id}', name: 'delete_ville', methods: "DELETE")]
    public function deleteVille(int $id, CityRepository $doctrine, EntityManagerInterface $entityManager): JsonResponse
    {
        $ville = $doctrine->find($id);

        if ($ville) {
            $entityManager->remove($ville);
            $entityManager->flush();

            return new JsonResponse("Ville supprimée", 200, [], true);
        } else {
            return new JsonResponse("Ville non trouvée", 404, [], true);
        }
    }

    #[Route('/insertVille', name: 'add_ville', methods: "POST")]
    public function insertVille(Request $request, CityRepository $doctrine, EntityManagerInterface $entityManager): JsonResponse
    {
        $ville = new City();
        $ville->setName($request->get('name'));
        $ville->setPostalCode($request->get('postal_code'));
        $ville->setLattitude($request->get('lattitude'));
        $ville->setLongitude($request->get('longitude'));

        $entityManager->persist($ville);
        $entityManager->flush();

        return new JsonResponse("Ville ajoutée", 200, [], true);
    }
}
