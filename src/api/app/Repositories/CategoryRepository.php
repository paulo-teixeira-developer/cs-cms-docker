<?php

namespace App\Repositories;

use App\Helpers\Api as API;
use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryRepository implements CategoryRepositoryInterface
{
    private $apiHelper;
    private $categoryModel;

    public function __construct(API $apiHelper, Category $categoryModel)
    {
        $this->apiHelper = $apiHelper;
        $this->categoryModel = $categoryModel;
    }

    public function listing()
    {
        try {
            $category = $this->categoryModel->paginate(50);
            return $this->apiHelper->responsePaginate($category);
        } catch (\Exception $e) {
            return $this->apiHelper->response(null, 'er', null, 500);
        }
    }

    public function extListing()
    {
        try {
            $category = $this->categoryModel->get(['id', 'name']);
            return $this->apiHelper->response($category);
        } catch (\Exception $e) {
            return $this->apiHelper->response(null, 'er', null, 500);
        }
    }

    public function store($category)
    {
        DB::beginTransaction();
        try {
            $this->categoryModel->create($category);
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
            $category = $this->categoryModel->find($id);
            if ($category)
                return $this->apiHelper->response($category);
            else
                return $this->apiHelper->response(null, 'nf');
        } catch (\Exception $e) {
            return $this->apiHelper->response(null, 'er', null, 500);
        }
    }

    public function update($category, $id)
    {
        DB::beginTransaction();
        try {

            /** atualização **/
            $cat = $this->categoryModel->find($id);

            if (!$cat)
                return $this->apiHelper->response(null, 'nf');

            $cat->update($category);
            DB::commit();
            return $this->apiHelper->response();
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
            $cat = $this->categoryModel->find($id);

            if (!$cat)
                return $this->apiHelper->response(null, 'nf');

            $cat->delete();
            DB::commit();
            return $this->apiHelper->response();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiHelper->response(null, 'er', null, 500);
        }
    }
}