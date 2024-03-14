<?php

namespace App\Controller\Admin;

use App\Entity\Usuario;
use App\Form\UsuarioType;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/admin/usuario')]
class UsuarioController extends AbstractController
{
    #[Route('/', name: 'app_admin_usuario_index', methods: ['GET'])]
    public function index(UsuarioRepository $usuarioRepository): Response
    {
        if ($this->isGranted('ROLE_SUPERADMIN')) {
            return $this->render('admin/usuario/index.html.twig', [
                'usuarios' => $usuarioRepository->findAll(),
            ]);
        } else {
            return $this->render('admin/usuario/index.html.twig', [
                'usuarios' => $usuarioRepository->findBy(['email' => $this->getUser()->getUserIdentifier()])
            ]);
        }
    }

    #[Route('/new', name: 'app_admin_usuario_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UsuarioRepository $usuarioRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $usuario = new Usuario();
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword(
                $usuario,
                $usuario->getPassword()
            );
            $usuario->setPassword($hashedPassword);
            $entityManager->persist($usuario);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_usuario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/usuario/new.html.twig', [
            'usuario' => $usuario,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_usuario_show', methods: ['GET'])]
    public function show(Usuario $usuario): Response
    {
        return $this->render('admin/usuario/show.html.twig', [
            'usuario' => $usuario,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_usuario_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Usuario $usuario,
        UsuarioRepository $usuarioRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        if (!$this->isGranted('ROLE_SUPERADMIN') && $usuario->getUserIdentifier() != $this->getUser()->getUserIdentifier()) {
            throw $this->createAccessDeniedException('No puedes editar un usuario que no sea tuyo');
        }
        $oldPassword = $usuario->getPassword();
        $oldRoles = $usuario->getRoles();
        $form = $this->createForm(UsuarioType::class, $usuario, [
            'isSuperadmin' => $this->isGranted('ROLE_SUPERADMIN')
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($usuario->getPassword() == '') {
                $usuario->setPassword(($oldPassword));
            } else {
                $hashedPassword = $passwordHasher->hashPassword(
                    $usuario,
                    $usuario->getPassword()
                );
                $usuario->setPassword($hashedPassword);
            }
            if (!$this->isGranted('ROLE_SUPERADMIN')) {
                $usuario->setRoles($oldRoles);
            }
            /* $usuarioRepository->add($usuario, true); */
            $entityManager->persist($usuario);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_usuario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/usuario/edit.html.twig', [
            'usuario' => $usuario,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_usuario_delete', methods: ['POST'])]
    public function delete(Request $request, Usuario $usuario, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $usuario->getId(), $request->request->get('_token'))) {
            $entityManager->remove($usuario);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_usuario_index', [], Response::HTTP_SEE_OTHER);
    }
}
