<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Files\File;

class FilesController extends Controller
{
    public function download($source, $file)
    {
        try {
            return (new File)->download($source . '/' . $file);
        } catch (\League\Flysystem\FileNotFoundException $ex) {
            return response()->json(['message' => 'Not found.'], 404);
        }
    }
}
