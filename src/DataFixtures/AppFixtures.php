<?php

namespace App\DataFixtures;

use App\Entity\Language;
use App\Entity\Service;
use App\Entity\ServiceTranslation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Create languages
        $french = new Language();
        $french->setCode('fr');
        $french->setName('Français');
        $french->setIsDefault(true);
        $french->setIsActive(true);
        $manager->persist($french);

        $english = new Language();
        $english->setCode('en');
        $english->setName('English');
        $english->setIsDefault(false);
        $english->setIsActive(true);
        $manager->persist($english);

        $spanish = new Language();
        $spanish->setCode('es');
        $spanish->setName('Español');
        $spanish->setIsDefault(false);
        $spanish->setIsActive(true);
        $manager->persist($spanish);

        // Create services
        $services = [
            [
                'slug' => 'consultation-web',
                'published' => true,
                'translations' => [
                    'fr' => [
                        'title' => 'Consultation Web',
                        'description' => 'Services de consultation pour votre présence en ligne',
                        'detail' => "Notre équipe d'experts vous accompagne dans la création et l'optimisation de votre site web. Nous analysons vos besoins, proposons des solutions adaptées et vous guidons dans votre transformation digitale.\n\nNos services incluent :\n- Audit technique et fonctionnel\n- Stratégie digitale personnalisée\n- Recommandations d'optimisation\n- Accompagnement dans la mise en œuvre"
                    ],
                    'en' => [
                        'title' => 'Web Consultation',
                        'description' => 'Consultation services for your online presence',
                        'detail' => "Our team of experts supports you in creating and optimizing your website. We analyze your needs, propose suitable solutions and guide you in your digital transformation.\n\nOur services include:\n- Technical and functional audit\n- Personalized digital strategy\n- Optimization recommendations\n- Implementation support"
                    ],
                    'es' => [
                        'title' => 'Consultoría Web',
                        'description' => 'Servicios de consultoría para su presencia en línea',
                        'detail' => "Nuestro equipo de expertos le acompaña en la creación y optimización de su sitio web. Analizamos sus necesidades, proponemos soluciones adaptadas y le guiamos en su transformación digital.\n\nNuestros servicios incluyen:\n- Auditoría técnica y funcional\n- Estrategia digital personalizada\n- Recomendaciones de optimización\n- Acompañamiento en la implementación"
                    ]
                ]
            ],
            [
                'slug' => 'developpement-applications',
                'published' => true,
                'translations' => [
                    'fr' => [
                        'title' => 'Développement d\'Applications',
                        'description' => 'Création d\'applications web et mobiles sur mesure',
                        'detail' => "Nous développons des applications web et mobiles performantes, sécurisées et évolutives. Notre approche agile nous permet de livrer des solutions qui répondent parfaitement à vos besoins métier.\n\nTechnologies utilisées :\n- Symfony, React, Vue.js\n- Node.js, Python, PHP\n- Applications mobiles natives et cross-platform\n- Architecture cloud et microservices"
                    ],
                    'en' => [
                        'title' => 'Application Development',
                        'description' => 'Custom web and mobile application development',
                        'detail' => "We develop high-performance, secure and scalable web and mobile applications. Our agile approach allows us to deliver solutions that perfectly meet your business needs.\n\nTechnologies used:\n- Symfony, React, Vue.js\n- Node.js, Python, PHP\n- Native and cross-platform mobile applications\n- Cloud architecture and microservices"
                    ]
                ]
            ],
            [
                'slug' => 'formation-digitale',
                'published' => false,
                'translations' => [
                    'fr' => [
                        'title' => 'Formation Digitale',
                        'description' => 'Programmes de formation pour monter en compétences digitales',
                        'detail' => "Nous proposons des formations personnalisées pour vous aider à maîtriser les outils et technologies digitales. Nos programmes s'adaptent à votre niveau et à vos objectifs.\n\nFormations disponibles :\n- Développement web (HTML, CSS, JavaScript)\n- Frameworks modernes (React, Vue.js, Angular)\n- Gestion de projets digitaux\n- Marketing digital et SEO"
                    ],
                    'en' => [
                        'title' => 'Digital Training',
                        'description' => 'Training programs to develop digital skills',
                        'detail' => "We offer personalized training to help you master digital tools and technologies. Our programs adapt to your level and objectives.\n\nAvailable training:\n- Web development (HTML, CSS, JavaScript)\n- Modern frameworks (React, Vue.js, Angular)\n- Digital project management\n- Digital marketing and SEO"
                    ],
                    'es' => [
                        'title' => 'Formación Digital',
                        'description' => 'Programas de formación para desarrollar competencias digitales',
                        'detail' => "Ofrecemos formación personalizada para ayudarle a dominar las herramientas y tecnologías digitales. Nuestros programas se adaptan a su nivel y objetivos.\n\nFormación disponible:\n- Desarrollo web (HTML, CSS, JavaScript)\n- Frameworks modernos (React, Vue.js, Angular)\n- Gestión de proyectos digitales\n- Marketing digital y SEO"
                    ]
                ]
            ]
        ];

        foreach ($services as $serviceData) {
            $service = new Service();
            $service->setSlug($serviceData['slug']);
            $service->setIsPublished($serviceData['published']);

            foreach ($serviceData['translations'] as $langCode => $translation) {
                $language = null;
                switch ($langCode) {
                    case 'fr':
                        $language = $french;
                        break;
                    case 'en':
                        $language = $english;
                        break;
                    case 'es':
                        $language = $spanish;
                        break;
                }

                if ($language) {
                    $serviceTranslation = new ServiceTranslation();
                    $serviceTranslation->setLanguage($language);
                    $serviceTranslation->setTitle($translation['title']);
                    $serviceTranslation->setDescription($translation['description']);
                    $serviceTranslation->setDetail($translation['detail']);
                    $service->addTranslation($serviceTranslation);
                }
            }

            $manager->persist($service);
        }

        $manager->flush();
    }
}
