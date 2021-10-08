<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use \Illuminate\Support\Facades\Hash;
use \App\Models\Users;
use \Illuminate\Support\Facades\DB;
use \Illuminate\Support\Str;


class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth',['except'=>['getStore,changePassword']]);
    }

    public function register(Request $request){
        $name = $request->input('name');
        $phoneNumber = $request->input('phone');
        $birthday = $request->input('tgl_lahir');
        $email = $request->input('email');
        $avatar= $request->file('avatar');
        $token_fcm = $request->input('token_fcm');
        $api_token = base64_encode(Str::random(40));

        if($avatar){
            $foto = time().$avatar->getClientOriginalName();
            $avatar->move('images',$foto);
        }else{
            $foto = 'default.png';
        }
               

        $insert = Users::create([
                'nama'=>$name,
                'no_hp'=>$phoneNumber,
                'avatar'=>$foto,
                'tgl_lahir'=>$birthday,
                'email'=>$email,
                'status_delete'=>0,
                'token_fcm'=>$token_fcm,
                'api_token'=>$api_token
        ]);
    

        if($insert){
            return response()->json([
                'success'=>true,
                'message'=>'Register Sukses',
                'data'=>$insert
            ],201);
        }else{
            return response()->json([
                'success'=>false,
                'message'=>'register failed failed',
                'data'=>$name
            ],401);
        }

    }



    public function getUser(Request $request){
        $phone = $request->input('phone');
        $token_fcm = $request->input('token_fcm');
        $api_token = base64_encode(Str::random(40));

        $user =  Users::where('no_hp', $phone)
        ->where('status_delete',0)
        ->first();
        
        if($user){
                $user->update([
                    'api_token'=>$api_token,
                    'token_fcm'=>$token_fcm
                ]);
            return response()->json([
                'success'=>true,
                'message'=>'success',
                'data'=>$user
            ],200);
        }else{
            return response()->json([
                'success'=>false,
                'message'=>'data not found',
            ],404);
        }
    }

    public function updateUser(Request $request,$id){
        $image= $request->file('image');
        $delete= $request->input('status_delete');
        if($image){
            $foto = time().$image->getClientOriginalName();
            $image->move('images',$foto);
         }else{
            $foto = 'default.png';
        }

        if($delete){
                $update = Users::whereId($id)->update([
                    "image"=>$foto,
                    "status_delete"=>$delete
                ]);
        }else{
            $update = Users::whereId($id)->update([
                    "image"=>$foto
                ]);
        }

        

        if($update){
            return response()->json([
                'success'=>true,
                'message'=>'success',
                'data'=>$update
            ],200);
        }else{
            return response()->json([
                'success'=>false,
                'message'=>'data not found',
            ],404);
        }
    }
    

//     public function changePassword(Request $request,$id){
//         $oldPassword = $request->input('oldPassword');
//         $password    = $request->input('password');
//         $id          = $request->input('name_user');

// //        $store =  Store::where('id',8);
//         $store =  Store::where('id',"8")->first();

//         if(Hash::check($oldPassword, $store->password)){
//             $store->update([
//                 'password'=>Hash::make($password)
//             ]);
//             return response()->json([
//                 'success'=>true,
//                 'message'=>'sukses'
//             ],201);
//         }else{
//             return response()->json([
//                 'success'=>false,
//                 'message'=>'Password yang anda masukan salah'
//             ],401);
//         }

//     return response()->json([
//         'data'=>$store,
//         'passsword'=>$password
//     ],201);

//     }

    // public function delete($id){
    //     $store = Store::find($id);
    //     if($store){
    //         $delete = Store::whereId($id)->update([
    //             'status_delete'     => 1,
    //         ]);

    //         if($delete){
    //             return response()->json([
    //                 'success'=>true,
    //                 'message'=>'data sukses di delete',
    //             ],201);
    //         }else{
    //             return response()->json([
    //                 'success'=>false,
    //                 'message'=>'data gagal di delete',
    //             ],401);
    //         }
    //     }else{
    //         return response()->json([
    //             'success'=>false,
    //             'message'=>'data yang ingin anda delete tidak tersedia',
    //         ],401);
    //     }
    // }
}
