<?php

namespace App\Http\Controllers;

use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Api as API;

class UserController extends Controller
{

    private $apiHelper;
    private $userRepository;

    public function __construct(API $apiHelper, UserRepositoryInterface $userRepository)
    {
        $this->apiHelper = $apiHelper;
        $this->userRepository = $userRepository;
    }

    public function listing()
    {
        return $this->userRepository->listing();
    }

    public function store(Request $request)
    {
        /** validação **/
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'birth' => 'required|max:100|date',
            'profession' => 'required|max:100',
            'biography' => 'required|max:2000',
            'user.email' => 'required|max:255|email|unique:App\Models\User,email',
            'user.email_confirmation' => 'required|max:255|email|same:user.email',
            'user.password' => 'required|min:6|max:15',
            'user.password_confirmation' => 'required|min:6|max:15|same:user.password',
        ]);

        if ($validator->fails()) {
            return $this->apiHelper->response(null, 'er', $validator->messages()->all());
        }

        return $this->userRepository->store($request->except('user'), $request->input('user'));
    }

    public function show($id)
    {
        return $this->userRepository->show($id);
    }

    public function update(Request $request, $id)
    {
        /** validação **/
        $validator = Validator::make($request->all(), [
            'person.name' => 'required|max:100',
            'person.last_name' => 'required|max:100',
            'person.birth' => 'required|max:100|date',
            'person.profession' => 'required|max:100',
            'person.biography' => 'required|max:2000',
        ]);

        if ($validator->fails()) {
            return $this->apiHelper->response(null, 'er', $validator->messages()->all());
        }

        return $this->userRepository->update($request->input('person'), $id);
    }

    public function updateCredential(Request $request, $id)
    {
        /** validação **/
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:255|email',
            'password' => 'nullable|min:6|max:15',
        ]);

        if ($validator->fails()) {
            return $this->apiHelper->response(null, 'er', $validator->messages()->all());
        }

        return $this->userRepository->updateCredential($request->all(), $id);
    }

    public function updateImg(Request $request, $id)
    {
        /** validação **/
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:jpg,jpeg,png',
        ]);

        if ($validator->fails()) {
            return $this->apiHelper->response(null, 'er', $validator->messages()->all());
        }

        return $this->userRepository->updateImg($request->file('file'), $id);
    }

    public function delete($id)
    {
        return $this->userRepository->delete($id);
    }
}
