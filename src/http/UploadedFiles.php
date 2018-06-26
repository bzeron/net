<?php

namespace net\http;

use net\collection\Collection;

/**
 * Class UploadedFiles
 * @package net\http
 */
class UploadedFiles extends Collection
{
    /**
     * @param string $targetDir
     * @param bool $randomName
     */
    public function MoveToDir($targetDir, $randomName = false)
    {
        foreach ($this->all() as $file) {
            foreach ($file as $item) {
                if ($item instanceof UploadedFile) {
                    if ($randomName) {
                        $item->Move($targetDir, md5(uniqid()));
                    } else {
                        $item->Move($targetDir);
                    }
                }
            }
        }
    }
}