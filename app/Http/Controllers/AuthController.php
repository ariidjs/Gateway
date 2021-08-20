<?php

namespace App\Http\Controllers;

use App\Models\Drivers;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct()
    {
        //
    }

    public function loginDriver(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all()], 401
            );
        }


        $email = $request->input('email');
        $password = $request->input('password');
        $fcmToken = $request->input('fcm_token');

        $driver = Drivers::where('email', $email)->first();

        if ($driver) {
            if (Hash::check($password, $driver->password)) {
                $token = Str::random(32);
                if ($fcmToken) {
                    $driver->update([
                        'api_token' => $token,
                        'fcm_token' => $fcmToken
                    ]);
                } else {
                    $driver->update([
                        'api_token' => $token,
                        'fcm_token' => $driver->fcm_token
                    ]);
                }
                return response()->json([
                    'success' => true,
                    'message' => "Login Success!",
                    'data' => [
                        'api_token' => $driver->api_token,
                        'fcm_token' => $driver->fcm_token
                    ]

                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Login Failed! Wrong Password",
                    'data' => null
                ], 401);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => "Login Failed! Email not registered!",
                'data' => null
            ], 404);
        }
    }

    public function signUpDriver(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'email' => 'required|unique:drivers',
            'password' => 'required',
            'no_hp' => 'required|unique:drivers',
            'no_kendaraan' => 'required|unique:drivers',
            'foto_stnk' => 'required',
            'foto_ktp' => 'required',
            'foto_formal' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all()], 401
            );
        }

        if ($request->hasFile('foto_stnk') && $request->hasFile('foto_ktp') && $request->hasFile('foto_formal')) {
            $foto_stnk = Str::random(32);
            $foto_ktp = Str::random(32);
            $foto_formal = Str::random(32);
            $destinationPath = storage_path('/storage/app/public/imageupload');
            $request->file('foto_stnk')->move($destinationPath, $foto_stnk . '.' . $request->file('foto_stnk')->getClientOriginalExtension());
            $request->file('foto_ktp')->move($destinationPath, $foto_ktp . '.' . $request->file('foto_ktp')->getClientOriginalExtension());
            $request->file('foto_formal')->move($destinationPath, $foto_formal . '.' . $request->file('foto_formal')->getClientOriginalExtension());


            $data = Drivers::create([
                'nama' => $request->input('nama'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'no_hp' => $request->input('no_hp'),
                'no_kendaraan' => $request->input('no_kendaraan'),
                'foto_stnk' => $foto_stnk . '.' . $request->file('foto_stnk')->getClientOriginalExtension(),
                'foto_ktp' => $foto_ktp . '.' . $request->file('foto_ktp')->getClientOriginalExtension(),
                'foto_formal' => $foto_formal . '.' . $request->file('foto_formal')->getClientOriginalExtension(),
                'total_trans' => 0,
                'saldo' => 0,
                'rating' => 0.00,
                'status' => 0

            ]);

            if ($data) {
                return response()->json([
                    'success' => true,
                    'message' => "Register is Successfully",
                    'data' => $data
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Register is not Successfully",
                    'data' => ''
                ], 400);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => "Register is not Successfully",
                'data' => ''
            ], 400);
        }
    }

    public function storeLogin(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');
        $token_fcm = $request->input('token_fcm');
        $user = Store::where('email',$email)->first();

        if($user){
            if(Hash::check($password, $user->password)){
                $api_token = base64_encode(\Illuminate\Support\Str::random(40));

                $user->update([
                    'api_token'=>$api_token,
                    'token_fcm'=>$token_fcm
                ]);
                return response()->json([
                    'success'=>true,
                    'message'=>'Login Sukses',
                    'data'=>[
                        "user"=>$user,
                        "token"=>$api_token
                    ]
                ],201);
            }else{
                return response()->json([
                    'success'=>false,
                    'message'=>'Password Salah'
                ],401);
            }
        }else{
            return response()->json([
                'success'=>false,
                'message'=>'email yang anda masukan tidak tersedia'
            ],400);
        }
    }

    public function signUpStore(Request $request){
        $name_store = $request->input('name_store');
        $name_user = $request->input('name_user');
        $phoneNumber = $request->input('phoneNumber');
        $image_ktp = $request->file('image_ktp');
        $saldo = "0";
        $email = $request->input('email');
        $status_store = 0;
        $image_store = $request->file('image_store');
        $address = $request->input('address');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $status_delete = 0;
        $token_fcm = $request->input('token_fcm');
        $api_token = $request->input('api_token');
        $password = Hash::make($request->input('password'));

        $checkEmail =  Store::where('email', '=', $email)->first();
        $checkPhone =  Store::where('phoneNumber', '=', $phoneNumber)->first();

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

        if($checkEmail || $checkPhone){
            return response()->json([
                'success'=>false,
                'message'=>'Email atau nomor telephone sudah terdaftar'
            ],400);
        }else{
            $register = Store::create([
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
                'token_fcm'=>$token_fcm,
                'api_token'=>$api_token
            ]);

            if($register){
                return response()->json([
                    'success'=>true,
                    'message'=>'success',
                    'data'=>$register
                ],201);
            }else{
                return response()->json([
                    'success'=>false,
                    'message'=>'Failese'
                ],400);
            }
        }
    }
//    public function testGuzzel()
//    {
//        $client = new \GuzzleHttp\Client();
//        $request = $client->get('https://rizkhan.serbatech.my.id/api/v1/penerima');
//        $response = $request->getBody();
//
//        return response()->json(json_decode($response));
//
//    }
}
