<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ToolController extends AbstractController
{
    /**
     * @Route("/", name="app_tool")
     */
    public function index(): Response
    {
        return $this->render('tool/index.html.twig', [
            'controller_name' => 'ToolController',
        ]);
    }
}
