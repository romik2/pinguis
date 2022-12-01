<?php

namespace App\Controller;

use App\Entity\Setting;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/settings")
 */
class SettingController extends AbstractController
{
    /**
     * @Route("edit", name="settings_edit")
     */
    public function editSettings(Request $request, ManagerRegistry $doctrine): Response
    {
        foreach ($request->get('settings') as $settingId => $settingValue) {
            $setting = $doctrine->getRepository(Setting::class)->find($settingId);
            $setting->setValue($settingValue);
            $doctrine->getManager()->persist($setting);
        }
        $doctrine->getManager()->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
