<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait UploadFile
{

    public function upload($file, $folder, $last_file = null)
    {
        if ($last_file && Storage::disk('public')->exists($last_file)) {
            Storage::disk('public')->delete($last_file);
        }
        $fileName = time() . '_' . $file->getClientOriginalName();
        Storage::disk('public')->putFileAs($folder, $file, $fileName);
        return "/storage/{$folder}/{$fileName}";
    }

    public function delete($path)
    {
        if ($path) {
            if (str_contains($path, "/storage")) {
                $path = substr($path, strlen("/storage"));
            }
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }

    public function uploadWithOriginalName($file, $folder, $last_file = null)
    {
        if ($last_file && Storage::disk('public')->exists($last_file)) {
            Storage::disk('public')->delete($last_file);
        }
        $fileName = $file->getClientOriginalName();
        Storage::disk('public')->putFileAs($folder, $file, $fileName);
        return "/storage/{$folder}/{$fileName}";
    }

}
