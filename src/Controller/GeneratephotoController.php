<?php

namespace App\Controller;

use App\Entity\Genrequest;
use App\Entity\Processingunit;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GeneratephotoController extends AbstractController
{   
    #[Route('/getuserbytoken', name: 'app_test')]
    public function getuserbytoken(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $JsonData = json_decode($request->getContent(), true);
        $genrequest = $em->getRepository(Genrequest::class)->findOneBy(['securitytoken' => $JsonData['securitytoken']]);
        if ($genrequest) {
            return new JsonResponse(['status' => 'ok', 'user' => $genrequest->getUser()->getUsername()]);
        } else {
            return new JsonResponse(['status' => 'error', 'message' => 'Token not found'], 404);
        }
    }

    //ToDo: set the processing unit id
    #[Route('/confirmphotorequest', name: 'app_confirmphotorequest')]
    public function confirmrequest(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $JsonData = json_decode($request->getContent(), true);
        $genrequest = $em->getRepository(Genrequest::class)->find($JsonData['id']);
        $prcessingunit = $em->getRepository(Processingunit::class)->findOneBy(['securitytoken' => $JsonData['securitytoken']]);
        if ($genrequest) {
            $genrequest->setStartprocessing(new \DateTime());
            $genrequest->setProcessingunit($prcessingunit);
            
            $em->persist($genrequest);
            $em->flush();
            return new JsonResponse(['status' => 'ok']);
        } else {
            return new JsonResponse(['status' => 'error', 'message' => 'Request not found'], 404);
        }
    }

    #[Route('/closephotorequest', name: 'app_closephotorequest')]
    public function closerequest(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $JsonData = json_decode($request->getContent(), true);
        $genrequest = $em->getRepository(Genrequest::class)->find($JsonData['id']);
        if (!$JsonData['refused']) {
            $genrequest->setEndprocessing(new \DateTime());
            $genrequest->setGenerated(true);
            $genrequest->setRefused(false);
            $em->persist($genrequest);
            $em->flush();
            return new JsonResponse(['status' => 'ok']);}
        else {
            $genrequest->setEndprocessing(new \DateTime());
            $genrequest->setGenerated(false);
            $genrequest->setRefused(true);
            $genrequest->setRefusereason($JsonData['refusereason']);
            $em->persist($genrequest);
            $em->flush();
            return new JsonResponse(['status' => 'ok']);
        }
    }

    //Get the highest priority task for backend
    #[Route('/getgentask', name: 'app_getgentask')]
    public function getgentask(EntityManagerInterface $em): JsonResponse
    {
        $genrequest = $em->getRepository(Genrequest::class)->createQueryBuilder('g')
            ->Where('g.startprocessing IS NULL')
            ->orderBy('g.priority', 'DESC')
            ->addOrderBy('g.dcreated', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        $result = [];

        if ($genrequest) {
            $result = [
                'id' => $genrequest->getId(),
                'dcreated' => $genrequest->getDcreated()->format('Y-m-d H:i:s'),
                'promptpositive' => $genrequest->getPromtpositive(),
                'promptnegative' => $genrequest->getPromtnegative(),
                'resolution' => $genrequest->getResolution(),
                'priority' => $genrequest->getPriority(),
                'modelname' => $genrequest->getModelname(),
                'user' => $genrequest->getUser()->getUsername(),
                'inputfile' => $genrequest->getInputfile() ? [
                    'id' => $genrequest->getInputfile()->getId(),
                    'filename' => $genrequest->getInputfile()->getFilename(),
                    'directory' => $genrequest->getInputfile()->getDirectory(),
                ] : null,
                'useinputfile' => $genrequest->isUseinputfile(),
            ];
        }

        return new JsonResponse($result);
    }

    //Get the user last 10 tasks - for frontend
    #[Route('/getlastusertasks', name: 'app_getlastusertasks')]
public function getlasttasks(EntityManagerInterface $em): JsonResponse
{
    $user = $this->getUser();
    $genrequests = $em->getRepository(Genrequest::class)->findBy(
        ['user' => $user],
        ['dcreated' => 'DESC'],
        10
    );

    $result = [];
    foreach ($genrequests as $genrequest) {
        $inputFile = $genrequest->getInputfile();
        $fileData = null;

        if ($inputFile) {
            $fileData = [
                'id' => $inputFile->getId(),
                'filename' => $inputFile->getFilename(),
                'directory' => $inputFile->getDirectory(),
            ];
        }

        $result[] = [
            'id' => $genrequest->getId(),
            'dcreated' => $genrequest->getDcreated()->format('Y-m-d H:i:s'),
            'promptpositive' => $genrequest->getPromtpositive(),
            'promptnegative' => $genrequest->getPromtnegative(),
            'resolution' => $genrequest->getResolution(),
            'priority' => $genrequest->getPriority(),
            'modelname' => $genrequest->getModelname(),
            'user' => $genrequest->getUser()->getUserIdentifier(), // vagy getUsername(), attól függően
            'inputfile' => $fileData,
            'useinputfile' => $genrequest->isUseinputfile(),
        ];
    }

    return new JsonResponse($result);
}



    //Record a generate photo request to database
    #[Route('/generatephotorow', name: 'app_generatephotorow')]
    public function recordphotorequest(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $JsonData = json_decode($request->getContent(), true);
        $genrequest = new Genrequest();
        $genrequest->setPromtpositive($JsonData['promptpositive'])
        ->setUser($this->getUser())
        ->setDcreated(new \DateTime());
        $genrequest->setPromtnegative($JsonData['promptnegative']);
        $genrequest->setResolution($JsonData['resolution']);
        $genrequest->setPriority($JsonData['priority']);
        $genrequest->setModelname($JsonData['modelname']);
        $inputfile=$em->getRepository(\App\Entity\File::class)->findOneBy(['id' => $JsonData['fileid']]);
        $genrequest->setInputfile( $inputfile );
        $genrequest->setUseinputfile($JsonData['loadImage']);
        $em->persist($genrequest);
        $em->flush();

        $JsonData['id'] = $genrequest->getId();
        $JsonData['dcreated'] = $genrequest->getDcreated()->format('Y-m-d H:i:s');
        $JsonData['promptpositive'] = $genrequest->getPromtpositive();
        $JsonData['promptnegative'] = $genrequest->getPromtnegative();
        $JsonData['resolution'] = $genrequest->getResolution();
        $JsonData['priority'] = $genrequest->getPriority();
        $JsonData['loadimage'] = $genrequest->isUseinputfile();
        $JsonData["fileid"] = $inputfile ? $inputfile->getId() : null;
        
        return new JsonResponse($JsonData);
    }

    #[Route('/generatephoto', name: 'app_generatephoto')]
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('generatephoto/genphoto.html.twig', [
            'controller_name' => 'GeneratephotoController',
        ]);
    }
}
