<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Tool;
use App\Entity\ToolType;

/**
 * @Route("/tools")
 */
class ToolController extends AbstractController
{
    /**
     * @Route("/", name="dashboard_tool")
     */
    public function listTool(Request $request): Response
    {
        $filters = $request->get('filters');
        if (!empty($filters['deleted'])) {
            $filters = ['deleted' => true];
        } else {
            $filters = ['deleted' => false];
        }
        $tools = $this->getDoctrine()->getRepository(Tool::class)->findBy(array_merge(['user' => $this->getUser()], $filters));
        $toolTypes = $this->getDoctrine()->getRepository(ToolType::class)->findAll();

        return $this->render('tool/index.html.twig', [
            'tools' => $tools,
            'filters' => $filters,
            'toolTypes' => $toolTypes,
        ]);
    }

    /**
     * @Route("/details", name="details_tool", methods={"POST"})
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
        $tool = new Tool();
        $toolDetails = $request->get('tool');
        $toolType = $this->getDoctrine()->getRepository(ToolType::class)->find($toolDetails['type']);
        
        $tool->setAddress($toolDetails['address'])->setName($toolDetails['name'])->setUser($this->getUser())->setType($toolType);

        $this->getDoctrine()->getManager()->persist($tool);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/edit/{id}", name="edit_tool", methods={"POST"})
     */
    public function editTool(Request $request): Response
    {
        $toolDetails = $request->get('tool');
        $tool = $this->getDoctrine()->getRepository(Tool::class)->find($request->get('id'));
        $toolType = $this->getDoctrine()->getRepository(ToolType::class)->find($toolDetails['type']);

        $tool->setAddress($toolDetails['address'])->setName($toolDetails['name'])->setType($toolType);

        $this->getDoctrine()->getManager()->persist($tool);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/delete/{id}", name="delete_tool")
     */
    public function deleteTool(Request $request, Tool $tool): Response
    {
        $tool = $this->getDoctrine()->getRepository(Tool::class)->find($request->get('id'));
        $tool->setDeleted(true);
        $this->getDoctrine()->getManager()->persist($tool);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/undelete/{id}", name="undelete_tool")
     */
    public function unDeleteTool(Request $request, Tool $tool): Response
    {
        $tool = $this->getDoctrine()->getRepository(Tool::class)->find($request->get('id'));
        $tool->setDeleted(false);
        $this->getDoctrine()->getManager()->persist($tool);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($request->headers->get('referer'));
    }
}
