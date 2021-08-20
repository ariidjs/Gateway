<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use \Illuminate\Support\Facades\Hash;
use \App\Models\Store;
use \Illuminate\Support\Facades\DB;


class StoreController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function update(Request $request,$id){
        $name_store = $request->input('name_store');
        $name_user = $request->input('name_user');
        $phoneNumber = $request->input('phoneNumber');
        $image_ktp = $request->file('image_ktp');
        $saldo = $request->input('saldo');
        $email = $request->input('email');
        $status_store = $request->input('status_store');
        $image_store = $request->file('image_store');
        $address = $request->input('address');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $status_delete =$request->input('status_delete');
        $token_fcm = $request->input('token_fcm');
        $api_token = $request->input('api_token');
        $password = Hash::make($request->input('password'));

        // return $name_store."fa";


        if($image_ktp){
            $fotoKtp = time().$image_ktp->getClientOriginalName();
            $image_ktp->move('images',$fotoKtp);
        }else{
            $fotoKtp = 'default.png';
        }

        if($image_store){
            $fotoStore = url(time().$image_store->getClientOriginalName());
            $image_store->move('images',$fotoStore);
        }else{
            $fotoStore = 'default.png';
        }

        $update = Store::whereId($id)->update([
                'name_store'=>$name_store,
                'name_user'=>$name_user,
                'phoneNumber'=>$phoneNumber,
                'image_ktp'=>$fotoKtp,
                'saldo'=>$saldo,
                'email'=>$email,
                'status_store'=>$status_store,
                'image_store'=>$fotoStore,
                'address'=>$address,
                'latitude'=>$latitude,
                'longitude'=>$longitude,
                'status_delete'=>$status_delete,
                'password'=>$password,
                // 'token_fcm'=>$token_fcm,
                // 'api_token'=>$api_token
        ]);

        if($update){
            return response()->json([
                'success'=>true,
                'message'=>'update Sukses',
                'data'=>[
                    "user"=>$update
                ]
            ],201);
        }else{
            return response()->json([
                'success'=>false,
                'message'=>'update failed'
            ],401);
        }

    }

    public function getStore($id){
        $store =  Store::where('id', $id)
        ->where('status_delete',0)
        ->first();

        if($store){
            return response()->json([
                'success'=>true,
                'message'=>'success',
                'data'=>$store
            ],401);
        }else{
            return response()->json([
                'success'=>false,
                'message'=>'data tidak tersedia',
            ],401);
        }
    }

    public function delete($id){
        $store = Store::find($id);
        if($store){
            $delete = Store::whereId($id)->update([
                'status_delete'     => 1,
            ]);

            if($delete){
                return response()->json([
                    'success'=>true,
                    'message'=>'data sukses di delete',
                ],201);
            }else{
                return response()->json([
                    'success'=>false,
                    'message'=>'data gagal di delete',
                ],401);
            }
        }else{
            return response()->json([
                'success'=>false,
                'message'=>'data yang ingin anda delete tidak tersedia',
            ],401);
        }
    }
}
