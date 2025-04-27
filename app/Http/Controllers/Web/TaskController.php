<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Inertia\Response;

class TaskController extends Controller
{
    public function index(): Response
    {
        $categories = Category::query()->get();

        return inertia('Tasks/List', [
            'categories' => $categories,
        ]);
    }
}
