<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Repository\BrandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class BrandController extends AbstractController
{
    #[Route('/listeMarques', name: 'liste_marque', methods: "GET")]
    public function listeMarque(BrandRepository $doctrine, SerializerInterface $serializerInterface): JsonResponse
    {
        $liste = $doctrine->findAll();
        $listeJson = $serializerInterface->serialize($liste, 'json');

        return new JsonResponse($listeJson, 200, [], true);
    }


    #[Route('/deleteMarque/{id}', name: 'delete_marque', methods: "DELETE")]
    public function deleteMarque(int $id, BrandRepository $doctrine, EntityManagerInterface $entityManager): JsonResponse
    {
        $marque = $doctrine->find($id);

        if ($marque) {
            $entityManager->remove($marque);
            $entityManager->flush();

            return new JsonResponse("Marque supprimée", 200, [], true);
        } else {
            return new JsonResponse("Marque non trouvée", 404, [], true);
        }
    }

    /**
     * @Route("/insertMarque", name="insert_marque", methods="POST")
     */


    #[Route('/insertMarque', name: 'insert_marque', methods: "POST")]
    public function insertMarque(Request $request, BrandRepository $doctrine, EntityManagerInterface $entityManager): JsonResponse
    {
        $name = strtoupper($request->query->get('name'));
        $findmarque = $doctrine->findOneBy(['name' => $name]);
        if ($name == null) {
            return new JsonResponse("Marque vide", 404, [], true);
        } else if ($findmarque) {
            return new JsonResponse("Marque déjà existante", 404, [], true);
        } else {
            $marque = new Brand();
            $marque->setName($name);
            $entityManager->persist($marque);
            $entityManager->flush();

            return new JsonResponse("Marque ajoutée", 200, [], true);
        }
    }
}
