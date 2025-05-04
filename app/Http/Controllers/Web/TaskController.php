<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Inertia\Response;

class TaskController extends Controller
{
    public function index(): Response
    {
        return inertia('Tasks/List');
    }
}
