<?php 

namespace tests\Service;


use App\Service\MySlugger;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;



class MySluggerTest extends KernelTestCase   
{
    public function testSlugify()
    {
        self::bootKernel();

        $symfonySlugger = new AsciiSlugger();
        $mySlugger = new MySlugger($symfonySlugger, false);

        //$container = self::$container;

        //$mySlugger = $container->get(MySlugger::class);


        $this->assertEquals(
            'cours-forrest-cours',
            $mySlugger->slugify('cours forrest cours')
        );
        

    
    }
}
