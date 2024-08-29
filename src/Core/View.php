<?php
declare(strict_types=1);

namespace App\Core;

use Twig\Environment;

class View implements ViewInterface
{
    private array $parameters = [];

    public function __construct(
        private readonly Environment $twig,
    )
    {}

    public function addParameter(string $key, mixed $value): void
    {
        $this->parameters[$key] = $value;
    }

    //setTempate

    public function display(string $template): void
    {
        echo $this->twig->render($template . '.twig', $this->parameters);
    }
}
