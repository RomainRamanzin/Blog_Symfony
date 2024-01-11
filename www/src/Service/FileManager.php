<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManager
{
     private $sharedDirectory;

     public function __construct($sharedDirectory)
     {
          $this->sharedDirectory = $sharedDirectory;
     }

     public function upload(UploadedFile $file, string $name = '', string $subdir = '', bool $public = false): String
     {
          if ($name === '') {
               $name = $file->getBasename();
          }

          $fileName = $name;

          if ($public === false) {
               $dirPath = $this->sharedDirectory . '/private/' . $subdir;
          } else {
               $dirPath = $this->sharedDirectory . '/public/' . $subdir;
          }

          while (file_exists($dirPath . '/' . $fileName)) {
               // création d'un détrompeur unique
               $key = uniqid();
               $fileName = $name . '_' . $key;
          }

          if (file_exists($dirPath) === false) {
               mkdir($dirPath, 777, true); // pour tester, 644 c'est beaucoup mieux
          }

          $fileName = $fileName . '.' . $file->guessExtension();

          $file->move($dirPath, $fileName);
          return $fileName;
     }

     public function download(string $name, string $subdir = ''): BinaryFileResponse
     {
          $dirPath = $this->sharedDirectory . '/private/' . $subdir;

          if (!file_exists($dirPath . '/' . $name)) {
               return new BinaryFileResponse('', 404);
          }

          $response = new BinaryFileResponse($dirPath . '/' . $name);
          return $response;
     }
}
