<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Api as API;
use App\Interfaces\FileRepositoryInterface;

class FileController extends Controller
{

    private $apiHelper;
    private $fileRepository;

    public function __construct(API $apiHelper, FileRepositoryInterface $fileRepository)
    {
        $this->apiHelper = $apiHelper;
        $this->fileRepository = $fileRepository;
    }

    public function listing(Request $request)
    {
        $order = ($request->has('order')) ? $request->query('order') : "asc";
        return $this->fileRepository->listing($order);
    }

    public function listingByPath(Request $request)
    {
        /** validação **/
        $validator = Validator::make($request->all(), [
            'file_path_id' => 'required|array',
        ]);

        if ($validator->fails()) {
            return $this->apiHelper->response(null, 'er', $validator->messages()->all());
        }

        $filePathId = $request->query('file_path_id');
        $order = ($request->has('order')) ? $request->query('order') : "asc";

        return $this->fileRepository->listingByPath($filePathId, $order);
    }

    public function store(Request $request)
    {
        /** validação **/
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:jpg,jpeg,png,mp3,wav',
        ]);

        if ($validator->fails()) {
            return $this->apiHelper->response(null, 'er', $validator->messages()->all());
        }

        return $this->fileRepository->storeStrict($request->file('file'), 'public');
    }

    public function show($id)
    {
        return $this->fileRepository->show($id);
    }

    public function streaming($path_primary, $path_secondary, $file)
    {
        return $this->fileRepository->streaming($path_primary . "/" . $path_secondary, $file);
    }

    public function streamingPrivate($file)
    {
        return $this->fileRepository->streaming('private/user/img', $file);
    }

    public function delete($id)
    {
        return $this->fileRepository->delete($id);
    }

    public function replaceName()
    {

        $filesInFolder = \File::files('public/icon');
        $file = [];
        foreach ($filesInFolder as $path) {
            $fileOld = pathinfo($path)['basename'];
            $fileNew = str_replace('-svgrepo-com', '', $fileOld);
            Storage::copy('public/icon/' . $fileOld, 'public/icon/new-icon/' . $fileNew);
        }

        return ['message' => 'successo'];

    }
}
