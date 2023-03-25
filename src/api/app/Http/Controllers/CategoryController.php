<?php

namespace App\Http\Controllers;

use App\Interfaces\CategoryRepositoryInterface;
use Illuminate\Http\Request;
use App\Helpers\Api as API;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    private $apiHelper;
    private $categoryRepository;

    public function __construct(API $apiHelper, CategoryRepositoryInterface $categoryRepository)
    {
        $this->apiHelper = $apiHelper;
        $this->categoryRepository = $categoryRepository;
    }

    public function listing()
    {
        return $this->categoryRepository->listing();
    }

    public function extListing()
    {
        return $this->categoryRepository->extListing();
    }

    public function store(Request $request)
    {
        /** validação **/
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50|unique:App\Models\Category,name',
        ]);

        if ($validator->fails()) {
            return $this->apiHelper->response(null, 'er', $validator->messages()->all());
        }

        return $this->categoryRepository->store($request->all());
    }

    public function show($id)
    {
        return $this->categoryRepository->show($id);
    }

    public function update(Request $request, $id)
    {
        /** validação **/
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50|unique:App\Models\Category,name,' . $id,
        ]);

        if ($validator->fails()) {
            return $this->apiHelper->response(null, 'er', $validator->messages()->all());
        }

        return $this->categoryRepository->update($request->all(), $id);
    }

    public function delete($id)
    {
        return $this->categoryRepository->delete($id);
    }
}
