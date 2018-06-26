<?php

namespace net\http;

use net\collection\Collection;

class UploadedFiles extends Collection
{

    /**
     * @param string $src
     * @param bool $randomName
     * @return void
     */
    public function move(string $src, bool $randomName = false): void
    {
        foreach ($this->all() as $files) {
            foreach ($files as $file) {
                if ($file instanceof UploadedFile) {
                    if ($randomName) {
                        $file->move($src, md5(uniqid()));
                    } else {
                        $file->move($src, "");
                    }
                }
            }
        }
    }
}