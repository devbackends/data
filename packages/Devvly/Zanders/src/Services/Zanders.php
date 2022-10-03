<?php

namespace Devvly\Zanders\Services;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;

class Zanders
{

    protected $disk;

    /**
     * Contributor constructor.
     * @param string $diskName
     */
    public function __construct(string $diskName)
    {
        $this->disk = Storage::disk($diskName);
    }

    /**
     *
     * Get content from remote server by provided path
     *
     * @param string $path
     * @return false|string
     */
    public function get(string $path)
    {
        // Check the existence of the specified path
        if (!$this->disk->exists($path)) {
            return false;
        }

        // Try to get content
        $content = null;
        try {
            $content = $this->disk->get($path);
        } catch (FileNotFoundException $e) {
            echo 'Error while fetching content: '.$e->getMessage();
            return false;
        }

        return $content;
    }

}