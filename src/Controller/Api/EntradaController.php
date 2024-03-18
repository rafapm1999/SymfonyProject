<?php

namespace App\Controller\Api;

use App\Repository\EntradaRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EntradaController extends AbstractController
{
    #[Route('/api/entrada', name: 'app_api_entrada_index', methods:['GET'])]
    public function index(Request $request, EntradaRepository $entradaRepository, PaginatorInterface $paginator): Response
    {
        $currentPage = $request->query->get('page', 1);
        $query = $entradaRepository->getQueryAll();
        $allEntradas = $paginator->paginate($query, $currentPage, 10);
        $resultado = [];
        foreach ($allEntradas as $entrada) {
           $resultado[] = [
            'id'=>$entrada->getId(),
            'fecha'=> $entrada->getFecha()->format('Y-m-d H:i:s'),
            'slug'=>$entrada->getSlug(),
            'titulo'=>$entrada->getTitulo()
           ];
        }
        return $this->json($resultado);
    }
}
