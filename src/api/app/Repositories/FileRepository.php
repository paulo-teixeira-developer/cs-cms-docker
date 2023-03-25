<?php

namespace App\Repositories;

use App\Helpers\Api as API;
use App\Interfaces\FileRepositoryInterface;
use App\Models\File;
use App\Models\FileFormat;
use App\Models\FilePath;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileRepository implements FileRepositoryInterface
{
    const DIR_IMG = "img" . DIRECTORY_SEPARATOR;
    const DIR_AUDIO = "audio" . DIRECTORY_SEPARATOR;

    private $apiHelper;
    private $fileModel;
    private $fileFormatModel;
    private $filePathModel;

    public function __construct(API $apiHelper, File $fileModel, FileFormat $fileFormatModel, FilePath $filePathModel)
    {
        $this->apiHelper = $apiHelper;
        $this->fileModel = $fileModel;
        $this->fileFormatModel = $fileFormatModel;
        $this->filePathModel = $filePathModel;
    }

    public function listing($order = 'asc')
    {
        try {
            $files = $this->fileModel->with(['FileFormat', 'FilePath'])
                ->whereNotIn('file_path_id', [1,2])
                ->withCasts(['created_at' => 'datetime:d-m-Y', 'updated_at' => 'datetime:d-m-Y'])
                ->orderBy('id', $order)
                ->paginate(10);

            return $this->apiHelper->responsePaginate($files, 'sc');
        } catch (\Exception $e) {
            return $this->apiHelper->response(null, 'er', null, 500);
        }
    }

    public function listingByPath(array $filePathId, $order)
    {
        try {
            $files = $this->fileModel->with(['FileFormat', 'FilePath'])
                ->whereIn('file_path_id', $filePathId)
                ->withCasts(['created_at' => 'datetime:d-m-Y', 'updated_at' => 'datetime:d-m-Y'])
                ->orderBy('id', $order)
                ->paginate(10);

            return $this->apiHelper->responsePaginate($files, 'sc');
        } catch (\Exception $e) {
            return $this->apiHelper->response(null, 'er', null, 500);
        }
    }

    public function store($file, $path)
    {
        DB::beginTransaction();
        try {
            /** pegando informações do arquivo **/
            $fileInfo = $this->getFileInfo($file);
            $fileInfoData = $fileInfo['data'];
            if ($fileInfo['status'] != "success")
                return $this->apiHelper->responseArray(null, $this->apiHelper->getCodeStatus($fileInfo['status']), $fileInfo['message'], $fileInfo['statusResponse']);

            /** obtendo o caminho do arquivo **/
            $filePath = $this->getDirByType($fileInfoData['extension'], $path);

            /** verificando se path existe **/
            $filePathItem = $this->filePathModel->where('name', $filePath)->first();
            if (!$filePathItem)
                return $this->apiHelper->responseArray(null, 'er', ['Ocorreu um erro ao consultar o caminho do arquivo']);

            /** salvando na base **/
            $fileCreated = $this->fileModel->create(array_merge($fileInfoData, ["file_path_id" => $filePathItem->id]));

            /** upload de arquivo **/
            $fileUpload = $this->uploadFile($file, "{$fileInfoData['hash']}.{$fileInfoData['extension']}", $filePath);

            if ($fileUpload['status'] != "success")
                return $this->apiHelper->responseArray(null, $this->apiHelper->getCodeStatus($fileUpload['status']), $fileUpload['message']);

            DB::commit();
            return $this->apiHelper->responseArray($fileCreated, 'sc');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiHelper->responseArray(null, 'er', null, 500);
        }
    }

    public function storeStrict($file, $path)
    {
        DB::beginTransaction();
        try {
            /** pegando informações do arquivo **/
            $fileInfo = $this->getFileInfo($file);
            $fileInfoData = $fileInfo['data'];
            if ($fileInfo['status'] != "success")
                return $this->apiHelper->response(null, $this->apiHelper->getCodeStatus($fileInfo['status']), $fileInfo['message'], $fileInfo['statusResponse']);

            /** obtendo o caminho do arquivo **/
            $filePath = $this->getDirByType($fileInfoData['extension'], $path);

            /** verificando se path existe **/
            $filePathItem = $this->filePathModel->where('name', $filePath)->first();
            if (!$filePathItem)
                return $this->apiHelper->response(null, 'er', ['Ocorreu um erro ao consultar o caminho do arquivo']);

            /** verificando se nome existe na base **/
            if ($this->fileModel->where(["name" => $fileInfoData['name'], 'file_path_id' => $filePathItem->id, 'file_format_id' => $fileInfoData['file_format_id']])->count())
                return $this->apiHelper->response(null, 'er', ["O arquivo " . $fileInfoData['name'] . " já existe na base."]);

            /** salvando na base **/
            $fileCreated = $this->fileModel->create(array_merge($fileInfoData, ["file_path_id" => $filePathItem->id]));

            /** upload de arquivo **/
            $fileUpload = $this->uploadFile($file, "{$fileInfoData['hash']}.{$fileInfoData['extension']}", $filePath);

            if ($fileUpload['status'] != "success")
                return $this->apiHelper->response(null, $this->apiHelper->getCodeStatus($fileUpload['status']), $fileUpload['message']);

            DB::commit();
            return $this->apiHelper->response();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiHelper->response($e->getMessage(), 'er', null, 500);
        }
    }

    public function getFileInfo($file)
    {
        try {
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $name_hash = hash('sha256', time());
            $extension = $file->extension();
            $fileFormat = $this->fileFormatModel->where('name', $extension)->first();

            if (!$fileFormat)
                return $this->apiHelper->responseArray(null, 'er', ["ocorreu um erro ao consultar o tipo do arquivo."]);

            $data = [
                'name' => $name,
                'hash' => $name_hash,
                'size' => $file->getSize(),
                'extension' => $extension,
                'file_format_id' => $fileFormat->id
            ];

            return $this->apiHelper->responseArray($data, 'sc');
        } catch (\Exception $e) {
            return $this->apiHelper->response(null, 'er', null, 500);
        }
    }

    public function uploadFile($file, $name, $directory)
    {
        try {
            /** armazenando arquivo **/
            if (!Storage::disk('local')->putFileAs($directory, $file, $name)) {
                return $this->apiHelper->responseArray(null, 'er', ['Ocorreu um erro durante o upload do arquivo.']);
            }

            return $this->apiHelper->responseArray(null, 'sc');
        } catch (\Exception $e) {
            return $this->apiHelper->response(null, 'er', null, 500);
        }
    }

    public function getDirByType($extension, $path)
    {
        $listImg = "|jpg|jpeg|png|";
        $listAudio = "|mp3|wav|";
        $findExtension = "|" . $extension . "|";

        if (str_contains($listImg, $findExtension)) {
            return $path . '/' . "img";
        } else if (str_contains($listAudio, $findExtension)) {
            return $path . '/' . "audio";
        }
    }

    public function show($id)
    {
        try {
            $file = $this->fileModel->with(['FileFormat', 'FilePath'])
                ->where('id', $id)
                ->withCasts(['created_at' => 'datetime:d-m-Y', 'updated_at' => 'datetime:d-m-Y'])
                ->first();
            if ($file)
                return $this->apiHelper->response($file);
            else
                return $this->apiHelper->response(null, 'nf');
        } catch (\Exception $e) {
            return $this->apiHelper->response(null, 'er', null, 500);
        }
    }

    public function replace($id, $newFile)
    {
        DB::beginTransaction();
        try {
            /** pegando informações do arquivo **/
            $fileInfo = $this->getFileInfo($newFile);
            $fileInfoData = $fileInfo['data'];
            if ($fileInfo['status'] != "success")
                return $this->apiHelper->response(null, $this->apiHelper->getCodeStatus($fileInfo['status']), $fileInfo['message'], $fileInfo['statusResponse']);

            /** buscando informações do atual arquivo e validando **/
            $file = $this->fileModel->with(['FilePath'])->where('id', $id)->first();
            if (!$file) {
                return $this->apiHelper->response(null, 'nf');
            }

            /** obtendo path atual file para delete **/
            $oldFilePath = "{$file->FilePath->name}/{$file->hash}.{$file->FileFormat->name}";

            /** atualizando na base **/
            $file->update($fileInfoData);

            /** upload do novo arquivo **/
            $fileUpload = $this->uploadFile($newFile, "{$fileInfoData['hash']}.{$fileInfoData['extension']}", $file->FilePath->name);
            if ($fileUpload['status'] != "success")
                return $this->apiHelper->response(null, $this->apiHelper->getCodeStatus($fileUpload['status']), $fileUpload['message']);

            DB::commit();
            /** delete do antigo arquivo **/
            $this->deleteFile($oldFilePath);

            return $this->apiHelper->response(null, 'sc');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiHelper->response(null, 'er', null, 500);
        }
    }

    public function streaming($path, $file)
    {
        try {

            $DS = DIRECTORY_SEPARATOR;
            $FP = storage_path();
            $filePath = "{$FP}{$DS}app{$DS}{$path}{$DS}{$file}";

            $response = new BinaryFileResponse($filePath);
            BinaryFileResponse::trustXSendfileTypeHeader();

            return $response;
        } catch (\Exception $e) {
            return $this->apiHelper->response(null, 'er', null, 500);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {

            $file = $this->fileModel->with(['FileFormat', 'FilePath'])->where('id', $id)->first();

            /** deletando na base **/
            if ($file)
                $file->delete();
            else
                return $this->apiHelper->response(null, 'nf');

            /** deletando arquivo **/
            $delete = $this->deleteFile("{$file->FilePath->name}/{$file->hash}.{$file->FileFormat->name}");
            if ($delete['status'] != 'success')
                return $this->apiHelper->response(null, $this->apiHelper->getCodeStatus($delete['status']), $delete['message'], $delete['statusResponse']);

            DB::commit();
            return $this->apiHelper->response();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiHelper->response(null, 'er', null, 500);
        }
    }

    public function deleteFile($filePathFull)
    {
        try {
            if (!Storage::disk('local')->exists($filePathFull))
                return $this->apiHelper->responseArray(null, 'er', ['Arquivo não existe no diretório.']);

            if (!Storage::delete($filePathFull))
                return $this->apiHelper->responseArray(null, 'er', ['Ocorreu um erro.']);

            return $this->apiHelper->responseArray();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiHelper->response(null, 'er', null, 500);
        }
    }
}