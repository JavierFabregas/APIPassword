<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Helper\Token;
use App\Category;
use App\Password;
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

        /* Para que funcione los datos deben llegar por params en vez de por el formulario del body (si se hace desde el formulario del body da null) */


        $user = User::where('email',$request->data_token->email)->first();

        $infoToShow = array();
        array_push($infoToShow, $user);
        
        if (isset($user)) {    

           $categories = Category::where('user_id',$user->id)->get();

           foreach ($categories as $key => $category) {
               array_push($infoToShow, ["Category" => $category]);     

               $passwords = Password::where('category_id',$category->id)->get();
               array_push($infoToShow,["Password" => $passwords]);
           }
            return response()->json(["Info user" => $infoToShow]);

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
        $user = User::where('email',$request->data_token->email)->first();

        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = $request->password;

        $user->update();
        return response()->json(["Success" => "Se ha modificado el usuario"], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        /* Para que funcione los datos deben llegar por params en vez de por el formulario del body (si se hace desde el formulario del body da null) */
        
        $user = User::where('email',$request->data_token->email)->first();

         if (!isset($user)) {
             return response()->json(["Error" => "No existe el usuario"], 401);
        }else{
            $user->delete();
            return response()->json(["Success" => "Se ha borrado el usuario"], 201);
        }
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
