<?php

namespace App\Controller;

use App\Repository\ServiceRepository;
use App\Service\LanguageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private ServiceRepository $serviceRepository,
        private LanguageService $languageService
    ) {
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $services = $this->serviceRepository->findPublishedServices();
        $currentLanguage = $this->languageService->getCurrentLanguage();
        $defaultLanguage = $this->languageService->getDefaultLanguage();

        return $this->render('home/index.html.twig', [
            'services' => $services,
            'currentLanguage' => $currentLanguage,
            'defaultLanguage' => $defaultLanguage,
            'languages' => $this->languageService->getActiveLanguages(),
        ]);
    }

    #[Route('/service/{slug}', name: 'service_show')]
    public function show(string $slug): Response
    {
        $service = $this->serviceRepository->findBySlugWithTranslations($slug);
        
        if (!$service) {
            throw $this->createNotFoundException('Service not found.');
        }

        $currentLanguage = $this->languageService->getCurrentLanguage();
        $defaultLanguage = $this->languageService->getDefaultLanguage();
        $translation = $service->getTranslationWithFallback($currentLanguage, $defaultLanguage);

        return $this->render('home/service.html.twig', [
            'service' => $service,
            'translation' => $translation,
            'currentLanguage' => $currentLanguage,
            'defaultLanguage' => $defaultLanguage,
            'languages' => $this->languageService->getActiveLanguages(),
        ]);
    }

    #[Route('/switch-language/{code}', name: 'switch_language')]
    public function switchLanguage(string $code, Request $request): Response
    {
        $language = $this->languageService->getLanguageByCodeOrDefault($code);
        $this->languageService->setCurrentLanguage($language);

        // Redirect back to the referer or home
        $referer = $request->headers->get('referer');
        if ($referer) {
            return $this->redirect($referer);
        }

        return $this->redirectToRoute('home');
    }
}
