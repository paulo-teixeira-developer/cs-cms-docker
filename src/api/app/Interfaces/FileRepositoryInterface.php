<?php

namespace App\Interfaces;

interface FileRepositoryInterface
{
    public function listing($order);

    public function listingByPath(array $filePathId, $order);

    public function storeStrict($file, $path);

    public function store($file, $path);

    public function getFileInfo($file);

    public function uploadFile($file, $name, $directory);

    public function getDirByType($extension, $path);

    public function show($id);

    public function replace($id, $newFile);

    public function streaming($path, $file);

    public function delete($id);

    public function deleteFile($filePathFull);
}
