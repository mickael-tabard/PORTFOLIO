<?php

namespace App\Controller;

use App\Entity\Tweet;

use App\Form\TweetType;
use Doctrine\ORM\Mapping\Entity;
use App\Repository\UserRepository;
use App\Repository\TweetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/')]
class TweetController extends AbstractController
{
    #[Route('/', name: 'app_tweet_index', methods: ['GET', 'POST'])]
    public function index(Request $request, TweetRepository $tweetRepository, UserRepository $userRepository, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();
        $tweetsAsc = $tweetRepository->findAll(); // Changer l'ordre en ASC
        $tweetsDesc = $tweetRepository->getAllTweetsAscending();
        $lastThreeUsers = $userRepository->findLastThreeRegisteredUsers();

        $tweet = new Tweet();
        $form = $this->createForm(TweetType::class, $tweet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'utilisateur actuellement connecté
            $user = $security->getUser();

            // Associer l'utilisateur au tweet
            $tweet->setUser($user);

            $entityManager->persist($tweet);
            $entityManager->flush();

            return $this->redirectToRoute('app_tweet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tweet/index.html.twig', [
            'tweetsDesc' => $tweetsDesc,
            'tweetsAsc' => $tweetsAsc,
            'user' => $user,
            'lastThreeUsers' => $lastThreeUsers,
            'tweet' => $tweet,
            'form' => $form->createView(),
        ]);
    }



    #[Route('/{id}', name: 'app_tweet_show', methods: ['GET'])]
    public function show(Tweet $tweet): Response
    {
        return $this->render('tweet/show.html.twig', [
            'tweet' => $tweet,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tweet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tweet $tweet, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TweetType::class, $tweet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $tweet->setUser($this->getUser());

            return $this->redirectToRoute('app_tweet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tweet/edit.html.twig', [
            'tweet' => $tweet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tweet_delete', methods: ['POST'])]
    public function delete(Request $request, Tweet $tweet, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $tweet->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tweet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tweet_index', [], Response::HTTP_SEE_OTHER);
    }
}
