<?php

namespace App\Tests\Service;

use App\Service\MySlugger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Si on on veut où que l'on doit(ve) passer par le conteneur de services
 * C'est mieux !
 */
class MySluggerContainerTest extends WebTestCase
{
    public function testSlugifyToLower(): void
    {
        // On veut accéder au conteneur de services depuis le Kernel
        // (plutôt que de passer notre service en public dans services.yaml)
        self::bootKernel();
        $container = self::$container;
        // On récupère notre service depuis le conteneur
        $mySlugger = $container->get(MySlugger::class);
        // On force le toLower à true
        $mySlugger->setToLower(true);

        // Slugifier une chaine
        $slug = $mySlugger->slugify('Hello World');

        // Vérifier qu'elle est correcte
        $this->assertEquals('hello-world', $slug);
    }

    public function testSlugifyDefault(): void
    {
        // On veut accéder au conteneur de services depuis le Kernel
        // (plutôt que de passer notre service en public dans services.yaml)
        self::bootKernel();
        $container = self::$container;
        // On récupère notre service depuis le conteneur
        $mySlugger = $container->get(MySlugger::class);
        // On force le toLower à true
        $mySlugger->setToLower(false);

        // Slugifier une chaine
        $slug = $mySlugger->slugify('Hello World');

        // Vérifier qu'elle est correcte
        $this->assertEquals('Hello-World', $slug);
    }
}
