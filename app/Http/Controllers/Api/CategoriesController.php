<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function index()
    {
        CategoryResource::wrap('data'); #在外部创建一个data
        return CategoryResource::collection(Category::all());
    }
}
