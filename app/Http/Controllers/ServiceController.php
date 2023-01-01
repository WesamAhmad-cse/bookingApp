<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Company;
use App\Models\Queue;
use App\Models\ServiceQueue;

        class ServiceController extends Controller
        {
        function getDetails($id)
        {
        $service=Service::find($id);

        return $service;
        }


        public function addService(request $req)
        {
        $service= new Service();
        $service->name=$req->input('name');
        $service->duration_time=$req->input('duration_time');
        $service->logo=$req->input('logo');
        $service->save();

        return $service;
        }



        public function delete($id)
        {
        $result= Service::where('id', $id)->delete();
        if ($result) {
        return ["result"=>"user has been delete"];
        } else {
        return ["result"=>"Operation faild"];
        }
        }


        function updateDetails(Request $req, $id)
        {
        $service= Service::find($id);
        $service->name=$req->input('name');
        $service->duration_time=$req->input('duration_time');
        $service->logo=$req->input('logo');
        $service->update();
        return $service;

        }

       function getServiceDurationTime($id)
        { $duration_time= Service::selectRaw('duration_time')->where('id',$id);

        return $duration_time;

        }
         function getAllServices($company_id)
        {
        return "s";
        // $user= User::where('company_id',$company_id)->get();
        // return $user;

        // $queue= Queue::where('user_id',$user)->get();
        // $service_queue = ServiceQueue::selectRaw('source_id')->where('queue_id', $queue)->get();

        //      return $service_queue ;

        }

        function searchForService(Request $req){
            $name = $req->name;
                 $service = Service::where('name', $name)->get();
             return $service;

      }



}