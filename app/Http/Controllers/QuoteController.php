<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Quote;   
use Illuminate\Support\Facades\Validator;

class QuoteController extends Controller
{

    public function index()
    {
    	try{
	        $data["count"] = Quote::count();
	        $quote = array();

	        foreach (Quote::all() as $p) {
	            $item = [
	                "id"          => $p->id,
	                "quote"       => $p->quote,
	                "created_at"  => $p->created_at,
	                "updated_at"  => $p->updated_at
	            ];

	            array_push($quote, $item);
	        }
	        $data["quote"] = $quote;
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
	        $data["count"] = Quote::count();
	        $quote = array();

	        foreach (Quote::take($limit)->skip($offset)->get() as $p) {
	            $item = [
	                "id"          => $p->id,
	                "quote"       => $p->quote,
	                "created_at"  => $p->created_at,
	                "updated_at"  => $p->updated_at
	            ];

	            array_push($quote, $item);
	        }
	        $data["quote"] = $quote;
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
    			'quote'    => 'required|string|max:255',
    		]);

    		if($validator->fails()){
    			return response()->json([
    				'status'	=> 0,
    				'message'	=> $validator->errors()
    			]);
    		}

    		$data = new Quote();
	        $data->quote = $request->input('quote');
	        $data->save();

    		return response()->json([
    			'status'	=> '1',
    			'message'	=> 'Data quote berhasil ditambahkan!'
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
    		'quote'    => 'required|string|max:255',
		]);

      	if($validator->fails()){
      		return response()->json([
      			'status'	=> '0',
      			'message'	=> $validator->errors()
      		]);
      	}

      	//proses update data
      	$data = Quote::where('id', $id)->first();
        $data->quote = $request->input('quote');
        $data->save();

      	return response()->json([
      		'status'	=> '1',
      		'message'	=> 'Data quote berhasil diubah'
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

            $delete = Quote::where("id", $id)->delete();

            if($delete){
              return response([
              	"status"	=> 1,
                  "message"   => "Data quote berhasil dihapus."
              ]);
            } else {
              return response([
                "status"  => 0,
                  "message"   => "Data quote gagal dihapus."
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
