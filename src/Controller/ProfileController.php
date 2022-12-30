<?php

namespace App\Controller;

use App\Entity\Setting;
use App\Entity\User;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchy;


/**
 * @Route("/profile/")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("{id}/details", name="profile_details")
     */
    public function detailsProfile(User $user, ManagerRegistry $doctrine): Response
    {
        if ($this->getUser()->getId() != $user->getId() && !in_array("ROLE_ADMIN", $this->getUser()->getRoles())) {
            return $this->redirectToRoute('dashboard');
        }

        $roles = $this->getParameter('security.role_hierarchy.roles');
        $settings = $doctrine->getRepository(Setting::class)->findAll();

        return $this->render('profile/details.html.twig', [
            'user' => $user,
            'settings' => $settings,
            'roles' => $roles,
        ]);
    }

    /**
     * @Route("{id}/edit", name="profile_edit")
     */
    public function editProfile(Request $request, User $user, ManagerRegistry $doctrine): Response
    {
        if ($this->getUser()->getId() != $user->getId() || !in_array("ROLE_ADMIN", $this->getUser()->getRoles())) {
            return $this->redirectToRoute('dashboard');
        }

        foreach ($request->get('user') as $userFieldKey => $userField) {
            $name = lcfirst($userFieldKey);
            $function = "set$name";
            $user->$function($userField);
        }

        if (in_array("ROLE_ADMIN", $this->getUser()->getRoles())) {
            $user->setRoles([$request->get('role')]);
        }

        $doctrine->getManager()->persist($user);
        $doctrine->getManager()->flush();
        return $this->redirect($request->headers->get('referer'));
    }
}
