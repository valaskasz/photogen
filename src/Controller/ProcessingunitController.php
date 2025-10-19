<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProcessingunitController extends AbstractController
{
    private function setvariables($processingunit,&$hasServer,&$securitytoken,&$reliability,&$taskscompleted,&$hardware){
        $hasServer=true;
        $securitytoken=$processingunit->getSecuritytoken();
        $reliability=$processingunit->getReliability();
        $taskscompleted=$processingunit->getTaskscompleted();
        $hardware=$processingunit->getHardware();
    }
    #[Route('/registerprocessingunit/{mode?}', name: 'app_registerprocessingunit')]
    public function index(?String $mode,Request $request, EntityManagerInterface $em): Response
    {
        $hasServer=false;
        $securitytoken='';
        $reliability=0;
        $taskscompleted=0;
        $hardware='';
        $processingunit=$em->getRepository(\App\Entity\Processingunit::class)->findOneBy(['user'=>$this->getUser()]);
        if ($processingunit) {
            $this->setvariables($processingunit,$hasServer,$securitytoken,$reliability,$taskscompleted,$hardware);
           
        }

        if ($request->isMethod('POST') && $mode && $mode=='register') {
                $processingunit = new \App\Entity\Processingunit();
                $processingunit->setUser($this->getUser());
                $processingunit->setTaskscompleted(0);
                $processingunit->setReliability(0);
                $processingunit->setSecuritytoken(bin2hex(random_bytes(32)));
            $processingunit->setHardware($request->get('hardware'));
            $em->persist($processingunit);
            $em->flush();
            $this->setvariables($processingunit,$hasServer,$securitytoken,$reliability,$taskscompleted,$hardware);
            return $this->redirectToRoute('app_registerprocessingunit');
        }

        if ($request->isMethod('POST') && $mode &&$mode=="change"){
            if ($processingunit) {
                $processingunit->setHardware($request->get('hardware'));
                $em->persist($processingunit);
                $em->flush();
                $this->setvariables($processingunit,$hasServer,$securitytoken,$reliability,$taskscompleted,$hardware);
                return $this->redirectToRoute('app_registerprocessingunit');
            }
        }

        return $this->render('processingunit/processingunit.html.twig', [
            'controller_name' => 'ProcessingunitController',
            'hasServer'=>$hasServer,
            'securitytoken'=>$hasServer?$securitytoken:'',
            'reliability'=>$hasServer?$reliability:0,
            'taskscompleted'=>$hasServer?$taskscompleted:0,
            'hardware'=>$hasServer?$hardware:'',

        ]);
    }
}
