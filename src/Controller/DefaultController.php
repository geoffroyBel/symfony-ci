<?php

namespace App\Controller;

use App\Entity\Prestation;
use App\Security\UserConfirmationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use JoliCode\Elastically\Client;
use JoliCode\Elastically\Messenger\IndexationRequest;
use Elastica\Query\MultiMatch;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Serializer\SerializerInterface;

class DefaultController extends AbstractController
{
    // /**
    //  * @Route("/default", name="default")
    //  */
    // #[Route("/default", name: "default_index")]
    // public function index(): Response
    // {
    //     return $this->json([
    //         'message' => 'Welcome to your new controller!',
    //         'path' => 'src/Controller/DefaultController.php',
    //     ]);
    // }
    // /**
    //  * @Route("/search", methods="GET", name="blog_search")
    //  */
    // #[Route("/search", methods:"GET", name: "presta_search")]
    // public function search(Request $request, Client $client): Response
    // {
    //     $query = $request->query->get('q', '');
    //     $limit = $request->query->get('l', 10);

    //     $searchQuery = new MultiMatch();
    //     $searchQuery->setFields([

    //         'name'
    //     ]); 
    //     $searchQuery->setQuery($query);
    //     $searchQuery->setType(MultiMatch::TYPE_MOST_FIELDS);

    //     $foundPosts = $client->getIndex('prestation')->search($searchQuery);
    //     $results = [];

    //     foreach ($foundPosts->getResults() as $result) {
    //         /** @var \App\Model\Prestation $post */
    //         $post = $result->getModel();

    //         $results[] = [
    //             'name' => htmlspecialchars($post->name, ENT_COMPAT | ENT_HTML5),
    //             // 'date' => $post->publishedAt->format('M d, Y'),
    //             // 'author' => htmlspecialchars($post->authorName, ENT_COMPAT | ENT_HTML5),
    //             // 'summary' => htmlspecialchars($post->summary, ENT_COMPAT | ENT_HTML5),
    //             // 'url' => $this->generateUrl('blog_post', ['slug' => $post->slug]),
    //         ];
    //     }
    //     return $this->json($results);
    // }
    // #[Route("/confirm-user/{token}", name: "default_confirm_token")]
    // public function confirmUser(string $token, UserConfirmationService $userConfirmationService): void
    // {
    //     $userConfirmationService->confirmUser($token);
    //     $this->redirectToRoute("default_index");
    // }


    // // #[Route("/add", name:"mercure_add", methods: "POST")]
    // // public function add(Request $request, SerializerInterface $serializer)
    // // {
    // //     $prestation = $serializer->deserialize($request->getContent(), Prestation::class, 'json');
    // //     return $this->json($prestation);
    // // }
    // #[Route("/mercure_test", methods:"GET", name: "presta_search")]
    // public function test(Request $request, HubInterface $hub)
    // {
    //     $update = new Update("http://localhost:8741/ping", "[]");
    //     $hub->publish($update);
    //     return $this->json([]);
    // }
}
