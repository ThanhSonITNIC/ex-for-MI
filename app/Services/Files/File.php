<?php

namespace App\Services\Files;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class File
{
    protected $folder = null;

    protected function root()
    {
        return Str::lower(env('APP_NAME'));
    }

    public function download($pathFile)
    {
        return Storage::download($this->root() . '/' . $pathFile);
    }

    public function store(UploadedFile $file)
    {
        $name = $this->hashName($file);
        Storage::putFileAs($this->path(), $file, $name);
        return $name;
    }

    protected function hashName(UploadedFile $file): string
    {
        return round(microtime(true) * 1000) . explode('.', $file->hashName())[0] . '.' . $file->getClientOriginalExtension();
    }

    public function path(): string
    {
        return $this->root() . '/' . $this->folder();
    }

    public function folder(): string
    {
        return $this->folder ?: 'files';
    }

    public function url($fileName)
    {
        return asset('files') . '/' . $this->folder() . '/' . $fileName;
    }

    public function remove(string $path)
    {
        return Storage::delete($this->root() . '/' . $path);
    }

    /**
     * Get info file from Uploaded File.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return array
     */
    public static function info(UploadedFile $file): array
    {
        return [
            'mime_type' => $file->getClientMimeType(),
            'extension' => $file->getClientOriginalExtension(),
            'size' => filesize($file->path()),
        ];
    }

    /**
     * Get info file from path file.
     *
     * @param  string  $pathFile
     * @return array
     */
    public static function infoFromPathFile(string $pathFile): array
    {
        $path_file = (new static)->root() . '/' . $pathFile;
        $mime_type = null;
        $extension = null;
        $size = null;
        if (Storage::exists($path_file)) {
            $mime_type = Storage::mimeType($path_file);
            $extension = pathinfo(Storage::path($path_file), PATHINFO_EXTENSION);
            $size = Storage::size($path_file);
        }
        return [
            'mime_type' => $mime_type,
            'extension' => $extension,
            'size' => $size,
        ];
    }

    public static function infoFromUrl(string $url): array
    {
        $headers = array_change_key_case(get_headers($url, true), CASE_UPPER);
        $mime_type = strtok($headers["CONTENT-TYPE"] ?? "", ';');
        return [
            'mime_type' => $mime_type,
            'extension' => \Illuminate\Http\Testing\MimeType::search($mime_type),
            'size' => $headers["CONTENT-LENGTH"] ?? null,
        ];
    }
}
