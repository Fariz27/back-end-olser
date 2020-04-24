<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service;
use App\Image_file;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
	        $data["count"] = Service::count();
            $data["service"] = Service::join('users', 'service.id_partner', '=', 'users.id')    
                                        ->join('image_file', 'service.image_id', '=', 'image_file.id')
                                        ->select('service.*','users.name AS usname','image_file.name AS imname')->get();
	        $data["status"] = 1;
	        return response($data);

	    } catch(\Exception $e){
			return response()->json([
			  'status' => '0',
			  'message' => $e->getMessage()
			]);
      	}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {
        try{
    		$validator = Validator::make($request->all(), [
    			'img'                         => 'required',
				'service'			          => 'required|string|max:500',
                'price_range_min'			  => 'required|integer',
                'price_range_max'			  => 'required|integer',
                'location'                    => 'required', 
                'description'                    => 'required', 
                
    		]);

    		if($validator->fails()){
    			return response()->json([
    				'status'	=> 0,
    				'message'	=> $validator->errors()
    			]);
            }
            

            $file = $request->file('img');
            $tujuan_upload = 'data_file';
            $nameimg = $file->getClientOriginalExtension().Str::random(6).'.'.$file->getClientOriginalExtension();
            $file->move($tujuan_upload,$nameimg);

            $dataImage = new Image_file();
            $dataImage->partner_id  = $id;
            $dataImage->name        = $nameimg;
            $dataImage->save();
            $imageDat = Image_file::where('name',$nameimg)->first();

            $data = new Service();
	        $data->id_partner       = $id;
	        $data->image_id         = $imageDat->id;
	        $data->service          = $request->input('service');
            $data->price_range_min  = $request->input('price_range_min');
            $data->price_range_max  = $request->input('price_range_max');
	        $data->location         = $request->input('location');
	        $data->description      = $request->input('description');
	        $data->save();

    		return response()->json([
    			'status'	=> '1',
                'message'	=> $imageDat->id
                
    		], 201);

      } catch(\Exception $e){
            return response()->json([
                'status' => '0',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
