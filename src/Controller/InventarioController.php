<?php

namespace App\Controller;

use App\Entity\Inventario;
use App\Repository\InventarioRepository;
use App\Repository\TipoRepository;
use App\Repository\ProductoRepository;
use App\Entity\Transferencia;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class InventarioController extends AbstractController
{
    #[Route('/bodega/add', name: 'add_to_bodega', methods: ['POST'])]
    public function addToBodega(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator, SerializerInterface $serializer, InventarioRepository $inventarioRepository, TipoRepository $tipoRepository, ProductoRepository $productoRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        // Validate data
        $inventario = new Inventario();
        // Check if tipo exists
        $tipo = $tipoRepository->find($data['tipo_id']);
        if (!$tipo) {
            return new JsonResponse(['error' => 'Invalid tipo_id provided.'], 400);
        }
        // Check if producto exists
        $producto = $productoRepository->find($data['producto_id']);
        if (!$producto) {
            return new JsonResponse(['error' => 'Invalid producto_id provided.'], 400);
        }
        // Check if an entry exists that matches the tipo_id, producto_id, and expiracion
        $existingEntry = $inventarioRepository->findOneBy([
            'tipo_id' => $tipo,
            'producto_id' => $producto,
            'expiracion' => new \DateTime($data['expiracion'])
        ]);
        if ($existingEntry) {
            // Update the cantidad of the existing entry
            $existingEntry->setCantidad($existingEntry->getCantidad() + $data['cantidad']);
        } else {
            // Create a new entry
            $inventario = new Inventario();
            $inventario->setTipoId($tipo);
            $inventario->setProductoId($producto);
            $inventario->setCantidad($data['cantidad']);
            $inventario->setExpiracion(new \DateTime($data['expiracion']));

            $errors = $validator->validate($inventario);
            if (count($errors) > 0) {
                return new JsonResponse((string) $errors, 400);
            }

            // Persist the new inventario entry
            $entityManager->persist($inventario);
        }

        $errors = $validator->validate($inventario);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, 400);
        }

        // Persist inventario entry
        $entityManager->flush();

        // Serialize the inventario object into JSON
        $jsonInventario = $serializer->serialize($existingEntry ?? $inventario, 'json');

        return new JsonResponse($jsonInventario, 201, [], true);
    }
    #[Route('/inventario/transfer', name: 'transfer_inventario', methods: ['POST'])]
    public function transferInventario(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator, SerializerInterface $serializer, ProductoRepository $productoRepository, TipoRepository $tipoRepository, InventarioRepository $inventarioRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        // Validate data
        $transferencia = new Transferencia();

        // Check if producto exists
        $producto = $productoRepository->find($data['producto_id']);
        if (!$producto) {
            return new JsonResponse(['error' => 'Invalid producto_id provided.'], 400);
        }

        // Check if origen tipo exists
        $origenTipo = $tipoRepository->find($data['origen_tipo_id']);
        if (!$origenTipo) {
            return new JsonResponse(['error' => 'Invalid origen_tipo_id provided.'], 400);
        }

        // Check if destino tipo exists
        $destinoTipo = $tipoRepository->find($data['destino_tipo_id']);
        if (!$destinoTipo) {
            return new JsonResponse(['error' => 'Invalid destino_tipo_id provided.'], 400);
        }

        $transferencia->setProductoId($producto);
        $transferencia->setOrigen($origenTipo);
        $transferencia->setDestino($destinoTipo);
        $transferencia->setCantidad($data['cantidad']);
        $transferencia->setFechaRealizada(new \DateTime($data['fecha']));
        $transferencia->setExpiracion(new \DateTime($data['expiracion']));

        $errors = $validator->validate($transferencia);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, 400);
        }

        // Persist transferencia entry
        $entityManager->persist($transferencia);
        $entityManager->flush();

        // Fetch updated entries from Inventario for both origen and destino
        $origenInventario = $inventarioRepository->findOneBy(['tipo_id' => $origenTipo, 'producto_id' => $producto, 'expiracion' => new \DateTime($data['expiracion'])]);
        $destinoInventario = $inventarioRepository->findOneBy(['tipo_id' => $destinoTipo, 'producto_id' => $producto, 'expiracion' => new \DateTime($data['expiracion'])]);

        $response = [
            'origen' => $origenInventario ? $origenInventario->getCantidad() : 0,
            'destino' => $destinoInventario ? $destinoInventario->getCantidad() : 0
        ];

        return new JsonResponse($response, 200);
    }
    #[Route('/inventario/list', name: 'inventario_list', methods: ['GET'])]
    public function listInventarios(EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        // Fetch all Inventario entities from the database
        $inventarios = $entityManager->getRepository(Inventario::class)->findAll();

        // If no inventarios are found, return an empty array
        if (!$inventarios) {
            return new JsonResponse([], 200);
        }

        // Serialize the inventarios into JSON, including related entities
        $jsonInventarios = $serializer->serialize($inventarios, 'json');

        return new JsonResponse($jsonInventarios, 200, [], true);
    }
}
