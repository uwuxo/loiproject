<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Course;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CourseRequest;

class CourseController extends Controller
{
    public function index()
    {
        // Phân trang với mỗi trang có 10 người dùng
        $groups = Course::paginate(20);

        return view('backend.pages.courses.index', compact('groups'));
    }
    public function create(){
        $users = User::select('id','name')->get();
        $rooms = Room::select('id','name')->get();
        return view('backend.pages.courses.create', compact('users','rooms'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $group = Course::find($id);
        $users = User::select('id','name')->get();
        $rooms = Room::select('id','name')->get();
        //$allowed_days = json_decode($user->allowed_days, true); // Lấy danh sách các ngày mà user được phép đăng nhập
        return view('backend.pages.courses.edit', compact('group','users','rooms'));
    }

    public function register(CourseRequest $request)
    {
        $course = new Course([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'schedule' => $request->schedule,
        ]);

        $conflictCheck = $course->validateScheduleConflict($id=null, $request->rooms ?? null);
        if ($conflictCheck['hasConflict']) {
            return back()
                ->withErrors('error', sprintf(
                    'Course schedule conflict with course "%s" (from %s to %s)',
                    $conflictCheck['conflictWith']['name'],
                    Carbon::parse($conflictCheck['conflictWith']['start_date'])->format('d/m/Y'),
                    Carbon::parse($conflictCheck['conflictWith']['end_date'])->format('d/m/Y')
                ))
                ->withInput();
        }

        $course->save();
        
        if($course && !empty($request->users)){
            $course->users()->attach($request->users);
        }
        if($course && !empty($request->rooms)){
            $course->rooms()->attach($request->rooms);
        }

    return redirect()->route('group.index')->with('success', 'Course create successful.');
        
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'schedule' => 'required|array'
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
    
            // Tiếp tục xử lý logic sau khi validate thành công
            // ...
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Bắt lỗi ValidationException và xử lý
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Bắt lỗi chung và xử lý
            return response()->json([
                'message' => 'An error occurred',
            ], 500);
        }
        // Validate schedule conflict
        $updatedCourse = new Course([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'schedule' => $request->schedule
        ]);
        
        $conflictCheck = $updatedCourse->validateScheduleConflict($id,$request->rooms ?? null);
        if ($conflictCheck['hasConflict']) {
            return back()
                ->withErrors('error', sprintf(
                    'Course schedule conflict with course "%s" (from %s to %s)',
                    $conflictCheck['conflictWith']['name'],
                    Carbon::parse($conflictCheck['conflictWith']['start_date'])->format('d/m/Y'),
                    Carbon::parse($conflictCheck['conflictWith']['end_date'])->format('d/m/Y')
                ))
                ->withInput();
        }
        $course->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'schedule' => $request->schedule
        ]);

        $course->users()->sync($request->users);
        $course->rooms()->sync($request->rooms);

        // Redirect với thông báo thành công
        return redirect()->route('group.index')->with('success', 'Course updated successfully!');
    }

    public function destroy($id)
    {
        $user = Course::find($id);
        $user->delete();
        return redirect()->route('group.index')
                        ->with('success','Course deleted successfully');
    }
}
