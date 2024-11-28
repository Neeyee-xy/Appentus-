<?php

namespace App\Http\Controllers;
use App\Services\UserService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\Sanctum; 
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;


class UserController extends Controller
{
    protected $userService;
    /**
     * use  User services to isolate concerns.
     *
     * 
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * authenticate User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $rules = [
            'email' => ['required','max:255','email'],
            'password'    => ['required', 'string'],
        
        ];


        $customMessages = [];
        $validator = Validator::make($request->all(), $rules, $customMessages);
       
         if (!$validator->fails()) {
            $user = User::where('email', $request['email'])->first();

            if(!$user || !Auth()->attempt($request->all())){
                    return response()->json(['message' => 'Invalid credentials'], 401); 
            }

            $token = $user->createToken('my-app-token')->plainTextToken;

            return $this->respondWithToken($token);
           
            
        }else{
            return response()->json(['message' => $validator->errors()], 422);
        }
    }
    /**
     * store User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => ['required','string','max:255'],
            'email' => ['required','max:255','email','unique:users,email'],
            'password'    => ['required', 'string', 'confirmed'],
        
        ];


        $customMessages = [];
        $validator = Validator::make($request->all(), $rules, $customMessages);
       
         if (!$validator->fails()) {
            $data = $request->all();
            $data['password']=bcrypt($request->input('password'));
            if ($this->userService->createUser($data)) {
               return response()->json(['message' => 'User created,Kindly log in '], 200);
            }else{
                return response()->json(['message' =>'User could not be created. Check your input and try again.' ], 400);
            }
            
        }else{
            return response()->json(['message' => $validator->errors()], 422);
        }
    }
    /**
     * update User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    { 
        $rules = [

            
            'name' => ['required','string','max:255'],
            'email' => ['required','max:255','email','unique:users,email'],
            'password'    => ['required', 'string', 'confirmed'],
        
        ];


        $customMessages = [];
        $validator = Validator::make($request->all(), $rules, $customMessages);
       
        if (!$validator->fails()) {
            $data = $request->all();
            if($this->userService->updateUser($id, $data)) {
                return response()->json(['message' => 'User updated'], 200);
            }else{
                return response()->json(['message' =>'User could not be updated. Check your input and try again.' ], 400);
            }
            
        }else{
           return response()->json(['message' => $validator->errors()], 422); 
        }
    }
    /**
     * delete User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        
        if ($this->userService->deleteUser($id)) {
                return response()->json(['message' => 'User deleted'], 200);
            }else{
                return response()->json(['message' =>'User could not be deleted' ], 400);
            }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function current_active_user()
    {
        return response()->json(Auth::user());
    }
    /**
     * Return access_token and user's details.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'user' => Auth::user()
        ]);
    }
}
