<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\User;
use Faker\Guesser\Name;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\List_;
use Symfony\Component\Console\Input\Input;
use function PHPUnit\Framework\isNull;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Models\Company;
use App\Models\Category;
use App\Models\Role;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Time;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    public function filterBooking(Request $req)
    {
        $created=$Date=$Service_name=$user_name=$Client_name=$status="";
        $created =$req->created;
        $date = $req->date;
        $Service_name = $req->name;
        $user_name = $req->name; //employee
        $Client_name = $req->name; //customer
        $status = $req->status;
        $name = $req->name;


        if (!empty($created)) {
            $booking= Booking::where('created', $created)
            ->get();
        } elseif (!empty($date)) {
            $time= Time::where('date', $date)
            ->get();
        } elseif (!empty($service)) {
            $booking= Booking::where('service', $service)
            ->get();
        } elseif (!empty($name)) {
            $user= User::where('name', $name)
            ->get($name);
        } elseif (!empty($name)) {
            $customer = Customer::where('first_name', 'LIKE', "%{$name}%")
            ->orwhere('last_name', 'LIKE', "%{$name}%")
            ->orWhereRaw("concat(first_name,' ', last_name) like '%" . $name . "%' ")
            ->get();
            $service = Service::where('name', $name)

            ->get();
        } elseif (!empty($status)) {
            $booking= Booking::where('status', $status)
            ->get();
        } elseif (!empty($date) && !empty($service)) {
            $booking= Booking::where('date', $date)
            ->where('service', $service)
            ->get();
        } elseif (!empty($date) && !empty($user)) {
            $booking= Booking::where('date', $date)
            ->where('user', $user)
            ->get();
        } elseif (!empty($date) && !empty($Client)) {
            $booking= Booking::where('date', $date)
            ->where('Client', $Client)
            ->get();
        } elseif (!empty($date) && !empty($status)) {
            $booking= Booking::where('date', $date)
            ->where('status', $status)
            ->get();
        } elseif (!empty($user) && !empty($Client)) {
            $booking= Booking::where('user', $user)
              ->where('Client', $Client)
              ->get();
        } elseif (!empty($Client) && !empty($service)) {
            $booking= Booking::where('Client', $Client)
            ->where('service', $service)
            ->get();
        } elseif (!empty($Client) && !empty($service)&&!empty($user) && !empty($status)) {
            $booking= Booking::where('Client', $Client)
                      ->where('service', $service)
                      ->where('user', $user)
                      ->where('status', $status)
                      ->get();
        } elseif (!empty($Client) && !empty($service)&&!empty($user) && !empty($date)) {
            $booking= Booking::where('Client', $Client)
                        ->where('service', $service)
                        ->where('user', $user)
                        ->where('$date', $date)
                        ->get();
        } elseif (!empty($name) &&!empty($created) && !empty($service_name)&&!empty($user) && !empty($date)) {
            $customer = Customer::where('first_name', 'LIKE', "%{$name}%")
                ->orwhere('last_name', 'LIKE', "%{$name}%")
                ->orWhereRaw("concat(first_name,' ', last_name) like '%" . $name . "%' ")
                ->get();

            $service= Service::where('name', $name)
                 ->where('user', $user)
                 ->where('$date', $date)
                 ->where('created', $created)
                 ->get();
        }

        return response()->json(['booking'=>$booking]);
    }







        public function filterClient(Request $req)
        {
            $name=$phone_number=$email="";
            $name =$req->name;
            $phone_number = $req->phone_number;
            $email = $req->email;



            if (!empty($email)) {
                $customer= Customer::where('email', $email)
                ->get();
            } elseif (!empty($name) && !empty($phone_number)) {
                $customer = Customer::where('first_name', 'LIKE', "%{$name}%")
                    ->orwhere('last_name', 'LIKE', "%{$name}%")
                    ->orWhereRaw("concat(first_name,' ', last_name) like '%" . $name . "%' ")
                    ->where('phone_number', $phone_number)
                    ->get();
            }

            return response()->json(['customer'=>$customer]);
        }


            public function filterEmployee(Request $req)
            {
                $name=$phone_number=$email="";
                $name =$req->name;
                $phone_number = $req->phone_number;
                $email = $req->email;
                $role = $req->role;
                $id = $req->id;

                if (!empty($email)) {
                    $user= User::where('email', $email)
                    ->get();
                } elseif (!empty($name)) {
                    $user = User::where('name', $name)
                        ->get();
                } elseif (!empty($phone_number)) {
                    $user = User::where('phone_number', $phone_number)
                        ->get();
                } elseif (!empty($name) && !empty($phone_number)) {
                    $user = User::where('name', $name)
                        ->where('phone_number', $phone_number)
                        ->get();
                } elseif (!empty($role)) {
                    $role = Role::where('name', $role)->first();
                    $user= User::where('role_id', $role->id)->get();
                }

                return response()->json(['user'=>$user]);
            }



               public function register(Request $req)
               {
                   $validator=Validator::make($req->all(), [
                       'name' =>'required|string|max:200',
                       'email' =>'required|email|max:191|unique:users,email',
                       'password' =>'required',
                       'category_id'=>'required',
                       'type'=>'required',
                   ]);



                   if ($validator->fails()) {
                       return response()->json([
                           'validation_error'=>$validator->messages(),
                       ]);
                   } else {
                       //$lock=1;
                       // DB::beginTransaction();
                       // try{
                       $address=AddressController::createAddress($req->street, $req->city, $req->country);
                       $company =Company::create([
                       'name'=>$req->name,
                       'phone_number'=>$req->phone_number,
                       'category_id'=>$req->category_id,
                       'logo'=>$req->logo,
                       'description'=>$req->description,
                       'type'=>$req->type,
                       'address_id'=>$address->id,
                       ]);
                       DB::commit();

                       // }catch (Exception $e) {

                       return response()->json([
                           "result"=>"Operation faild"
                           ]);
                       $lock=0;
                       // }


                       //if($lock){
                       // try{
                       $role=Role::where('name', 'admin')->first();

                       $user =User::create([
                       'role_id'=>$role->id,
                       'name'=>$req->name,
                       'email'=>$req->email,
                       'password'=>Hash::make($req->password),
                       'company_id'=> $company->id,
                       ]);
                //    }
                       // catch (Exception $e) {
                       // DB::rollBack();
                       return response()->json([
                           "result"=>"Operation faild"
                           ]);

                    //    $lock=0;
                       //  }}


                //    if($lock){
                //     try{
                //         $token=$user->createToken('myapptoken')->plainTextToken;
                       $response=[

                       ];
                       DB::commit();
                       return  response()->json([
                           "result"=>"company account created successfully",
                           "token"=>$token,
                           "user"=>$user,
                           "company"=>$company,
                           ]);

                       //  }catch(Exception $e){

                       DB::rollBack();
                       return response()->json([
                           "result"=>"Operation faild"
                           ]);
                   }
               }





           // {
    //     "name":"beauty77",
    //     "category_id":"1",
    //     "street":"qwe122",
    //     "city":"jenin",
    //     "country":"palestine",
    //     "email":"aghmaaaaaaa@yahoo.com",
    //     "password":"123456",
    //     "phone_number":"0599932123",
    //     "description":"qqqqqq",
    //     "logo":"image",
    //     "type":"0",
    //         }





               public function getDetails($id)
               {
                   $company=Company::find($id);

                   $id=Company::where('id', $id)->get('address_id');

                   $address=AddressController::getAddress($id);

                   return response()->json([
                       $company,$address
                       ]);
               }


               public function updateDetails(Request $req, $id)
               {
                   $company=Company::find($id);


                   $address=AddressController::updateAddress($company->address_id, $req->street, $req->city, $req->country);

                   $company->update([
                       'name' =>$req->name,
                       'email'=>$req->email,
                       'phone_number'=>$req->phone_number,
                       'category_id'=>$req->category_id,
                       'logo'=>$req->logo,
                       'description'=>$req->description,
                       'type'=>$req->type,
                       'address_id'=>$address->id,


                    ]);
                   return response()->json([$company,$address]);
               }


               // {
        //     "name":"beauty77"
        //     "category_id":"1",
        //     "role_id":"1",
        //     "street":"qwe122",
        //     "city":"jenin",
        //     "country":"palestine",
        //     "email":"aghmaaaaaa@yahoo.com",
        //     "password":"123456",
        //     "phone_number":"0599932123",
        //     "description":"qqqqqq",
        //     "logo":"image",
        //     "type":"0",
        //     "address_id":"1"
        //         }




               public function delete($id)
               {
                   $result= Company::where('id', $id)->delete();
                   if ($result) {
                       return ["result"=>"Company account has been delete"];
                   } else {
                       return ["result"=>"Operation faild"];
                   }
               }

               public static function getCompanyType($id)
               {
                   return  $company=Company::select('type')->where('id', $id)->get();
               }


               //mobile

               public function searchForCompany(Request $req)
               {
                   $name = $req->name;
                   $company = Company::where('name', $name)->get();
                   return $company;
               }
}
