<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function listing();

    public function store($person, $user);

    public function show($id);

    public function update($person, $id);

    public function updateCredential($user, $id);

    public function updateImg($file, $id);

    public function delete($id);

}
