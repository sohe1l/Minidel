<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PagesController extends Controller
{


    public function addStore()
    {
        return view("pages.addstore");

    }

    public function privacy()
    {
        return view("pages.privacy");

    }

    public function terms()
    {
        return view("pages.terms");

    }


    public function contact()
    {
        return view("pages.contact");

    }


    public function about()
    {
        return view("pages.about");

    }

    public function faq()
    {
        return view("pages.faq");

    }

    public function test()
    {
        return view("test");

    }

}
