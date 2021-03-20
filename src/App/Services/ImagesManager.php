<?php

namespace App\Services;

class ImagesManager
{
    public function deleteImage($filePath)
    {
        unlink($filePath);
    }
}
