<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Company;

class CategoryController extends Controller
{
    function getAllCategories(){
       return Category::all();
 }

 function searchForCategory(Request $req){
       $name = $req->name;
            $category = Category::where('name', $name)->get();
        return $category;

 }

 function searchForCompany(Request $req){
    $name = $req->name;
         $company = Company::where('name', $name)->get();
     return $company;

}



}
