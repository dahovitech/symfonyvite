<?php

namespace App\Controller\Admin;

use App\Entity\Language;
use App\Repository\LanguageRepository;
use App\Service\LanguageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/languages')]
class LanguageController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private LanguageRepository $languageRepository,
        private LanguageService $languageService
    ) {
    }

    #[Route('/', name: 'admin_language_index')]
    public function index(): Response
    {
        $languages = $this->languageRepository->findBy([], ['isDefault' => 'DESC', 'name' => 'ASC']);

        return $this->render('admin/language/index.html.twig', [
            'languages' => $languages,
        ]);
    }

    #[Route('/new', name: 'admin_language_new')]
    public function new(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $language = new Language();
            $language->setCode($request->request->get('code'));
            $language->setName($request->request->get('name'));
            $language->setIsActive(true);
            $language->setIsDefault(false);

            // Check if this should be the default language
            if ($request->request->get('is_default')) {
                $this->languageService->setAsDefault($language);
            } else {
                $this->entityManager->persist($language);
                $this->entityManager->flush();
            }

            $this->addFlash('success', 'Language created successfully!');
            return $this->redirectToRoute('admin_language_index');
        }

        return $this->render('admin/language/new.html.twig');
    }

    #[Route('/{id}/edit', name: 'admin_language_edit')]
    public function edit(Language $language, Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $language->setCode($request->request->get('code'));
            $language->setName($request->request->get('name'));
            $language->setIsActive($request->request->get('is_active') ? true : false);

            // Check if this should be the default language
            if ($request->request->get('is_default')) {
                $this->languageService->setAsDefault($language);
            } else {
                $this->entityManager->persist($language);
                $this->entityManager->flush();
            }

            $this->addFlash('success', 'Language updated successfully!');
            return $this->redirectToRoute('admin_language_index');
        }

        return $this->render('admin/language/edit.html.twig', [
            'language' => $language,
        ]);
    }

    #[Route('/{id}/set-default', name: 'admin_language_set_default')]
    public function setDefault(Language $language): Response
    {
        $this->languageService->setAsDefault($language);
        $this->addFlash('success', 'Default language set successfully!');
        
        return $this->redirectToRoute('admin_language_index');
    }

    #[Route('/{id}/toggle-active', name: 'admin_language_toggle_active')]
    public function toggleActive(Language $language): Response
    {
        if ($language->isDefault() && $language->isActive()) {
            $this->addFlash('error', 'Cannot deactivate the default language!');
        } else {
            $language->setIsActive(!$language->isActive());
            $this->entityManager->persist($language);
            $this->entityManager->flush();
            
            $status = $language->isActive() ? 'activated' : 'deactivated';
            $this->addFlash('success', "Language {$status} successfully!");
        }

        return $this->redirectToRoute('admin_language_index');
    }

    #[Route('/{id}/delete', name: 'admin_language_delete', methods: ['POST'])]
    public function delete(Language $language): Response
    {
        if ($language->isDefault()) {
            $this->addFlash('error', 'Cannot delete the default language!');
        } else {
            $this->entityManager->remove($language);
            $this->entityManager->flush();
            $this->addFlash('success', 'Language deleted successfully!');
        }

        return $this->redirectToRoute('admin_language_index');
    }
}
