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
     * @Route("/", name="dashboard_tool", methods={"GET"})
     */
    public function listTool(): Response
    {
        $tools = $this->getDoctrine()->getRepository(Tool::class)->findAll();
        return $this->render('tool/index.html.twig', [
            'tools' => $tools,
        ]);
    }

    /**
     * @Route("/details", name="details_tool", methods={"GET"})
     */
    public function details(Request $request): Response
    {
        $tool = $this->getDoctrine()->getRepository(Tool::class)->find($request->get('id'));
        return new JsonResponse([
            'content' => $this->render('tool/details.html.twig', ['tool' => $tool])->getContent(),
        ]);
    }

    /**
     * @Route("/add", name="add_tool", methods={"POST"})
     */
    public function addTool(Request $request): Response
    {
        $toolDetails = $request->get('tool');
        $tool = new Tool();
        $tool->setAddress($toolDetails['address'])->setName($toolDetails['name']);
        $this->getDoctrine()->getManager()->persist($tool);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/edit/{id}", name="edit_tool", methods={"POST"})
     */
    public function editTool(Request $request, Tool $tool): Response
    {
        $tool = $this->getDoctrine()->getRepository(Tool::class)->find($request->get('id'));
        $tool->setAddress($toolDetails['address'])->setName($toolDetails['name']);
        $this->getDoctrine()->getManager()->persist($tool);
        return $this->redirect($request->headers->get('referer'));
    }
}
