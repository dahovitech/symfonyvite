<?php

namespace App\Service;

use App\Entity\Language;
use App\Repository\LanguageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LanguageService
{
    private const SESSION_LANGUAGE_KEY = 'current_language';

    public function __construct(
        private LanguageRepository $languageRepository,
        private EntityManagerInterface $entityManager,
        private RequestStack $requestStack
    ) {
    }

    /**
     * Get the current language from session or default
     */
    public function getCurrentLanguage(): Language
    {
        $session = $this->getSession();
        $languageCode = $session?->get(self::SESSION_LANGUAGE_KEY);

        if ($languageCode) {
            $language = $this->languageRepository->findByCode($languageCode);
            if ($language) {
                return $language;
            }
        }

        return $this->getDefaultLanguage();
    }

    /**
     * Set the current language in session
     */
    public function setCurrentLanguage(Language $language): void
    {
        $session = $this->getSession();
        $session?->set(self::SESSION_LANGUAGE_KEY, $language->getCode());
    }

    /**
     * Get the default language
     */
    public function getDefaultLanguage(): Language
    {
        $defaultLanguage = $this->languageRepository->findDefaultLanguage();
        
        if (!$defaultLanguage) {
            // Create a default language if none exists
            $defaultLanguage = new Language();
            $defaultLanguage->setCode('fr');
            $defaultLanguage->setName('Français');
            $defaultLanguage->setIsDefault(true);
            $defaultLanguage->setIsActive(true);
            
            $this->entityManager->persist($defaultLanguage);
            $this->entityManager->flush();
        }

        return $defaultLanguage;
    }

    /**
     * Get all active languages
     */
    public function getActiveLanguages(): array
    {
        return $this->languageRepository->findActiveLanguages();
    }

    /**
     * Set a language as default (and unset others)
     */
    public function setAsDefault(Language $language): void
    {
        // Unset all other defaults
        $this->entityManager->createQuery(
            'UPDATE App\Entity\Language l SET l.isDefault = false WHERE l.id != :id'
        )->setParameter('id', $language->getId())->execute();

        // Set the new default
        $language->setIsDefault(true);
        $this->entityManager->persist($language);
        $this->entityManager->flush();
    }

    /**
     * Get language by code or fallback to default
     */
    public function getLanguageByCodeOrDefault(string $code): Language
    {
        $language = $this->languageRepository->findByCode($code);
        return $language ?: $this->getDefaultLanguage();
    }

    private function getSession(): ?SessionInterface
    {
        $request = $this->requestStack->getCurrentRequest();
        return $request?->getSession();
    }
}
