<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Picture;   
use Illuminate\Support\Facades\Validator;

class PictureController extends Controller
{

    public function index()
    {
    	try{
	        $data["count"] = Picture::count();
	        $picture = array();

	        foreach (Picture::all() as $p) {
	            $item = [
	                "id"          => $p->id,
	                "judul"       => $p->judul,
	                "picture"     => $p->picture,
	                "deskripsi"   => $p->deskripsi,
	                "created_at"  => $p->created_at,
	                "updated_at"  => $p->updated_at
	            ];

	            array_push($picture, $item);
	        }
	        $data["picture"] = $picture;
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
	        $data["count"] = Picture::count();
	        $picture = array();

	        foreach (Picture::take($limit)->skip($offset)->get() as $p) {
	            $item = [
	                "id"          => $p->id,
	                "judul"       => $p->judul,
	                "picture"     => $p->picture,
	                "deskripsi"   => $p->deskripsi,
	                "created_at"  => $p->created_at,
	                "updated_at"  => $p->updated_at
	            ];

	            array_push($picture, $item);
	        }
	        $data["picture"] = $picture;
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
    			'judul'      => 'required|string|max:255',
    			'picture'    => 'required|string|max:255',
    			'deskripsi'  => 'required|string|max:255',
    		]);

    		if($validator->fails()){
    			return response()->json([
    				'status'	=> 0,
    				'message'	=> $validator->errors()
    			]);
    		}

    		$data = new Picture();
	        $data->judul = $request->input('judul');
	        $data->picture = $request->input('picture');
	        $data->deskripsi = $request->input('deskripsi');
	        $data->save();

    		return response()->json([
    			'status'	=> '1',
    			'message'	=> 'Data picture berhasil ditambahkan!'
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
      	$validator = Validator::make($request->all(), [
			'judul'      => 'required|string|max:255',
    		'picture'    => 'required|string|max:255',
    		'deskripsi'  => 'required|string|max:255',
		]);

      	if($validator->fails()){
      		return response()->json([
      			'status'	=> '0',
      			'message'	=> $validator->errors()
      		]);
      	}

      	//proses update data
      	$data = Picture::where('id', $id)->first();
        $data->judul = $request->input('judul');
        $data->picture = $request->input('picture');
        $data->deskripsi = $request->input('deskripsi');
        $data->save();

      	return response()->json([
      		'status'	=> '1',
      		'message'	=> 'Data picture berhasil diubah'
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

            $delete = Picture::where("id", $id)->delete();

            if($delete){
              return response([
              	"status"	=> 1,
                  "message"   => "Data picture berhasil dihapus."
              ]);
            } else {
              return response([
                "status"  => 0,
                  "message"   => "Data picture gagal dihapus."
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
