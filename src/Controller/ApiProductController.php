<?php

namespace App\Controller;

use App\Entity\Consumer;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ApiProductController extends AbstractController
{
    /**
     * @Route("/api/products", name="app_api_product_index",methods={"GET"})
     */
    #[OA\Response(
        response: 200,
        description: 'Retourne les informations des produit ',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Product::class, groups: ['product:all']))
        )
    )]
    #[Security(name: 'Bearer')]
    public function index(ProductRepository $productRepository,CacheInterface $cache,Request $request)
    {
        $page = $request->get('page',1);
        $limit = $request->get('limit',3);

        $resulatProduct = $cache->get('allproduct'.$page."&".$limit,function (ItemInterface $item) use ($limit, $page, $productRepository) {
            $item->expiresAfter(36100);
            return $productRepository->findAllWithPagination($page,$limit);
        });
        return $this->json($resulatProduct,200,[],['groups' => 'product:all']);
    }

    /**
     * @Route("/api/product/{id}", name="app_api_product",methods={"GET"})
     */
    #[OA\Response(
        response: 200,
        description: 'Retourne les informations d\'un produit ',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Product::class))
        )
    )]
    #[Security(name: 'Bearer')]
    public function getproduct($id,ProductRepository $productRepository,CacheInterface $cache)
    {
        try {
            $product = $cache->get('product-'.$id,function (ItemInterface $item) use ($id, $productRepository) {
                $item->expiresAfter(3600);
                return $productRepository->getProductById($id);
            });
            return $this->json($product,200,[]);
        }  catch (Exception $exception) {
            return $this->json($exception->getMessage(),$exception->getCode());
        }
    }
}
