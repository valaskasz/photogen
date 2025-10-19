<?php

namespace App\Controller;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

final class FilehandlingController extends AbstractController
{
    //As a request, manage a file upload. Incoming parameters: file binary, 
    //Returns: id of created File entity, 
    //ToDo: set the processing unit id as shared resource when it is possible
#[Route('/generatedfilesave', name: 'app_generatedfilesave', methods: ['POST'])]
public function generatedfilesave(Request $request, EntityManagerInterface $em): Response
{
    $uploadedFile = $request->files->get('file');
    if (!$uploadedFile) {
        return $this->json(['status' => 'error', 'message' => 'No file uploaded'], 400);
    }

    $filename = uniqid('generated_') . '.' . $uploadedFile->guessExtension();
    $uploadDir = $this->getParameter('upload_directory');
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $uploadedFile->move($uploadDir, $filename);

    $file = new \App\Entity\File();
    $file->setFilename($filename);

    $processingunit = $em->getRepository(\App\Entity\Processingunit::class)
        ->find($_ENV['DEFAULTFILEPROCESSINGUNIT']);
    $file->setProcessingunit($processingunit);

    $file->setDirectory((new \DateTime())->format('Y/m/d'));

    $em->persist($file);
    $em->flush();

    //Save the relation in Genfiles
    $genfile = new \App\Entity\Genfiles();
    $genfile->setGenrequest($em->getRepository(\App\Entity\Genrequest::class)->find($request->request->get('genrequestid')));
    $genfile->setFile($file);
    $em->persist($genfile);
    $em->flush();

    //Make a record in Filerequest to store the file
    $filerequest = new \App\Entity\Filerequest();
    $filerequest->setFile($file);
    $filerequest->setTostore(true);
    $filerequest->setUser($em->getRepository(\App\Entity\Genrequest::class)->find($request->request->get('genrequestid'))->getUser());
    $filerequest->setDcreated(new DateTime());
    $filerequest->setFinished(false);
    $em->persist($filerequest);
    $em->flush();

    return $this->json([
        'status'   => 'ok',
        'id'       => $file->getId(),
        'filename' => $file->getFilename(),
    ]);
}
    //The file server upload a file to 'Uploads' directory to be available for download
    #[Route('/fileuploadbyfileserver', name: 'app_fileuploadbyfileserver', methods: ['POST'])]
    public function fileupload(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            return $this->json(['status' => 'error', 'message' => 'No file uploaded'], 400);
        }
        $filerequest=$em->getRepository(\App\Entity\Filerequest::class)->find($request->request->get('filerequestid'));
        $file=$filerequest->getFile();
        $filename = $file->getFilename();
        $uploadDir = $this->getParameter('upload_directory');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $uploadedFile->move($uploadDir, $filename);
        $filerequest->setFinished(true);
        $filerequest->setDfinished(new DateTime());
        $em->persist($filerequest);
        $em->flush();
        return $this->json(['status' => 'ok', 'filename' => $filename]);
    }

    //The file server upload a file to 'Uploads' directory to be available for download
    #[Route('/useruploadfile', name: 'app_useruploadfile', methods: ['POST'])]
    public function userfileupload(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            return $this->json(['status' => 'error', 'message' => 'No file uploaded'], 400);
        }
        $filename = uniqid('userfile_') . '.' . $uploadedFile->guessExtension();
        $uploadDir = $this->getParameter('upload_directory');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $uploadedFile->move($uploadDir, $filename);
        $file = new \App\Entity\File();
        $file->setFilename($filename);
        $processingunit = $em->getRepository(\App\Entity\Processingunit::class)
            ->find($_ENV['DEFAULTFILEPROCESSINGUNIT']);
        $file->setProcessingunit($processingunit);
        $file->setDirectory((new \DateTime())->format('Y/m/d'));
        $em->persist($file);

        $fileuser = new \App\Entity\Fileuser();
        $fileuser->setFile($file);
        $fileuser->setUser($this->getUser());
        $fileuser->setDcreated(new DateTime());
        $em->persist($fileuser);
        $em->flush();

        $filerequest = new \App\Entity\Filerequest();
        $filerequest->setFile($file);
        $filerequest->setTostore(true);
        $filerequest->setUser($this->getUser());
        $filerequest->setDcreated(new DateTime());
        $filerequest->setFinished(false);
        $em->persist($filerequest);
        $em->flush();

        return $this->json(['status' => 'ok', 'filename' => $filename, 'fileid' => $file->getId()]);
    }


    #[Route('/isFileOnServer', name: 'app_isFileOnServer', methods: ['POST'])]
    public function isFileOnServer(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $JsonData = json_decode($request->getContent(), true);
        $file = $em->getRepository(\App\Entity\File::class)->findOneBy(['id' => $JsonData['fileid']]);
        if ($file) {
            $searchdir = $this->getParameter('upload_directory');
            $filepath = $searchdir . '/' . $file->getFilename();
            if (file_exists($filepath)) {
                return new JsonResponse(['status' => 'ok', 'fileid' => $file->getId()]);
            } else {
                return new JsonResponse(['status' => 'ok', 'message' => 'File recorded but file not found on server'], 404);
            }
        } else {
            return new JsonResponse(['status' => 'error', 'message' => 'File not found'], 404);
        }
    }




    //Return the oldest not closed, not started file request. 
    #[Route('/getfilerequest', name: 'app_filedownload', methods: ['GET'])]
    public function getfilerequest(EntityManagerInterface $em): Response
    {
        $filerequest = $em->getRepository(\App\Entity\Filerequest::class)->createQueryBuilder('f')
            ->where('f.finished = :finished')
            ->andWhere('f.started IS NULL OR f.started = false')
            ->setParameter('finished', false)
            ->orderBy('f.dcreated', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$filerequest) {
            return $this->json(['status' => 'error', 'message' => 'No pending file requests'], 404);
        }

        //Mark as started
        $filerequest->setStarted(true);
        $em->persist($filerequest);
        $em->flush();

        return $this->json([
            'status' => 'ok',
            'id'     => $filerequest->getId(),
            'file'   => [
                'id'       => $filerequest->getFile()->getId(),
                'filename' => $filerequest->getFile()->getFilename(),
                'directory'=> $filerequest->getFile()->getDirectory(),
            ],
            'user'   => $filerequest->getUser()->getUsername(),
            'tostore'=> $filerequest->isTostore(),
        ]);
    }

    //Mark a filerequest as finished
    #[Route('/markfilerequestfinished/{id}', name: 'app_markfilerequestfinished', methods: ['POST'])]
    public function markfilerequestfinished(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $filerequest = $em->getRepository(\App\Entity\Filerequest::class)->find($id);
        if (!$filerequest) {
            return $this->json(['status' => 'error', 'message' => 'Filerequest not found'], 404);
        }
        //Delete file from upload directory
        $uploadDir = $this->getParameter('upload_directory');
        $filePath = $uploadDir . '/' . $filerequest->getFile()->getFilename();
        if (file_exists($filePath)) {
            unlink($filePath);
        }
       // $filerequest->setFinished(true);
       // $filerequest->setDfinished(new DateTime());

        //Delete the current Filerequest
        $em->remove($filerequest);
        $em->flush();

        //$em->persist($filerequest);
        //$em->flush();
        return $this->json(['status' => 'ok']);
    }    

    #[Route('/getfileinfo/{id}','app_getfileinfo', methods: ['GET'])] 
    function getfileinfo(int $id, EntityManagerInterface $em): Response
    {
        $file = $em->getRepository(\App\Entity\File::class)->find($id);
        if (!$file) {
            return $this->json(['status' => 'error', 'message' => 'File not found'], 404);
        }

        return $this->json([
            'status' => 'ok',
            'file'   => [
                'id'       => $file->getId(),
                'filename' => $file->getFilename(),
                'directory'=> $file->getDirectory(),
            ],
        ]);
    }
    
    //Create a filerequest for an existing file --for testing purposes
    #[Route('/addfileuploadrequest/{fileid}','app_fileuploadrequest', methods: ['GET'])]
    function addfileuploadrequest(int $fileid, EntityManagerInterface $em): Response
    {
        $file = $em->getRepository(\App\Entity\File::class)->find($fileid);
        if (!$file) {
            return $this->json(['status' => 'error', 'message' => 'File not found'], 404);
        }
        $filerequest = new \App\Entity\Filerequest();
        $filerequest->setFile($file);
        $filerequest->setTostore(false);
        $filerequest->setUser($this->getUser());
        $filerequest->setDcreated(new DateTime());
        $filerequest->setFinished(false);
        $em->persist($filerequest);
        $em->flush();

        return $this->json([
            'status' => 'ok',
            'filerequestid'   => $filerequest->getId()
        ]);
    }

    #[Route('/getfilerequeststatus/{id}', name: 'app_getfilerequeststatus', methods: ['GET'])]
    public function getfilerequeststatus(int $id, EntityManagerInterface $em): Response
    {
        $filerequest = $em->getRepository(\App\Entity\Filerequest::class)->find($id);
        if (!$filerequest) {
            return $this->json(['status' => 'error', 'message' => 'Filerequest not found'], 404);
        }

        return $this->json([
            'status'   => 'ok',
            'filerequest' => [
                'id'        => $filerequest->getId(),
                'fileid'    => $filerequest->getFile()->getId(),
                'tostore'   => $filerequest->isTostore(),
                'started'   => $filerequest->isStarted(),
                'finished'  => $filerequest->isFinished(),
                'dcreated'  => $filerequest->getDcreated() ? $filerequest->getDcreated()->format('Y-m-d H:i:s') : null,
                'dfinished' => $filerequest->getDfinished() ? $filerequest->getDfinished()->format('Y-m-d H:i:s') : null,
            ],
        ]);
    }   
}
