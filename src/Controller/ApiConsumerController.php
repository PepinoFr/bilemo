<?php

namespace App\Controller;

use App\Entity\Consumer;
use App\Repository\ConsumerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Property;
use Symfony\Component\HttpKernel\Attribute\Cache;

use \OpenApi\Annotations\JsonContent;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class ApiConsumerController extends AbstractController
{
    /**
     * @Route("/api/consumer", name="app_api_consumers",methods={"GET"})
     */
    #[OA\Response(
        response: 200,
        description: 'Retourne les informations des utilisateurs ',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Consumer::class, groups: ['consumer:all']))
        )
    )]
    #[Security(name: 'Bearer')]
    public function getAllConsumer(ConsumerRepository $consumerRepository,TagAwareCacheInterface $cache,Request $request)
    {
        $page = $request->get('page',1);
        $limit = $request->get('limit',3);

        $resulatUser = $cache->get('user-'.str_replace( array( '%', '@', '\'', ';', '<', '>' ), ' ', $this->getUser()->getUserIdentifier()).$page."&".$limit,function (ItemInterface $item) use ($limit, $page, $consumerRepository) {
            $item->expiresAfter(3600);
            $item->tag("consumerCache");
            return $consumerRepository->findAllWithPagination($this->getUser()->getId(),$page,$limit);
        });
        return $this->json($resulatUser,200,[],['groups' => 'consumer:all']);
    }

    /**
     * @Route("/api/consumer/{id}", name="app_api_consumer",methods={"GET"})
     */
    #[OA\Response(
        response: 200,
        description: 'Retourne les informations d\'un utilisateur ',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Consumer::class, groups: ['consumer:read']))
        )
    )]
    #[Security(name: 'Bearer')]

    public function getConsumer($id,ConsumerRepository $consumerRepository,TagAwareCacheInterface  $cache)
    {
        try {
            $consumer = $cache->get('id-'.$id,function (ItemInterface $item) use ($id, $consumerRepository) {
                $item->expiresAfter(3600);
                return $consumerRepository->getConsumerById($id);
            });
            return $this->json($consumer,200,[],['groups' => 'consumer:read']);
        } catch (Exception $exception) {
            return $this->json($exception->getMessage(),$exception->getCode());
        }

    }

    /**
     * @Route("/api/consumer",name="app_api_addconsumer",methods={"POST"})
     */
    #[OA\RequestBody(
        request: 'Consumer',
        description: 'Données de l\'utilisateur',
        required: true,
    )]
    #[OA\Response(
        response: 200,
        description: 'Permet d\'ajouter un utilisateur ',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Consumer::class, groups: ['consumer:add']))
        )
    )]

    #[Security(name: 'Bearer')]
    public function store(Request $request,SerializerInterface $serializer,EntityManagerInterface $manager,TagAwareCacheInterface $cache){
        $jsonRecu = $request->getContent();

        $consumer = $serializer->deserialize($jsonRecu,Consumer::class,'json');

        $user = $this->getUser();;

        $consumer->setClient($user);

        $cache->invalidateTags(["consumerCache"]);

        $manager->persist($consumer);
        $manager->flush();

        return $this->json($consumer,201,[],['groups'=>'consumer:read']);
    }
    /**
     * @Route("/api/consumer/delete/{id}", name="app_api_del_consumer",methods={"GET"})
     */
    #[OA\Response(
        response: 204,
        description: 'Permet de suprimmer  un utilisateur ',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Consumer::class, groups: ['consumer:delete']))
        )
    )]

    #[Security(name: 'Bearer')]
    public function delConsumer($id,ConsumerRepository $consumerRepository,EntityManagerInterface $manager,TagAwareCacheInterface  $cache)
    {

        try {
            $consumer = $consumerRepository->getConsumerById($id);
            $manager->remove($consumer);
            $manager->flush();
            $cache->invalidateTags(["consumerCache"]);
            return $this->json("delete",204,[]) ;
        } catch (Exception $exception) {
            return $this->json($exception->getMessage(),$exception->getCode());
        }

    }

}
