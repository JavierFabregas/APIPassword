<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Helper\Token;
use App\Category;
class userController extends Controller
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
        $user = new User();
        if (!$user->userExists($request->email)){
            $user->register($request);

            $data_token = [
                "email" => $user->email,
            ];

            $token = new Token($data_token);
            
            $tokenEncoded = $token->encode();

            return response()->json([
                "token" => $tokenEncoded
            ], 201);
        }else{
            return response()->json(["Error" => "No se pueden crear usuarios con el mismo Email o con el Email vacío"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id = null)
    {

        $user = User::where('email',$request->data_token->email)->first();
        $infoToShow = [];
        if (isset($user)) {    
           $categories = Category::where('user_id',$user->id)->get();
            return response()->json(["User" => $user, "Categories" => $categories,"Categories2" => $categories]);
        }else{
            return response()->json(["Error" => "No existe un usuario con ese mail"]);
        }
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

    public function login(Request $request){

        $data_token = ['email'=>$request->email];
        
        $user = User::where($data_token)->first();  
       
        if ($user!=null) {       
            if($request->password == $user->password)
            {       
                $token = new Token($data_token);
                $tokenEncoded = $token->encode();

                return response()->json(["token" => $tokenEncoded], 201);
            }   
        }     
        return response()->json(["Error" => "No se ha encontrado"], 401);
    }
}
