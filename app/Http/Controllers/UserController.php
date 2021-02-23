<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function index()
    {
        $data["count"] = User::count();
        $user = array();

        foreach (User::all() as $p) {
            $item = [
                "id"          		=> $p->id,
                "phone"       		=> $p->phone,
                "name"        		=> $p->name,
                "email"    	  		=> $p->email,
                "profile_picture"	=> $p->profile_picture,
                "created_at"  		=> $p->created_at,
                "updated_at"  		=> $p->updated_at
            ];

            array_push($user, $item);
        }
        $data["user"] = $user;
        $data["status"] = 1;
        return response($data);
    }

    public function getAll($limit = 10, $offset = 0){
        $data["count"] = User::count();
        $user = array();

        foreach (User::take($limit)->skip($offset)->get() as $p) {
            $item = [
                "id"          		=> $p->id,
                "phone"        		=> $p->phone,
                "name"        		=> $p->name,
                "email"    	  		=> $p->email,
                "profile_picture"	=> $p->profile_picture,
                "created_at"  		=> $p->created_at,
                "updated_at"  		=> $p->updated_at
            ];
            array_push($user, $item);
        }
        $data["user"] = $user;
        $data["status"] = 1;
        return response($data);
    }
	//fungsi untuk login
	public function login(Request $request){
		$credentials = $request->only('email', 'password');

		try {
			if(!$token = JWTAuth::attempt($credentials)){
				return response()->json([
						'logged' 	=>  false,
						'message' 	=> 'Invalid email and password'
					]);
			}
		} catch(JWTException $e){
			return response()->json([
						'logged' 	=> false,
						'message' 	=> 'Generate Token Failed'
					]);
		}
		return response()->json([
					"logged"    => true,
                    "token"     => $token,
                    "message" 	=> 'Login berhasil'
		]);
	}

    public function delete($id)
    {
        try{

            User::where("id", $id)->delete();

            return response([
                "message"   => "Data berhasil dihapus."
            ]);
        } catch(\Exception $e){
            return response([
                "message"   => $e->getMessage()
            ]);
        }
    }

	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:User',
			'password' => 'required|string|min:6',
		]);

		if($validator->fails()){
			return response()->json([
				'status'	=> 0,
				'message'	=> $validator->errors()
			]);
		}

		$user = new User();
		$user->name 			= $request->name;
		$user->phone 			= $request->phone;
		$user->email 			= $request->email;
		$user->profile_picture 	= $request->profile_picture;
		$user->password		 	= Hash::make($request->password);
		$user->save();

		$token = JWTAuth::fromUser($user);

		return response()->json([
			'status'	=> '1',
			'message'	=> 'Admin berhasil ditambahkan'
			//'user'		=> $user,
		], 201);
	}

	public function update(Request $request)
	{
		//proses update data
		$user = User::where('id', $request->id)->first();
		$user->name 			= $request->name;
		$user->email 			= $request->email;
		$user->phone 			= $request->phone;
		$user->profile_picture 	= $request->profile_picture;
		
		$user->password 		= Hash::make($request->password);
		$user->save();


		return response()->json([
			'status'	=> '1',
			'message'	=> 'Admin berhasil diubah'
		], 201);
	}

	public function LoginCheck(){
		try {
			if(!$user = JWTAuth::parseToken()->authenticate()){
				return response()->json([
						'auth' 		=> false,
						'message'	=> 'Invalid token'
					]);
			}
		} catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
			return response()->json([
						'auth' 		=> false,
						'message'	=> 'Token expired'
					], $e->getStatusCode());
		} catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
			return response()->json([
						'auth' 		=> false,
						'message'	=> 'Invalid token'
					], $e->getStatusCode());
		} catch (Tymon\JWTAuth\Exceptions\JWTException $e){
			return response()->json([
						'auth' 		=> false,
						'message'	=> 'Token absent'
					], $e->getStatusCode());
		}

		 return response()->json([
		 		"auth"      => true,
                "user"    => $user
		 ], 201);
	}

	public function logout(Request $request)
    {

        if(JWTAuth::invalidate(JWTAuth::getToken())) {
            return response()->json([
                "logged"    => false,
                "message"   => 'Logout berhasil'
            ], 201);
        } else {
            return response()->json([
                "logged"    => true,
                "message"   => 'Logout gagal'
            ], 201);
        }

        

    }

}
