<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
        return view('backend.pages.courses.create', compact('users'));
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
        //$allowed_days = json_decode($user->allowed_days, true); // Lấy danh sách các ngày mà user được phép đăng nhập
        return view('backend.pages.courses.edit', compact('group','users'));
    }

    public function register(Request $request)
    {
        // Validate form
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $group = Course::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);
        
        if($group && !empty($request->users)){
            $group->users()->attach($request->users);
        }

    return redirect()->route('group.index')->with('success', 'Group Create successful.');
        
    }

    public function update(Request $request, $id)
    {
        $group = Course::find($id);

        // Validate dữ liệu
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Cập nhật thông tin người dùng
        $group->name = $request->name;
        $group->status = $request->status;
        $group->save();

        $group->users()->sync($request->users);

        // Redirect với thông báo thành công
        return redirect()->route('group.index')->with('success', 'Group updated successfully!');
    }

    public function destroy($id)
    {
        $user = Course::find($id);
        $user->delete();
        return redirect()->route('group.index')
                        ->with('success','Group deleted successfully');
    }
}
