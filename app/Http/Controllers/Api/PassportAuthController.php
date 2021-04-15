<?php
 
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
 
class PassportAuthController extends Controller
{
    /**
     * Registration Req
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'position' => 'required',
            'status' => 'in:active, inactive'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
  
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'position' => $request->position,
            'status' => $request->status
        ]);
  
        $token = $user->createToken('Laravel8PassportAuth')->accessToken;
  
        return response()->json(['token' => $token], 200);
    }
  
    /**
     * Login Req
     */
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
  
        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('Laravel8PassportAuth')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
 
    /**
     * User info Req
     */
    public function details(){
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }

    /**
     * Logout Req
     */
    public function logout(Request $request){
        $logout = $request->user()->token()->revoke();
        if($logout){
            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        }
    }

    /**
     * Update user Req
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request, [
            'name' => 'required|min:4',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'position' => 'required',
            'status' => 'in:active, inactive'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->position = $request->position;
        $user->status = $request->status;
        $user->save();
        return response()->json([
            "success" => true,
            "message" => "User updated successfully.",
            "data" => $user
        ]);
    }

    /**
     * Delete user Req
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            "success" => true,
            "message" => "User deleted successfully.",
            "data" => $user
        ]);
    }

    /**
     * Find user by id Req
     */
    public function show($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return $this->sendError('user not found.');
        }

        return response()->json([
            "success" => true,
            "message" => "User retrieved successfully.",
            "data" => $user
        ]);
    }
}