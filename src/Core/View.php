<?php
declare(strict_types=1);

namespace App\Core;

use Twig\Environment;

class View implements ViewInterface
{
    private array $parameters = [];
    private string $template;

    public function __construct(
        private readonly Environment $twig,
    ) {}

    public function addParameter(string $key, mixed $value): void
    {
        $this->parameters[$key] = $value;
    }

    public function setTemplate(string $template): void
    {
        $this->template = $template;
    }

    public function display(): void
    {
        echo $this->twig->render($this->template, $this->parameters);
    }
}
