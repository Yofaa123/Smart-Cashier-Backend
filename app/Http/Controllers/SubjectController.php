<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::select('id', 'name', 'description')->get();

        return response()->json([
            'status' => true,
            'subjects' => $subjects
        ]);
    }
}
