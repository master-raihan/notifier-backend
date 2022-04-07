<?php
namespace App\Contracts\Repositories;

interface CategoryRepository
{
    public function getAllCategories($userId);
    public function createCategory($category);
    public function updateCategory($id, $category);
    public function deleteCategory($id);
    public function getCategory($id);
}
