<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileHelper {
    public static function uploadPrivate($file, $folder) {
        try {
            $extension = $file->getClientOriginalExtension();
            $filename = uniqid() . ".$extension";
            Storage::disk('local')->put("/private/$folder/$filename", File::get($file));

            return $filename;
        } catch(\Exception $exception) {
            Log::error($exception);

            return null;
        }
    }

    public static function uploadPublic($file, $folder) {
        try {
            $extension = $file->getClientOriginalExtension();
            $filename = uniqid() . time() . ".$extension";
            Storage::disk('local')->put("/public/$folder/$filename", File::get($file));

            return $filename;
        } catch(\Exception $exception) {
            Log::error($exception);

            return null;
        }
    }

    public static function removePrivate($folder, $filename) {
        Storage::disk('local')->delete("/private/$folder/$filename");
    }
    
    public static function removePublic($folder, $filename) {
        Storage::disk('local')->delete("/public/$folder/$filename");
    }
}