<?php

namespace App\Controller;

use App\Entity\Images;
use App\Form\ImageType;
use App\Repository\ImagesRepository;
use App\Service\FileUploaderService;
use App\Service\QrCodeGeneratorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImagesController extends AbstractController
{
    #[Route('/', name: 'app_images')]
    public function index(ImagesRepository $imagesRepository): Response
    {
        return $this->render('images/index.html.twig', [
            'title' => 'Votre image de rêve',
            'images' => $imagesRepository->findAll()
        ]);
    }

    #[Route('/create_new_image', name: 'app_newimage')]
    public function newImage(
        Request $request,
        QrCodeGeneratorService $qrCodeBuilder,
        SluggerInterface $slugger,
        EntityManagerInterface $em,
        FileUploaderService $fileUploader
    ): Response
    {
        $image = new Images();
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $slugName = $form->get('slug')->getData();
            $slug = $slugger->slug($slugName,'-', 'fr');
            $image->setSlug($slug);

            /** @var UploadedFile $imagefilename */
            $imagefilename = $form->get('imagefile')->getData();

            if($imagefilename) {
                $newFilename = $fileUploader->upload($imagefilename);
                $image->setImagefilename($newFilename);

                $em->persist($image);
                $em->flush();

                $this->addFlash('success', 'Félicitation '. $image->getSlug().' a été ajouter');
                $route = $this->generateUrl('app_images_validation',['id' => $image->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
                $qrCodeBuilder->qrCodeGeneratorAndSave($route, $image->getSlug());

                return $this->redirectToRoute('app_images_validation', [
                    'id' => $image->getId()
                ]);
            }
        }

        return $this->render('images/new_image.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('image/{id<\d+>}', name: 'app_images_validation')]
    public function validationImage(Images $id): Response
    {
        if(!$this->getUser()) {
            $uniquIndentifier = '';
        }
        return $this->redirectToRoute('app_view_detail', [
            'slug' => $id->getSlug(),
        ]);
    }

    #[Route('image/{slug}', name: 'app_view_detail')]
    public function viewDetail(
        #[MapEntity(mapping: ['slug' => 'slug'])]
        Images $slug): Response
    {

        return $this->render('images/view.html.twig', [
            'slug' => $slug
        ]);
    }
}
