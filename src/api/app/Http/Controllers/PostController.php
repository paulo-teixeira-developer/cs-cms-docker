<?php

namespace App\Http\Controllers;

use App\Helpers\Api as API;
use App\Interfaces\PostRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PostController extends Controller
{
    private $apiHelper;
    private $postRepository;

    public function __construct(API $apiHelper, PostRepositoryInterface $postRepository)
    {
        $this->apiHelper = $apiHelper;
        $this->postRepository = $postRepository;
    }

    public function extListing()
    {
        return $this->postRepository->extListing();
    }

    public function extListTheLatest()
    {
        return $this->postRepository->extListing(6);
    }

    public function listing(Request $request)
    {
        $order = ($request->has('order')) ? $request->query('order') : "asc";
        return $this->postRepository->listing($order);
    }

    public function store(Request $request)
    {
        /** validação **/
        $request->merge(['slug' => Str::of($request->input('title'))->slug('-')]);
        $request->merge(['user_id' => auth()->user()->id]);

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:200',
            'slug' => 'unique:App\Models\post,slug',
            'summary' => 'required|max:200',
            'content' => 'required',
            'published' => 'required|boolean',
            'category_id' => 'required|integer|exists:App\Models\Category,id',
            'file_id' => 'required|integer|exists:App\Models\File,id',
        ],
            ['slug.unique' => 'O slug para este post já está sendo utilizado. Altere o título para outro diferente.']);

        if ($validator->fails()) {
            return $this->apiHelper->response(null, 'er', $validator->messages()->all());
        }

        return $this->postRepository->store($request->all());
    }

    public function show($id)
    {
        return $this->postRepository->show($id);
    }

    public function extShowBySlug($slug)
    {
        return $this->postRepository->showBySlug($slug);
    }

    public function update(Request $request, $id)
    {
        /** validação **/
        $request->merge(['slug' => Str::of($request->input('title'))->slug('-')]);
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:200',
            'slug' => 'unique:App\Models\Post,slug,' . $id,
            'summary' => 'required|max:200',
            'content' => 'required',
            'published' => 'required|boolean',
            'category_id' => 'required|integer|exists:App\Models\Category,id',
            'file_id' => 'required|integer|exists:App\Models\File,id',
        ],
            ['slug.unique' => 'O slug para este post já está sendo utilizado. Alterar o título para outro diferente.']);

        if ($validator->fails()) {
            return $this->apiHelper->response(null, 'er', $validator->messages()->all());
        }

        return $this->postRepository->update($request->all(), $id);
    }

    public function delete($id)
    {
        return $this->postRepository->delete($id);
    }
}