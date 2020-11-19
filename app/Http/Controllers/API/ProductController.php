<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Validator;
use App\product;
class ProductController extends Controller
{
  

  public function createProduct(Request $request) {
    
      
        $validator = Validator::make($request->all(), [ 
          'name' => 'required|min:2',
          'code' => 'required',
        'tax_percent' => 'required',
        'cess_percent' => 'required',
        'quantity' => 'required',
        'purchase_cost' => 'required',
        'mrp' => 'required',
        'hsn_code' => 'required',
        'uom' => 'required',
        'description' => 'required|min:10',
          
      ]);
      if ($validator->fails()) { 
        return response()->json(['error'=>$validator->errors()], 401);   

    }

        $Product = new product;
        $Product->code = $request->code;
        $Product->name = $request->name;
        $Product->category_id = $request->category_id;
        $Product->exempted_id = $request->exempted_id;
        $Product->tax_type_id = $request->tax_type_id;
        $Product->tax_percent = $request->tax_percent;
        $Product->cess_percent = $request->cess_percent;
        $Product->quantity = $request->quantity;
        $Product->purchase_cost = $request->purchase_cost;
        $Product->mrp = $request->mrp;
        $Product->hsn_code = $request->hsn_code;
        $Product->uom = $request->uom;
        $Product->description = $request->description;

        
        $Product->save();
  
        return response()->json([
          "message" => "Product record created",
         
          'data' =>  $Product
        ],
         201);


      }
}
