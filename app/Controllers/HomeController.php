<?php

namespace Natom\Ecoride\Controllers;

use Natom\Ecoride\Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->render("home");
    }
}
