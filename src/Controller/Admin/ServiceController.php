<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use App\Entity\ServiceTranslation;
use App\Repository\ServiceRepository;
use App\Service\LanguageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/services')]
class ServiceController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ServiceRepository $serviceRepository,
        private LanguageService $languageService,
        private SluggerInterface $slugger
    ) {
    }

    #[Route('/', name: 'admin_service_index')]
    public function index(): Response
    {
        $services = $this->serviceRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/service/index.html.twig', [
            'services' => $services,
            'currentLanguage' => $this->languageService->getCurrentLanguage(),
        ]);
    }

    #[Route('/new', name: 'admin_service_new')]
    public function new(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $service = new Service();
            $slug = $this->slugger->slug($request->request->get('slug', ''))->lower();
            $service->setSlug($slug);
            $service->setIsPublished($request->request->get('is_published') ? true : false);

            // Create translations for all active languages
            $languages = $this->languageService->getActiveLanguages();
            foreach ($languages as $language) {
                $title = $request->request->get("title_{$language->getCode()}");
                $description = $request->request->get("description_{$language->getCode()}");
                $detail = $request->request->get("detail_{$language->getCode()}");

                if ($title || $description || $detail) {
                    $translation = new ServiceTranslation();
                    $translation->setLanguage($language);
                    $translation->setTitle($title);
                    $translation->setDescription($description);
                    $translation->setDetail($detail);
                    $service->addTranslation($translation);
                }
            }

            $this->entityManager->persist($service);
            $this->entityManager->flush();

            $this->addFlash('success', 'Service created successfully!');
            return $this->redirectToRoute('admin_service_index');
        }

        $languages = $this->languageService->getActiveLanguages();
        
        return $this->render('admin/service/new.html.twig', [
            'languages' => $languages,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_service_edit')]
    public function edit(Service $service, Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $slug = $this->slugger->slug($request->request->get('slug', ''))->lower();
            $service->setSlug($slug);
            $service->setIsPublished($request->request->get('is_published') ? true : false);
            $service->setUpdatedAt(new \DateTimeImmutable());

            // Update translations for all active languages
            $languages = $this->languageService->getActiveLanguages();
            foreach ($languages as $language) {
                $title = $request->request->get("title_{$language->getCode()}");
                $description = $request->request->get("description_{$language->getCode()}");
                $detail = $request->request->get("detail_{$language->getCode()}");

                $translation = $service->getTranslation($language);
                if (!$translation && ($title || $description || $detail)) {
                    $translation = new ServiceTranslation();
                    $translation->setLanguage($language);
                    $service->addTranslation($translation);
                }

                if ($translation) {
                    $translation->setTitle($title);
                    $translation->setDescription($description);
                    $translation->setDetail($detail);
                }
            }

            $this->entityManager->persist($service);
            $this->entityManager->flush();

            $this->addFlash('success', 'Service updated successfully!');
            return $this->redirectToRoute('admin_service_index');
        }

        $languages = $this->languageService->getActiveLanguages();
        
        return $this->render('admin/service/edit.html.twig', [
            'service' => $service,
            'languages' => $languages,
        ]);
    }

    #[Route('/{id}/toggle-published', name: 'admin_service_toggle_published')]
    public function togglePublished(Service $service): Response
    {
        $service->setIsPublished(!$service->isPublished());
        $service->setUpdatedAt(new \DateTimeImmutable());
        $this->entityManager->persist($service);
        $this->entityManager->flush();

        $status = $service->isPublished() ? 'published' : 'unpublished';
        $this->addFlash('success', "Service {$status} successfully!");

        return $this->redirectToRoute('admin_service_index');
    }

    #[Route('/{id}/delete', name: 'admin_service_delete', methods: ['POST'])]
    public function delete(Service $service): Response
    {
        $this->entityManager->remove($service);
        $this->entityManager->flush();
        $this->addFlash('success', 'Service deleted successfully!');

        return $this->redirectToRoute('admin_service_index');
    }
}
