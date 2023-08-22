<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Entity\Envase;
use App\Entity\Dimension;
use App\Entity\Ingrediente;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ProductoController extends AbstractController
{
    #[Route('/producto/create', name: 'product_create', methods: ['POST'])]
    public function createProduct(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator, SerializerInterface $serializer): Response
    {
        $data = json_decode($request->getContent(), true);

        // Validate data
        $product = new Producto();
        $product->setNombre($data['nombre']);
        $product->setDescripcion($data['descripcion']);
        // $product->setPrecio($data['precio']);
        // $product->setExpiracion(new \DateTime($data['expiracion']));

        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, 400);
        }

        // Create and set envase
        $envase = new Envase();
        $envase->setNombre($data['envase']['nombre']);
        $envase->setDescripcion($data['envase']['descripcion']);
        $envase->setMaterial($data['envase']['material']);
        $envase->setVolumen($data['envase']['volumen']);
        $envase->setUnidadVol($data['envase']['unidadVol']);

        // Create and set dimensiones for envase
        $dimension = new Dimension();
        $dimension->setLargo($data['envase']['dimensiones']['largo']);
        $dimension->setAncho($data['envase']['dimensiones']['ancho']);
        $dimension->setAlto($data['envase']['dimensiones']['alto']);
        $dimension->setUnidad($data['envase']['dimensiones']['unidad']);

        $envase->setDimensionId($dimension);

        $product->setEnvaseId($envase);

        // Handle ingredientes
        if (isset($data['ingredientes']) && is_array($data['ingredientes'])) {
            foreach ($data['ingredientes'] as $ingredienteData) {
                $ingredienteName = $ingredienteData['nombre'];
                $ingredienteDescripcion = $ingredienteData['descripcion'];

                $ingrediente = $entityManager->getRepository(Ingrediente::class)->findOneBy(['nombre' => $ingredienteName]);
                if (!$ingrediente) {
                    // Create new ingrediente if not exists (or you can return an error if you prefer)
                    $ingrediente = new Ingrediente();
                    $ingrediente->setNombre($ingredienteName);
                    $ingrediente->setDescripcion($ingredienteDescripcion);
                    $entityManager->persist($ingrediente);
                }
                $product->addIngrediente($ingrediente);
            }
        }

        // Persist product
        $entityManager->persist($product);
        $entityManager->flush();

        // Serialize the product object, including related entities, into JSON
        $jsonProduct = $serializer->serialize($product, 'json', ['groups' => 'product']);

        return new JsonResponse($jsonProduct, 201, [], true);
    }
    #[Route('/producto/edit', name: 'product_edit', methods: ['POST'])]
    public function editProduct(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator, SerializerInterface $serializer): Response
    {
        $data = json_decode($request->getContent(), true);

        // Fetch the existing product
        $product = $entityManager->getRepository(Producto::class)->find($data['id']);
        if (!$product) {
            return new JsonResponse(['error' => 'Product not found'], 404);
        }

        // Update product properties
        $product->setNombre($data['nombre']);
        $product->setDescripcion($data['descripcion']);

        // Update envase properties
        $envase = $product->getEnvaseId();
        $envase->setNombre($data['envase']['nombre']);
        $envase->setDescripcion($data['envase']['descripcion']);
        $envase->setMaterial($data['envase']['material']);
        $envase->setVolumen($data['envase']['volumen']);
        $envase->setUnidadVol($data['envase']['unidadVol']);

        // Update dimension properties
        $dimension = $envase->getDimensionId();
        $dimension->setLargo($data['envase']['dimensiones']['largo']);
        $dimension->setAncho($data['envase']['dimensiones']['ancho']);
        $dimension->setAlto($data['envase']['dimensiones']['alto']);
        $dimension->setUnidad($data['envase']['dimensiones']['unidad']);

        // Validate the updated product
        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, 400);
        }

        // Flush changes to the database
        $entityManager->flush();

        // Serialize and return the updated product
        $jsonProduct = $serializer->serialize($product, 'json', ['groups' => 'product']);
        return new JsonResponse($jsonProduct, 200, [], true);
    }
    #[Route('/producto/delete/{id}', name: 'product_delete', methods: ['DELETE'])]
    public function deleteProduct(string $id, EntityManagerInterface $entityManager): Response
    {
        // Fetch the product entity from the database using the provided ID
        $product = $entityManager->getRepository(Producto::class)->find($id);

        // If the product is not found, return an error message
        if (!$product) {
            return new JsonResponse(['message' => 'Producto no encontrado.'], 404);
        }

        // Delete the product
        $entityManager->remove($product);
        $entityManager->flush();

        // Return a confirmation message
        return new JsonResponse(['message' => 'Producto eliminado con éxito.'], 200);
    }
    #[Route('/producto/list', name: 'product_list', methods: ['GET'])]
    public function listProducts(EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        // Fetch all Producto entities from the database
        $products = $entityManager->getRepository(Producto::class)->findAll();

        // If no products are found, return an empty array
        if (!$products) {
            return new JsonResponse([], 200);
        }

        // Serialize the products into JSON, including related entities
        $jsonProducts = $serializer->serialize($products, 'json', ['groups' => 'product']);

        return new JsonResponse($jsonProducts, 200, [], true);
    }
}
