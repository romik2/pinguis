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
use App\Entity\ToolStatus;

/**
 * @Route("/tools")
 */
class ToolController extends AbstractController
{
    /**
     * @Route("/", name="dashboard_tool")
     */
    public function dashboard(Request $request, ManagerRegistry $doctrine): Response
    {
        $filters = $request->get('filters', ['deleted' => false]);
        if (!empty($filters['deleted'])) {
            $filters = ['deleted' => true];
        }

        $toolTypes = $doctrine->getRepository(ToolType::class)->findAll();

        return $this->render('tool/index.html.twig', [
            'filters' => $filters,
            'toolTypes' => $toolTypes,
        ]);
    }

    /**
     * @Route("/list", name="list_tool")
     */
    public function list(Request $request, ManagerRegistry $doctrine): Response
    {
        try {
            $filters = $request->get('filters', ['deleted' => false]);
            if (!empty($filters['deleted'])) {
                $filters = ['deleted' => true];
            }
    
            $tools = $doctrine->getRepository(Tool::class)->findBy(array_merge(['user' => $this->getUser()], $filters));
            $toolStatuses = $doctrine->getRepository(ToolStatus::class)->getToolsStatus($tools, 10);

            return new JsonResponse([
                'content' => $this->render('tool/list.html.twig', ['tools' => $tools, 'toolStatuses' => $toolStatuses])->getContent(),
            ]);
        } catch (Exception $ex) {
            return new JsonResponse([
                'error' => $ex->getMessages(),
            ]);
        }
    }

    /**
     * @Route("/details", name="details_tool", methods={"POST"})
     */
    public function details(Request $request, ManagerRegistry $doctrine): Response
    {
        $tool = $doctrine->getRepository(Tool::class)->find($request->get('id'));
        $toolStatuses = $doctrine->getRepository(ToolStatus::class)->getToolsStatus([$tool], 20);

        return new JsonResponse([
            'content' => $this->render('tool/details.html.twig', ['tool' => $tool, 'toolStatuses' => $toolStatuses])->getContent(),
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
