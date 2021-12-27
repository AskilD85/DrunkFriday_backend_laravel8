<?php

namespace App\Modules\File;

use App\File;
use App\User;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final class FileService
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct()
    {
        $this->filesystem = Storage::disk('local');
    }

    public function upload(UploadedFile $uploadedFile, User $user): File
    {
        $filename = uniqid() . '.' . $uploadedFile->extension();

        $this->filesystem->put($filename, $uploadedFile->getContent());

        $file = new File();
        $file->name = $filename;
        $file->user_id = $user->id;
        $file->save();

        return $file;
    }

    /**
     * Путь к файлу
     */
    public function download(File $file): string
    {
        $path = $this->filesystem->path($file->name);
        return $path;
    }
}
