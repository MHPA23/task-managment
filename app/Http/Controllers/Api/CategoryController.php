<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryController extends Controller
{
    public function index(): ResourceCollection|JsonResponse
    {
        try {
            $categories = Category::query()->get();

            return CategoryResource::collection($categories);
        } catch (\Exception $e) {
            return response()->json(['error' => trans('message.failed_to_fetch_categories')], 500);
        }
    }
}
