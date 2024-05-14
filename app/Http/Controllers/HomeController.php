<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testimonials;
use App\Models\Blog;

class HomeController extends Controller
{

    public function index(Request $request)
    {
        $testimonials = Testimonials::all();
        $blogs = Blog::all();
        return view('layouts', compact('testimonials', 'blogs'));
    }
}
