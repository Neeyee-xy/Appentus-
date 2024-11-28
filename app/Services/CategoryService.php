<?php

namespace App\Services;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;

class CategoryService
{   
    public function getAll()
    {
        $user = Auth::user();
        return $user->categories;
    }
    public function getOne($id)
    {
        $category = Category::find($id);
        return $category;
    }

    public function createCategory(array $data)
    {
        return Category::create($data);
    }

    public function updateCategory($id, array $data)
    {
        $category = Category::find($id);
        $category?->update($data);
        return $category;
    }

    public function deleteCategory($id)
    {
        return Category::destroy($id);
    }
    //... add other necessary methods
}