<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Likes;
use App\Entity\Tweet;
use App\Form\Likes1Type;
use App\Repository\LikesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/likes')]
class LikesController extends AbstractController
{
    #[Route('/', name: 'app_likes_index', methods: ['GET'])]
    public function index(LikesRepository $likesRepository): Response
    {


        return $this->render('likes/index.html.twig', [
            'likes' => $likesRepository->findAll(),

        ]);
    }

    #[Route('/new', name: 'app_likes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $id_user = $request->query->get('id_user');
        $id_tweet = $request->query->get('id_tweet');

        // Récupérer l'utilisateur et le tweet correspondants
        $user = $entityManager->getRepository(User::class)->find($id_user);
        $tweet = $entityManager->getRepository(Tweet::class)->find($id_tweet);

        if (!$user || !$tweet) {
            // Gérer la situation où l'utilisateur ou le tweet n'existe pas
            // Vous pouvez rediriger vers une page d'erreur ou afficher un message
            //...

            return $this->redirectToRoute('page_d_erreur');
        }

        // Vérifier si le like n'existe pas déjà
        $existingLike = $entityManager->getRepository(Likes::class)->findOneBy([
            'user' => $this->getUser(),
            'tweet' => $tweet,
        ]);

        if ($this->getUser() !== null) {
            # code...
            if ($existingLike !== null) {
                // Vérifier si le like existant appartient à l'utilisateur actuel
                if ($existingLike->getUser() === $this->getUser()) {
                    // L'utilisateur actuel a déjà liké ce tweet, supprimer ce like
                    $entityManager->remove($existingLike);
                    $entityManager->flush();
                } else {
                    // Le like existant appartient à un autre utilisateur, peut-être afficher un message d'erreur ou gérer la situation
                    // ...
                    $like = new Likes();
                    $like->setUser($this->getUser());
                    $like->setTweet($tweet);

                    $entityManager->persist($like);
                    $entityManager->flush();
                }
            } else {
                // Créer un nouvel objet Likes car l'utilisateur n'a pas encore liké ce tweet
                $like = new Likes();
                $like->setUser($this->getUser());
                $like->setTweet($tweet);

                $entityManager->persist($like);
                $entityManager->flush();
            }
        } else {
            return $this->redirectToRoute('page_d_erreur_user_pas_connecter');
        }
        return $this->redirectToRoute('app_tweet_index', [], Response::HTTP_SEE_OTHER);
    }




    #[Route('/{id}', name: 'app_likes_show', methods: ['GET'])]
    public function show(Likes $like): Response
    {
        return $this->render('likes/show.html.twig', [
            'like' => $like,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_likes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Likes $like, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Likes1Type::class, $like);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_likes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('likes/edit.html.twig', [
            'like' => $like,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_likes_delete', methods: ['POST'])]
    public function delete(Request $request, Likes $like, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $like->getId(), $request->request->get('_token'))) {
            $entityManager->remove($like);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_likes_index', [], Response::HTTP_SEE_OTHER);
    }
}
