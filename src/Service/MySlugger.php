<?php

namespace App\Service;

use Symfony\Component\String\Slugger\SluggerInterface;

class MySlugger
{   
    /**
     * @var SluggerInterface $slugger Le slugger de Symfony
     */
    private $slugger;

    /**
     * @var bool $toLower Paramètre de configuration pour passer en minuscule
     */
    private $toLower;

    public function __construct(SluggerInterface $slugger, bool $toLower)
    {
        $this->slugger = $slugger;
        $this->toLower = $toLower;

    }

    public function slugify(string $string)
    {   
        // On slugifie
        $slug = $this->slugger->slug($string);

        // On lower ou pas ?
        if ($this->toLower) {
            return $slug->lower();
        }

        return $slug;
    }

}
