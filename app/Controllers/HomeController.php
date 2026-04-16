<?php

namespace Natom\Ecoride\Controllers;

use Natom\Ecoride\Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->render("home");
    }

    // ✅ Mentions légales
    public function mentions(): void
    {
        $this->render("pages/mentions");
    }

    // ✅ CGU
    public function cgu(): void
    {
        $this->render("pages/cgu");
    }
}
