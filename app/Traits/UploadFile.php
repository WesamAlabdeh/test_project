<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;

trait UploadFile
{

    protected function storeFiles(array $data, string $key, string $directory , ?string $disk = 'public'): array
    {
        if (array_key_exists($key, $data) && is_array($data[$key])) {
            $data[$key] = array_map(function ($file) use ($directory, $disk) {
                return $file instanceof UploadedFile ? $file->store($directory, $disk) : $file;
            }, $data[$key]);
        }

        return $data;
    }

}
