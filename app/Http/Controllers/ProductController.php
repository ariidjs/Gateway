<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use \Illuminate\Support\Facades\Hash;
use \App\Models\Product;
use \Illuminate\Support\Facades\DB;
use \App\Services\ProductService;
use \App\Traits\ApiResponser;

class ProductController extends Controller
{
    use ApiResponser;
     public $productService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProductService $productService)
    {
         $this->productService = $productService;
    }

 public function getProductStore(){

        $responseData = json_decode($this->successResponse($this->productService->getProductStore(0))->original,true);

        $dataResponse = $responseData["data"];

        return $dataResponse;
 }
}
