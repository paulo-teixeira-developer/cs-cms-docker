<?php

namespace App\Repositories;

use App\Helpers\Api as API;
use App\Interfaces\AuthRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{
    private $apiHelper;
    private $userModel;

    public function __construct(API $apiHelper, User $userModel)
    {
        $this->apiHelper = $apiHelper;
        $this->userModel = $userModel;
    }

    public function login($user)
    {
        try {
            /** autenticação **/
            $userAuth = $this->userModel->with(['Person' => ['File' => ['FileFormat', 'FilePath']]])->where('email', $user['email'])->first();

            if (!$userAuth || !Hash::check($user['password'], $userAuth->password)) {
                return $this->apiHelper->response(null, 'er', ["Email ou Senha inválido."]);
            } else {
                $token = $userAuth->createToken($userAuth->email)->plainTextToken;
                return $this->apiHelper->response([
                    'token' => $token,
                    'user' => [
                        'id' => $userAuth->id,
                        'name' => $userAuth->Person->name,
                        'last_name' => $userAuth->Person->last_name,
                        'email' => $userAuth->email,
                        'img_account' => ($userAuth->Person->file_id) ? $userAuth->Person->File->hash . '.' . $userAuth->Person->File->FileFormat->name : null,
                    ],
                ]);
            }
        } catch (\Exception $e) {
            return $this->apiHelper->response($e->getMessage(), 'er', null, 500);
        }
    }

    public function logout()
    {
        try {
            auth()->user()->tokens()->delete();
            return $this->apiHelper->responseBasic();
        } catch (\Exception $e) {
            return $this->apiHelper->response(null, 'er', null, 500);
        }
    }
}