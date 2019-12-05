<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\User;

class categoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        $category = new Category();
        //var_dump($request->data_token->email);exit();

        $user = User::where('email',$request->data_token->email)->first();
        $categorySearched = Category::where('user_id',$user->id)->where('name',$request->name)->first();
        
        if (isset($categorySearched)) {
             return response()->json(["Error" => "No puedes repetir el nombre de la categoria"], 401);
        }else{

            $category->register($user->id,$request->name);
            return response()->json(["Success" => "Se ha creado la categoria"], 201);
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
    public function destroy(Request $request, $id = null)
    {
        /* Para que funcione los datos deben llegar por params en vez de por el formulario del body (si se hace desde el formulario del body da null) */

        $user = User::where('email',$request->data_token->email)->first();
        $category = Category::where('user_id',$user->id)->where('name',$request->name)->first();

        
        if (!isset($category)) {
             return response()->json(["Error" => "No existe la categoria"], 401);
        }else{
            $category->delete();
            return response()->json(["Success" => "Se ha borrado la categoria"], 201);
        }
    }
}
