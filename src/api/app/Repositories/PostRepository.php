<?php

namespace App\Repositories;

use App\Helpers\Api as API;
use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class PostRepository implements PostRepositoryInterface
{
    private $apiHelper;
    private $postModel;

    public function __construct(API $apiHelper, Post $postModel)
    {
        $this->apiHelper = $apiHelper;
        $this->postModel = $postModel;
    }

    public function extListing($perPage = 20)
    {
        try {
            $posts = $this->postModel->with(['Category:id,name', 'file:id,hash,file_format_id,file_path_id' => ['FileFormat:id,name', 'FilePath:id,name']])
                ->where('published', true)
                ->orderBy('id', 'desc')
                ->paginate($perPage, ['id', 'title', 'slug', 'summary', 'user_id','category_id', 'file_id', 'created_at']);

            return $this->apiHelper->responsePaginate($posts);
        } catch (\Exception $e) {
            return $this->apiHelper->response(null, 'er', null, 500);;
        }
    }

    public function listing($order = 'asc')
    {
        try {
            $posts = Post::with(['user:id,person_id' => ['person:id,name,last_name'], 'category:id,name', 'File' => ['FileFormat', 'FilePath']])
                ->withCasts(['created_at' => 'datetime:d-m-Y', 'updated_at' => 'datetime:d-m-Y'])
                ->orderBy('id', $order)
                ->paginate(10, ['id', 'title', 'published', 'user_id', 'category_id', 'file_id', 'created_at', 'updated_at']);

            return $this->apiHelper->responsePaginate($posts);
        } catch (\Exception $e) {
            return $this->apiHelper->response(null, 'er', null, 500);
        }
    }

    public function store($post)
    {
        DB::beginTransaction();
        try {
            /** criação **/
            $this->postModel->create($post);
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
            $post = $this->postModel->where('id', $id)
                ->with(['user:id,person_id' => ['Person:id,name,last_name'], 'file' => ['FileFormat', 'FilePath'], 'category:id,name'])
                ->get(['id', 'title', 'slug', 'summary', 'content', 'published', 'user_id', 'category_id', 'file_id', 'created_at', 'updated_at'])
                ->first();

            if ($post)
                return $this->apiHelper->response($post);
            else
                return $this->apiHelper->response(null, 'nf');
        } catch (\Exception $e) {
            return $this->apiHelper->response(null, 'er', null, 500);
        }
    }

    public function showBySlug($slug)
    {
        try {
            $post = $this->postModel->where('slug', $slug)
                ->with(['File:id,hash,file_format_id,file_path_id' => ['FileFormat', 'FilePath'], 'category:id,name'])
                ->withCasts(['updated_at' => 'datetime:d/m/Y'])
                ->get(['id', 'title', 'slug', 'summary', 'content', 'user_id', 'category_id', 'file_id', 'updated_at'])
                ->first();

            if ($post)
                return $this->apiHelper->response($post);
            else
                return $this->apiHelper->response(null, 'nf');
        } catch (\Exception $e) {
            return $this->apiHelper->response(null, 'er', null, 500);
        }
    }

    public function update($post, $id)
    {
        DB::beginTransaction();
        try {
            /** atualização **/
            $postUpdate = $this->postModel->find($id);

            if (!$postUpdate) {
                return $this->apiHelper->response(null, 'nf');
            }
            $postUpdate->update($post);

            DB::commit();
            return $this->apiHelper->response($postUpdate, 'sc', ['operação realizada com sucesso.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiHelper->response(null, 'er', null, 500);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            /** delete **/
            $post = $this->postModel->find($id);

            if (!$post)
                return $this->apiHelper->response(null, 'nf');

            $post->delete();

            DB::commit();
            return $this->apiHelper->response();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiHelper->response(null, 'er', null, 500);
        }
    }


}