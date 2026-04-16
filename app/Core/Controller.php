<?php

namespace Natom\Ecoride\Core;

class Controller
{
    protected function render(string $view, array $data = []): void
    {
        extract($data);

        ob_start();

        require __DIR__ . '/../Views/' . $view . '.php';

        $content = ob_get_clean();

        require __DIR__ . '/../Views/layout.php';
    }
}
