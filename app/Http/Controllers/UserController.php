<?php

namespace App\Http\Controllers;

use App\Models\User;
use \Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Request;
use League\CommonMark\Extension\CommonMark\Node\Block\ListItem;
use Nette\Utils\ArrayList;
// use PhpParser\Node\Expr\List_;
use SebastianBergmann\CodeCoverage\Node\Iterator;
use Traversable;

class UserController extends Controller
{
    public function editPassword(Request $req, $id)
    {
        $user= User::find($id);
        $user->password =Hash::make($req->input('password'));
        $user->update();
        return $user;
    }

    public function addUser(Request $req)
    {


        $user= User::create([
        //$user->id =$req->input('id');
            'name' => $req->name,
            'email' =>$req->email,
            'password' =>Hash::make($req->password),
            'role_id' =>$req->role_id,
            'company_id' => $req->company_id,
            'phone_number' =>$req->phone_number,
        ]);


        return response()->json([
            'user' => $user,
            'meassage' => 'user created successfully'
        ]);
    }



public function delete($id)
{
    $result= user::where('id', $id)->delete();
    if ($result) {
        return ["result"=>"user has been delete"];
    } else {
        return ["result"=>"Operation faild"];
    }
}



public function deleteSelected(ArrayList $id)
{
    for($i=0; $i<=sizeof($id); $i++)
        $result= User::where('id', $id[$i])->delete();
        if ($result) {
            return ["result"=>" selected users have been delete"];
        } else {
            return ["result"=>"Operation faild"];
        }
    }



}