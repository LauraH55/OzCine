<?php

namespace App\Tests\Service;

use App\Service\MySlugger;
use PHPUnit\Framework\TestCase;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * Si on on veut et que l'on peut tester un service sans passer le conteneur
 */
class MySluggerTest extends TestCase
{
    public function testSlugifyToLower(): void
    {
        // Instancier notre MySlugger
        // à la mano (sans passer par le conteneur de Service)

        // On a donc besoin de la classe AsciiSlugger
        $asciiSlugger = new AsciiSlugger();

        // LOWER
        // On peut donner les arguments au constructeur
        $mySlugger = new MySlugger($asciiSlugger, true);

        // Slugifier une chaine
        $slug = $mySlugger->slugify('Hello World');

        // Vérifier qu'elle est correcte
        $this->assertEquals('hello-world', $slug);
    }

    public function testSlugifyDefault(): void
    {
        // Instancier notre MySlugger
        // à la mano (sans passer par le conteneur de Service)

        // On a donc besoin de la classe AsciiSlugger
        $asciiSlugger = new AsciiSlugger();

        // UPPER (normal/par défaut)
        // On peut donner les arguments au constructeur
        $mySlugger = new MySlugger($asciiSlugger, false);

        // Slugifier une chaine
        $slug = $mySlugger->slugify('Hello World');

        // Vérifier qu'elle est correcte
        $this->assertEquals('Hello-World', $slug);
    }
}
