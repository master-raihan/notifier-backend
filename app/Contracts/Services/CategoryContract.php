<?php
namespace App\Contracts\Services;

interface CategoryContract
{
    public function getAllCategories();
    public function createCategory($request);
    public function updateCategory($id, $request);
    public function deleteCategory($id);
    public function getCategory($id);
}
