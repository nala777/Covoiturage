<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Entity\Car;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    #[Route('/listePersonne', name: 'liste_inscription', methods: "GET")]
    public function listeInscription(UserRepository $doctrine, SerializerInterface $serializerInterface): JsonResponse
    {
        $liste = $doctrine->findAll();
        $listeJson = $serializerInterface->serialize($liste, 'json');

        return new JsonResponse($listeJson, 200, [], true);
    }

    #[Route('/selectPersonne/{id}', name: 'select_personne', methods: "GET")]
    public function selectPersonne(int $id, UserRepository $doctrine, SerializerInterface $serializerInterface): JsonResponse
    {
        $personne = $doctrine->find($id);
        if ($personne) {
            $personneJson = $serializerInterface->serialize($personne, 'json');
            return new JsonResponse($personneJson, 200, [], true);
        } else {
            return new JsonResponse("Personne non trouvée", 404, [], true);
        }
    }

    #[Route('/deletePersonne/{id}', name: 'delete_personne', methods: "DELETE")]
    public function deleteInscription(int $id, UserRepository $doctrine, EntityManagerInterface $entityManager): JsonResponse
    {
        $inscription = $doctrine->find($id);

        if ($inscription) {
            $entityManager->remove($inscription);
            $entityManager->flush();

            return new JsonResponse("Personne supprimée", 200, [], true);
        } else {
            return new JsonResponse("Personne non trouvée", 404, [], true);
        }
    }

    #[Route('/insertPersonne', name: 'insert_personne', methods: "POST")]
    public function insertInscription(Request $request, UserRepository $doctrine, EntityManagerInterface $entityManager): JsonResponse
    {
        $email = $request->query->get('email');
        $findinscription = $doctrine->findOneBy(['email' => $email]);
        if ($email == null) {
            return new JsonResponse("Inscription vide", 404, [], true);
        } else if ($findinscription) {

            return new JsonResponse("Inscription déjà existante", 404, [], true);
        } else {

            $inscription = new User();
            $inscription->setEmail($email);
            $pwd = $request->query->get('password');
            $inscription->setPassword(password_hash($pwd, PASSWORD_DEFAULT));
            $inscription->setFirstname($request->query->get('firstname'));
            $inscription->setLastname($request->query->get('lastname'));
            $entityManager->persist($inscription);
            $entityManager->flush();

            return new JsonResponse("Inscription ajoutée", 200, [], true);
        }
    }
}
