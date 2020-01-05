<?php
namespace App\Service;

use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

class UploaderHelper
{
    /**
     * @var String
     */
    private $uploadsMediaPath;

    /**
     * @var String
     */
    private $projectDir;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * UploaderHelper constructor.
     *
     * @param String $uploadsMediaPath
     * @param String $projectDir
     * @param Filesystem $filesystem
     */
    public function __construct(
        string $uploadsMediaPath,
        string $projectDir,
        Filesystem $filesystem)
    {
        $this->uploadsMediaPath = $uploadsMediaPath;
        $this->projectDir = $projectDir;
        $this->filesystem = $filesystem;
    }

    /**
     * Make upload any file and set in uploads folder.
     *
     * @param UploadedFile $uploadedFile
     *
     * @return String[]
     */
    public function uploadMedia(UploadedFile $uploadedFile): array
    {
        $mediaPath = $this->uploadsMediaPath . '/'. date('Y') . '/' . date('m');
        $destination = $this->projectDir . '/public/' . $mediaPath;

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);

        $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();

        $uploadedFile->move(
            $destination,
            $newFilename
        );

        return [
            'file' => $mediaPath . '/' . $newFilename,
            'name' => $newFilename
        ];
    }

    /**
     * Delete file.
     *
     * @param String $path
     * @param Boolean $isPublic
     *
     * @throws Exception if dont delete the file.
     */
    public function deleteFile(string $path, bool $isPublic = true)
    {
        if ($isPublic) {
            $path = $this->projectDir . '/public/' .$path;
        }

        try {
            $this->filesystem->remove($path);
        } catch (IOExceptionInterface $exception) {
            echo "An error occurred while creating your directory at ".$exception->getPath();
        }
    }
}