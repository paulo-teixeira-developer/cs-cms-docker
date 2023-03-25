<?php

namespace App\Repositories;

use App\Helpers\Api as API;
use App\Interfaces\FileRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\Person;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{
    private $apiHelper;
    private $userModel;
    private $fileRepository;

    public function __construct(API $apiHelper, User $userModel, Person $personModel, FileRepositoryInterface $fileRepository)
    {
        $this->apiHelper = $apiHelper;
        $this->userModel = $userModel;
        $this->personModel = $personModel;
        $this->fileRepository = $fileRepository;
    }

    public function listing()
    {
        try {
            $user = $this->userModel->all();
            return $this->apiHelper->response($user);
        } catch (\Exception $e) {
            return $this->apiHelper->response(null, 'er', null, 500);
        }
    }

    public function store($person, $user)
    {
        DB::beginTransaction();
        try {
            /** criação **/
            $personCreate = $this->personModel->create($person);
            $personCreate->user()->create($user);

            DB::commit();
            return $this->apiHelper->response();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiHelper->response(null, 'er', null, 500);
        }
    }

    public function show($id)
    {
        try {
            $user = $this->userModel->where('id', $id)
                ->with(['Person' => ['File' => ['FileFormat', 'FilePath']]])
                ->first(['id', 'email', 'person_id']);

            if (!$user)
                return $this->apiHelper->response(null, 'nf');

            return $this->apiHelper->response($user);
        } catch (\Exception $e) {
            return $this->apiHelper->response(null, 'er', null, 500);
        }
    }

    public function update($person, $id)
    {
        DB::beginTransaction();
        try {
            /** atualização **/
            $userUpdate = $this->userModel->find($id);

            if (!$userUpdate) {
                return $this->apiHelper->response(null, 'nf', ['Usuário não encontrado.']);
            }

            $userUpdate->update($person);
            $userUpdate->Person->update($person);

            DB::commit();
            return $this->apiHelper->response();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiHelper->response(null, 'er', null, 500);
        }
    }

    public function updateCredential($user, $id)
    {
        try {
            /** autenticação **/
            $userAuth = $this->userModel->find($id);

            if (!$userAuth) {
                return $this->apiHelper->response(null, 'nf');
            }

            if ($user['password']) {
                $userAuth->update([
                    'email' => $user['email'],
                    'password' => $user['password'],
                ]);
            } else {
                $userAuth->update([
                    'email' => $user['email'],
                ]);
            }

            return $this->apiHelper->response(null);
        } catch (\Exception $e) {
            return $this->apiHelper->response($e->getMessage(), 'er', null, 500);
        }
    }

    public function updateImg($file, $id)
    {
        try {

            /** buscando e validando usuario **/
            $userUpdate = $this->userModel->with(['Person:id,file_id' => ['File:id,file_path_id,file_format_id,hash' => ['FilePath', 'FileFormat']]])->where('id', $id)->first(['id', 'person_id']);
            if (!$userUpdate) {
                return $this->apiHelper->response(null, 'nf');
            }

            /** criando nova imagem caso o usuario nao tenha **/
            if (!$userUpdate->Person->File) {
                $fileCreated = $this->fileRepository->store($file, 'private/user');
                if ($fileCreated['status'] == "success") {
                    $userUpdate->Person->update(['file_id' => $fileCreated['data']['id']]);
                    return $this->apiHelper->response();
                } else {
                    return $this->apiHelper->response(null, 'er', $fileCreated['message'], $fileCreated['statusResponse']);
                }
            }

            /** atualizando na base e upload **/
            return $this->fileRepository->replace($userUpdate->Person->File->id, $file);

        } catch (\Exception $e) {
            return $this->apiHelper->response($e->getMessage(), 'er', null, 500);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            /** delete **/
            $userDelete = $this->userModel->find($id);

            if (!$userDelete)
                return $this->apiHelper->response(null, 'nf');

            $userDelete->delete();
            $userDelete->Person->delete();

            DB::commit();
            return $this->apiHelper->response();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiHelper->response(null, 'er', null, 500);
        }
    }
}