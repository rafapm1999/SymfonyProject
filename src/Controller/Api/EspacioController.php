<?php

namespace App\Controller\Api;

use App\Entity\Espacio;
use App\Repository\EspacioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EspacioController extends AbstractController
{
    #[Route('/api/espacio', name: 'app_api_espacio_index', methods: ['GET'])]
    public function index(Request $request, EspacioRepository $espacioRepository, PaginatorInterface $paginator): Response
    {
        $currentPage = $request->query->get('page', 1);
        $query = $espacioRepository->getQueryAll();
        $allEspacios = $paginator->paginate($query, $currentPage, 10);
        /*     $allEspacios = $espacioRepository->findAll(['nombre' => 'ASC']); */
        $arrayColletion = [];

        foreach ($allEspacios as $espacio) {
            $arrayColletion[] = array(
                'id' => $espacio->getId(),
                'nombre' => $espacio->getNombre(),
            );
        }

        //Listado general de espacios
        return $this->json([
            'result' => 'ok',
            'espacios' => $arrayColletion,
        ]);
    }

    #[Route('/api/espacio', name: 'app_api_espacio_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        //Nuevo espacio
        $data = $request->toArray();
        if (isset($data['nombre'])) {
            $espacio = new Espacio();
            $espacio->setNombre($data['nombre']);
            $em->persist($espacio);
            $em->flush();
            return $this->json([
                'result' => 'ok',
                'id' => $espacio->getId()
            ]);
        } else {
            return new Response('Campo nombre no encontrado', 400);
        }
    }

    #[Route('/api/espacio/{id}', name: 'app_api_espacio_show', methods: ['GET'])]
    public function show($id, EspacioRepository $espacioRepository): Response
    {
        //Detalle del espacio
        $espacio = $espacioRepository->find($id);
        if ($espacio == null) {
            throw $this->createNotFoundException();
        }
        return $this->json([
            'id' => $espacio->getId(),
            'nombre' => $espacio->getNombre(),
        ]);
    }

    #[Route('/api/espacio/{id}', name: 'app_api_espacio_edit', methods: ['PUT'])]
    public function edit($id, Request $request, EntityManagerInterface $em, EspacioRepository $espacioRepository): Response
    {
        //Edicion del espacio
        $data = $request->toArray();
        $espacio = $espacioRepository->find($id);
        if ($espacio == null) {
            throw $this->createNotFoundException();
        }
        if (isset($data['nombre'])) {
            try {
                $espacio->setNombre($data['nombre']);
                $em->persist($espacio);
                $em->flush();
                return $this->json([
                    'result' => 'ok',
                    'id' => $espacio->getId()
                ]);
            } catch (\Exception $exception) {
                return $this->json([
                    'message' => $exception->getMessage()
                ], 400);
            }
        } else {
            return new Response('Campo nombre no encontrado', 400);
        }
    }

    #[Route('/api/espacio/{id}', name: 'app_api_espacio_delete', methods: ['DELETE'])]
    public function delete($id, EspacioRepository $espacioRepository ): Response
    {
        //Eliminacion del espacio
        $espacio = $espacioRepository->find($id);
        if ($espacio == null) {
            throw $this->createNotFoundException();
        }
            try {
               $espacioRepository->remove($espacio, true);
            } catch (\Exception $exception) {
                return $this->json([
                    'message' => $exception->getMessage()
                ], 400);
            }
        return $this->json([
            'result' => 'ok',
        ]);
    }
}
