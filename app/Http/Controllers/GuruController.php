<?php

namespace App\Http\Controllers;

use App\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class GuruController extends Controller
{

    public function index()
    {
    	try{
	        $data["count"] = Guru::count();
	        $guru = array();

	        foreach (Guru::all() as $p) {
	            $item = [
	                "id"          => $p->id,
	                "email"       => $p->email,
	                "nama_guru"   => $p->nama_guru, 
	                "sekolah"     => $p->sekolah,
	                "created_at"  => $p->created_at,
	                "updated_at"  => $p->updated_at
	            ];

	            array_push($guru, $item);
	        }
	        $data["guru"] = $guru;
	        $data["status"] = 1;
	        return response($data);

	    } catch(\Exception $e){
			return response()->json([
			  'status' => '0',
			  'message' => $e->getMessage()
			]);
      	}
    }

    public function getAll($limit = 10, $offset = 0)
    {
    	try{
	        $data["count"] = Guru::count();
	        $guru = array();

	        foreach (Guru::take($limit)->skip($offset)->get() as $p) {
	            $item = [
	                "id"          => $p->id,
	                "email"       => $p->email,
	                "nama_guru"   => $p->nama_guru, 
	                "sekolah"     => $p->sekolah,
	                "created_at"  => $p->created_at,
	                "updated_at"  => $p->updated_at
	            ];

	            array_push($guru, $item);
	        }
	        $data["guru"] = $guru;
	        $data["status"] = 1;
	        return response($data);

	    } catch(\Exception $e){
			return response()->json([
			  'status' => '0',
			  'message' => $e->getMessage()
			]);
      	}
    }

    public function store(Request $request)
    {
      try{
    		$validator = Validator::make($request->all(), [
    			'nama_guru'       => 'required|string|max:255',
				'email'			  => 'required|string|max:255|unique:Guru',
				'sekolah'		  => 'required|string|max:255',
				'kategori'		  => 'required|string|max:255',
				'password'		  => 'required|string|max:255',
    		]);

    		if($validator->fails()){
    			return response()->json([
    				'status'	=> 0,
    				'message'	=> $validator->errors()
    			]);
    		}

    		$guru = new Guru();
	        $guru->nama_guru = $request->input('nama_guru');
	        $guru->email = $request->input('email');
	        $guru->sekolah = $request->input('sekolah');
	        $guru->kategori = $request->input('kategori');
	        $guru->password = Hash::make($request->input('password'));
			$guru->save();

    		return response()->json([
    			'status'	=> '1',
    			'message'	=> 'Data guru berhasil ditambahkan!'
    		], 201);

      } catch(\Exception $e){
            return response()->json([
                'status' => '0',
                'message' => $e->getMessage()
            ]);
        }
  	}


    public function update(Request $request, $id)
    {
      try {
      	//proses update data
      	$data = Guru::where('id', $id)->first();
        $data->nama_guru = $request->input('nama_guru');
        $data->email = $request->input('email');
        $data->sekolah = $request->input('sekolah');
        $data->save();

      	return response()->json([
      		'status'	=> '1',
      		'message'	=> 'Data guru berhasil diubah'
      	]);
        
      } catch(\Exception $e){
          return response()->json([
              'status' => '0',
              'message' => $e->getMessage()
          ]);
      }
	}

    public function delete($id)
    {
        try{

            $delete = Guru::where("id", $id)->delete();

            if($delete){
              return response([
              	"status"	=> 1,
                  "message"   => "Data guru berhasil dihapus."
              ]);
            } else {
              return response([
                "status"  => 0,
                  "message"   => "Data guru gagal dihapus."
              ]);
            }
        } catch(\Exception $e){
            return response([
            	"status"	=> 0,
                "message"   => $e->getMessage()
            ]);
        }
    }

}
