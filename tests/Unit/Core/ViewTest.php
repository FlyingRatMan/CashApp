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
    private $reflection;
    private $twigPath;

    protected function setUp(): void
    {
        parent::setUp();

        $loader = new FilesystemLoader('src/View/templates');
        $twig = new Environment($loader, [
            'debug' => true,
            'cache' => false,
        ]);

        $this->view = new View($twig);
        $this->reflection = new \ReflectionClass($this->view);

        $this->twigPath =  __DIR__ . '/../../../src/View/templates/test.twig';
        $template = <<<'TEMPLATE'
                <html>
                    <div>{{ test }}</div>
                </html>
            TEMPLATE;
        file_put_contents($this->twigPath, $template);
    }

    protected function tearDown(): void
    {
        if (file_exists($this->twigPath)) {
            unlink($this->twigPath);
        }

        parent::tearDown();
    }

    public function testAddParameter(): void
    {
        $this->view->addParameter('key', 'value');

        $parametersProperty = $this->reflection->getProperty('parameters');
        $parameters = $parametersProperty->getValue($this->view);

        $this->assertArrayHasKey('key', $parameters);
        $this->assertSame('value', $parameters['key']);
    }

    public function testSetTemplate(): void
    {
        $this->view->setTemplate('index.twig');

        $templateProperty = $this->reflection->getProperty('template');
        $template = $templateProperty->getValue($this->view);

        $this->assertSame('index.twig', $template);
    }

    public function testRedirect(): void
    {
        $this->view->setRedirect('/index.php?page=login');

        $redirectProperty = $this->reflection->getProperty('redirectTo');
        $redirect = $redirectProperty->getValue($this->view);

        $this->assertSame('Location: /index.php?page=login', $redirect);
    }

    public function testDisplay(): void
    {
        $expected = <<<'EXPECTED'
                <html>
                    <div>Testing template rendering...</div>
                </html>
            EXPECTED;

        $this->view->setTemplate('test.twig');
        $this->view->addParameter('test', 'Testing template rendering...');

        $result = $this->view->display();

        $this->assertSame($expected, $result);
    }

    public function testDisplayIfRedirectSet(): void
    {
        $this->view->setRedirect('/index.php?page=login');

        $this->assertNull($this->view->display());
    }
}