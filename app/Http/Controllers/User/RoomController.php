<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Room;
use App\Models\Course;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $course = Course::find($id);
        $rooms = $course->rooms()->get();
        
        return view('backend.pages.rooms.index', compact(['rooms','course']));
    }

    public function gateway()
    {
        $rooms = Room::paginate(20);
        return view('backend.pages.rooms.gateway', compact(['rooms']));
    }

    public function create(){
        $courses = Course::all();
        return view('backend.pages.rooms.create', compact('courses'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:rooms',
        ]);
        //$allowedDays = json_encode($request->allowed_days);
        $room = new Room(
            [
                'name' => $request->name,
            ]
        );

        if(!empty($request->groups) && count($request->groups) > 1){
            $room->courses()->attach($request->groups);
            $conflictCheck = $room->courses()->first()->validateScheduleConflict(null,null,$request->groups);
            
            if ($conflictCheck['hasConflict']) {
                $room->courses()->sync([]);
                return redirect()->back()
                    ->with('error', 'Room schedule conflict. Please try again.')
                    ->withInput();
            }
        }
        $room->save();

        if($room && !empty($request->groups)){
            $room->courses()->attach($request->groups);
        }

        return redirect()->route('gateway')->with('success', 'Room create successful.');
    }

    /**
     * Display the specified resource.
     */
    public function edit($id)
    {
        $room = Room::findOrFail($id);
        //$allowed_days = [];
        //$allowed_days = json_decode($room->allowed_days, true);
        $groups = Course::select('id','name')->get();
        return view('backend.pages.rooms.edit', compact(['room','groups']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        // Validate dữ liệu
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:25|unique:rooms,name,' . $room->id,
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        if(!empty($request->groups) && count($request->groups) > 1){
            $conflictCheck = $room->courses()->first()->validateScheduleConflict(null,null,$request->groups);
            
            if ($conflictCheck['hasConflict']) {
                return redirect()->back()
                    ->with('error', 'Room schedule conflict. Please try again.')
                    ->withInput();
            }
        }

        // Cập nhật thông tin người dùng
        $room->update([
            'name' => $request->name,
        ]);

        $room->courses()->sync($request->groups);

        // Redirect với thông báo thành công
        return redirect()->route('gateway')->with('success', 'Room updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $room = Room::find($id);
        $room->delete();
        return redirect()->route('gateway')
                        ->with('success','Room deleted successfully');
    }
}
