<?php

namespace App\Controller;

use App\Entity\Setting;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/details", name="profile_details")
     */
    public function detailsProfile(ManagerRegistry $doctrine): Response
    {
        $settings = $doctrine->getRepository(Setting::class)->findAll();

        return $this->render('profile/details.html.twig', [
            'user' => $this->getUser(),
            'settings' => $settings,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="profile_edit")
     */
    public function editProfile(Request $request, ManagerRegistry $doctrine): Response
    {
        $user = $doctrine->getRepository(User::class)->find($request->get('id'));
        foreach ($request->get('user') as $userFieldKey => $userField) {
            $name = lcfirst($userFieldKey);
            $function = "set$name";
            $user->$function($userField);
        }
        $doctrine->getManager()->persist($user);
        $doctrine->getManager()->flush();
        return $this->redirect($request->headers->get('referer'));
    }
}
