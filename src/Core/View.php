<?php
declare(strict_types=1);

namespace App\Core;

use Twig\Environment;

class View implements ViewInterface
{
    protected array $parameters = [];
    protected string $template;
    private string $redirectTo;

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

    public function display(): ?string
    {
        if(!empty($this->redirectTo)) {
            header($this->redirectTo);
            return null;
        }

        return $this->twig->render($this->template, $this->parameters);
    }

    public function setRedirect(string $to): void
    {
        $this->redirectTo = "Location: " . $to;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getRedirectTo(): string
    {
        return $this->redirectTo;
    }
}
