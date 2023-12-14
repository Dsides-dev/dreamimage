<?php

namespace App\Service;

use DateTimeImmutable;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\Result\ResultInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class QrCodeGeneratorService extends AbstractController
{
    public function __construct(
        private readonly string $qrCodeDir,
        private readonly string $dirnamImgFile,
        private readonly LoggerInterface $logger
    )
    {}

    public function qrCodeGeneratorAndSave($data, $filenamePng): RedirectResponse|ResultInterface
    {
        try {
            $buildQrCode = Builder::create()
                ->writer(new PngWriter())
                ->writerOptions([])
                ->data($data)
                ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
                ->size(300)
                ->margin(10)
                ->logoPath($this->dirnamImgFile.'favicon_light.jpg')
                ->logoResizeToWidth(50)
                ->logoPunchoutBackground(true)
                ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
                ->validateResult(true)
                ->build();

            $dateTime =new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'));

            $this->logger->info('Qr Code créé', ['date' => $dateTime->format('d-m-Y H:i')]);
            $buildQrCode->saveToFile($this->qrCodeDir.$filenamePng.'.png');
            $this->addFlash('success', 'Le QR code à été générer et sauvegarder');

            return $buildQrCode;

        }catch (\Exception $e) {

            $this->logger->error('Erreur Qr Code', ['message' => $e->getMessage()]);
            $this->addFlash('danger', 'Une erreur ces produit lors de la création du QR code : '.$e->getMessage());

            return $this->redirectToRoute('app_images');
        }

    }
}