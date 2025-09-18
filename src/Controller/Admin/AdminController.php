<?php

namespace App\Controller\Admin;

use App\Service\LanguageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    public function __construct(
        private LanguageService $languageService
    ) {
    }

    #[Route('/', name: 'admin_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'languages' => $this->languageService->getActiveLanguages(),
            'currentLanguage' => $this->languageService->getCurrentLanguage(),
        ]);
    }
}
