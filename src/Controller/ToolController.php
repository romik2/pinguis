<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
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
    public function listTool(Request $request, ManagerRegistry $doctrine): Response
    {
        $filters = $request->get('filters');
        if (!empty($filters['deleted'])) {
            $filters = ['deleted' => true];
        } else {
            $filters = ['deleted' => false];
        }
        $tools = $doctrine->getRepository(Tool::class)->findBy(array_merge(['user' => $this->getUser()], $filters));
        $toolTypes = $doctrine->getRepository(ToolType::class)->findAll();

        return $this->render('tool/index.html.twig', [
            'tools' => $tools,
            'filters' => $filters,
            'toolTypes' => $toolTypes,
        ]);
    }

    /**
     * @Route("/details", name="details_tool", methods={"POST"})
     */
    public function details(Request $request, ManagerRegistry $doctrine): Response
    {
        $tool = $doctrine->getRepository(Tool::class)->find($request->get('id'));
        return new JsonResponse([
            'content' => $this->render('tool/details.html.twig', ['tool' => $tool])->getContent(),
        ]);
    }

    /**
     * @Route("/add", name="add_tool", methods={"POST"})
     */
    public function addTool(Request $request, ManagerRegistry $doctrine): Response
    {
        $tool = new Tool();
        $toolDetails = $request->get('tool');
        $toolType = $doctrine->getRepository(ToolType::class)->find($toolDetails['type']);
        
        $tool->setAddress($toolDetails['address'])->setName($toolDetails['name'])->setUser($this->getUser())->setType($toolType);

        $doctrine->getManager()->persist($tool);
        $doctrine->getManager()->flush();
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/edit/{id}", name="edit_tool", methods={"POST"})
     */
    public function editTool(Request $request, ManagerRegistry $doctrine): Response
    {
        $toolDetails = $request->get('tool');
        $tool = $doctrine->getRepository(Tool::class)->find($request->get('id'));
        $toolType = $doctrine->getRepository(ToolType::class)->find($toolDetails['type']);

        $tool->setAddress($toolDetails['address'])->setName($toolDetails['name'])->setType($toolType);

        $doctrine->getManager()->persist($tool);
        $doctrine->getManager()->flush();
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/delete/{id}", name="delete_tool")
     */
    public function deleteTool(Request $request, Tool $tool, ManagerRegistry $doctrine): Response
    {
        $tool = $doctrine->getRepository(Tool::class)->find($request->get('id'));
        $tool->setDeleted(true);
        $doctrine->getManager()->persist($tool);
        $doctrine->getManager()->flush();
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/undelete/{id}", name="undelete_tool")
     */
    public function unDeleteTool(Request $request, Tool $tool, ManagerRegistry $doctrine): Response
    {
        $tool = $doctrine->getRepository(Tool::class)->find($request->get('id'));
        $tool->setDeleted(false);
        $doctrine->getManager()->persist($tool);
        $doctrine->getManager()->flush();
        return $this->redirect($request->headers->get('referer'));
    }
}
