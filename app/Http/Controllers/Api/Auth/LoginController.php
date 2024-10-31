<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Models\Logged;

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

        $token = $user->createToken('laraval_api_token')->plainTextToken;
        return response()->json([
            'user' => $formattedDate,
            'token' => $token
        ]);
    }

    public function checkAlowedDay($user, $input){
        if($user && !empty($input)){
            $allowedDays = [];
            $groups = $user->courses()->where('status', 1)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->get();
            foreach($groups as $group){
                $room = $group->rooms()->where('name', $input)->whereTime('start_time', '<=', now())
                ->whereTime('end_time', '>=', now())
                ->first();
                if($room){
                    $allowedDays = json_decode($room->allowed_days, true);
                    $currentDay = Carbon::now()->dayOfWeek; 
                    if (in_array($currentDay, $allowedDays)) {
                        $room->logged()->create([
                            'user_id' => $user->id,
                            'room_id' => $room->id,
                            'created_at' => Carbon::now()
                        ]);
                        return true;
                    }
                }
                
            }
        }
    }
}
