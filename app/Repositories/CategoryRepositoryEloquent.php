<?php
namespace App\Repositories;

use App\Contracts\Repositories\CategoryRepository;
use App\Models\Category;
use App\Repositories\BaseRepository\BaseRepository;

class CategoryRepositoryEloquent extends BaseRepository implements CategoryRepository
{
    public function model(): Category
    {
        return new Category();
    }

    public function getAllCategories($userId)
    {
        return $this->model->where("user_id", $userId)->get();
    }

    public function createCategory($category)
    {
        return $this->model->create($category);
    }

    public function updateCategory($id, $category)
    {
        return $this->model->find($id)->update($category);
    }

    public function deleteCategory($id)
    {
        return $this->model->find($id)->delete();
    }

    public function getCategory($id)
    {
        return $this->model->find($id);
    }
}
