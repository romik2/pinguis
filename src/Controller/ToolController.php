<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Tool;

/**
 * @Route("/tools")
 */
class ToolController extends AbstractController
{
    /**
     * @Route("/", name="dashboard_tool")
     */
    public function listTool(): Response
    {
        $tools = $this->getDoctrine()->getRepository(Tool::class)->findAll();
        return $this->render('tool/index.html.twig', [
            'tools' => $tools,
        ]);
    }

    /**
     * @Route("/details/{id}", name="details")
     */
    public function details(Request $request): Response
    {
        $tool = $this->getDoctrine()->getRepository(Tool::class)->find($request->get('id'));
        return new JsonResponse([
            'content' => $this->render('tool/index.html.twig', ['tools' => []])->getContent(),
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function addTool(Request $request): Response
    {
        // $toolDetails = $request->get('tool');
        $tool = new Tool();
        // $tool->setAddress($toolDetails['address'])->setName($toolDetails['name']);
        $tool->setAddress('192.168.1.1')->setName('router');
        $this->getDoctrine()->getManager()->persist($tool);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function editTool(Request $request, Tool $tool): Response
    {
        $tool = $this->getDoctrine()->getRepository(Tool::class)->find($request->get('id'));
        $tool->setAddress($toolDetails['address'])->setName($toolDetails['name']);
        $this->getDoctrine()->getManager()->persist($tool);
        return $this->redirect($request->headers->get('referer'));
    }
}
