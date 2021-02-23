<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Video;   
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{

    public function index()
    {
    	try{
	        $data["count"] = Video::count();
	        $video = array();

	        foreach (Video::all() as $p) {
	            $item = [
	                "id"          => $p->id,
	                "judul"       => $p->judul,
	                "video"       => $p->video,
	                "created_at"  => $p->created_at,
	                "updated_at"  => $p->updated_at
	            ];

	            array_push($video, $item);
	        }
	        $data["video"] = $video;
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
	        $data["count"] = Video::count();
	        $video = array();

	        foreach (Video::take($limit)->skip($offset)->get() as $p) {
	            $item = [
	                "id"          => $p->id,
	                "judul"       => $p->judul,
	                "video"     => $p->video,
	                "created_at"  => $p->created_at,
	                "updated_at"  => $p->updated_at
	            ];

	            array_push($video, $item);
	        }
	        $data["video"] = $video;
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
    			'video'      => 'required|string|max:255',
    		]);

    		if($validator->fails()){
    			return response()->json([
    				'status'	=> 0,
    				'message'	=> $validator->errors()
    			]);
    		}

    		$data = new Video();
	        $data->judul = $request->input('judul');
	        $data->video = $request->input('video');
	        $data->save();

    		return response()->json([
    			'status'	=> '1',
    			'message'	=> 'Data video berhasil ditambahkan!'
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
    		'video'      => 'required|string|max:255',
		]);

      	if($validator->fails()){
      		return response()->json([
      			'status'	=> '0',
      			'message'	=> $validator->errors()
      		]);
      	}

      	//proses update data
      	$data = Video::where('id', $id)->first();
        $data->judul = $request->input('judul');
        $data->video = $request->input('video');
        $data->save();

      	return response()->json([
      		'status'	=> '1',
      		'message'	=> 'Data video berhasil diubah'
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

            $delete = Video::where("id", $id)->delete();

            if($delete){
              return response([
              	"status"	=> 1,
                  "message"   => "Data video berhasil dihapus."
              ]);
            } else {
              return response([
                "status"  => 0,
                  "message"   => "Data video gagal dihapus."
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
