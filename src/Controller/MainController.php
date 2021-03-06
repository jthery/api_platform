<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    private $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }
    /**
     * @Route("/main", name="main")
     */
    public function index()
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    /**
     * @Route("/secured", name="main_secured")
     */
    public function secured(Request $request)
    {
        return $this->render('main/secured.html.twig', [
            // ...
        ]);
    }
}
