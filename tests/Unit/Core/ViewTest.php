<?php
declare(strict_types=1);

namespace Unit\Core;

use App\Core\View;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ViewTest extends TestCase
{
    private View $view;

    protected function setUp(): void
    {
        $loader = new FilesystemLoader('src/View/templates');
        $twig = new Environment($loader, [
            'debug' => true,
            'cache' => false,
        ]);

        $this->view = new View($twig);
    }

    public function testAddParameter(): void
    {
        $this->view->addParameter('key', 'value');

        $reflection = new \ReflectionClass($this->view);
        $parametersProperty = $reflection->getProperty('parameters');
        $parameters = $parametersProperty->getValue($this->view);

        $this->assertArrayHasKey('key', $parameters);
        $this->assertSame('value', $parameters['key']);
    }

    public function testSetTemplate(): void
    {
        $this->view->setTemplate('index.twig');

        $reflection = new \ReflectionClass($this->view);
        $templateProperty = $reflection->getProperty('template');
        $template = $templateProperty->getValue($this->view);

        $this->assertSame('index.twig', $template);
    }

    /*public function testDisplay(): void
    {
        $expected = <<<'EXPECTED'
        <html>
            <div>Awesome template here</div>
        </html>
        EXPECTED;


    }*/
}