<?php

namespace App\Interfaces;

interface CategoryRepositoryInterface
{
    public function listing();

    public function extListing();

    public function store($category);

    public function show($id);

    public function update($category, $id);

    public function delete($id);

}
