<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        $user = User::where('username', $request->input('username'))->where('status', true)->first();

        if(!$this->checkAlowedDay($user, $request->input('room'))){
            throw ValidationException::withMessages([
                "The credentials you entered are incorrect"
            ]);
        }
        
        // if (!$user || !Hash::check($request->password, $user->password)) {
        //     throw ValidationException::withMessages([
        //         "The credentials you entered are incorrect"
        //     ]);
        // }
        $formattedDate['id'] = $user->id;
        $formattedDate['name'] = $user->name;
        $formattedDate['username'] = $user->username;
        $formattedDate['created_at'] = $user->created_at->format('d-m-Y H:i:s');
        $formattedDate['updated_at'] = $user->updated_at->format('d-m-Y H:i:s');
        return response()->json([
            'user' => $formattedDate,
            'token' => $user->createToken('laraval_api_token')->plainTextToken
        ]);
    }

    public function checkAlowedDay($user, $input){
        if($user && !empty($input)){
            $allowedDays = [];
            $groups = $user->courses;

            foreach($groups as $group){
                $room = $group->rooms()->where('name', $input)->first();
                if($room)
                $allowedDays = json_decode($room->allowed_days, true);
                $currentDay = Carbon::now()->dayOfWeek; 
                if (in_array($currentDay, $allowedDays)) {
                    return true;
                }
            }
        }
    }
}
