<?php

namespace App\Controller\Api;

use App\Repository\EspacioRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EspacioController extends AbstractController
{
    #[Route('/api/espacio', name: 'app_api_espacio_index', methods: ['GET'])]
    public function index(EspacioRepository $espacioRepository, PaginatorInterface $paginator): Response
    {
        $query = $espacioRepository->getQueryAll();
        $allEspacios = $paginator->paginate($query, 1, 10);
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
    public function new(): Response
    {
        //Nuevo espacio
        return $this->json([
            'result' => 'ok',
            'usuario' => $this->getUser()->getUserIdentifier()
        ]);
    }

    #[Route('/api/espacio/{id}', name: 'app_api_espacio_show', methods: ['GET'])]
    public function show($id): Response
    {
        //Detalle del espacio
        return $this->json([
            'result' => 'ok',
            'usuario' => $this->getUser()->getUserIdentifier()
        ]);
    }

    #[Route('/api/espacio/{id}', name: 'app_api_espacio_edit', methods: ['PUT'])]
    public function edit($id): Response
    {
        //Edicion del espacio
        return $this->json([
            'result' => 'ok',
            'usuario' => $this->getUser()->getUserIdentifier()
        ]);
    }

    #[Route('/api/espacio/{id}', name: 'app_api_espacio_delete', methods: ['DELETE'])]
    public function delete($id): Response
    {
        //Eliminacion del espacio
        return $this->json([
            'result' => 'ok',
            'usuario' => $this->getUser()->getUserIdentifier()
        ]);
    }
}
