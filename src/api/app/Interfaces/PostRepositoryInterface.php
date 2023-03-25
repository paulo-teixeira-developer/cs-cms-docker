<?php

namespace App\Interfaces;

interface PostRepositoryInterface
{
    public function extListing();

    public function store($post);

    public function show($id);

    public function showBySlug($slug);

    public function update($post, $id);

    public function delete($id);
}
