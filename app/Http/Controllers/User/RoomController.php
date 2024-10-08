<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Room;
use App\Models\Course;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

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

    public function create($id){
        $course = Course::find($id);
        return view('backend.pages.rooms.create', compact('course'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:rooms',
        ]);
        $course = Course::find($id);
        $allowedDays = json_encode($request->allowed_days);
        $room = $course->rooms()->create(
            [
                'name' => $request->name,
                'allowed_days' => $allowedDays
            ]
        );

        return redirect()->route('rooms', ['id' => $id])->with('success', 'Room Create successful.');
    }

    /**
     * Display the specified resource.
     */
    public function edit($id)
    {
        $room = Room::find($id);
        $allowed_days = [];
        $allowed_days = json_decode($room->allowed_days, true); // Lấy danh sách các ngày mà user được phép đăng nhập
        return view('backend.pages.rooms.edit', compact(['room','allowed_days']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $room = Room::find($id);

        // Validate dữ liệu
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:25|unique:rooms,name,' . $room->id,
            'allowed_days' => 'required|array',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Cập nhật thông tin người dùng
        $room->name = $request->name;
        $room->allowed_days = json_encode($request->allowed_days);
        $room->save();

        // Redirect với thông báo thành công
        return redirect()->route('rooms', $room->course->id)->with('success', 'Room updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $room = Room::find($id);
        $room->delete();
        return redirect()->route('rooms', $room->course->id)
                        ->with('success','Room deleted successfully');
    }
}
