<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Course;
use App\Models\Room;
use App\Models\Logged;
use Spatie\Permission\Models\Role;

class UserNewController extends Controller
{

    public function dashboard(){
        $user = Auth::user();
        $total_groups = Course::count();
        $total_users = User::count();
        $total_rooms = Room::count();
        $loggedInUsers = Logged::count();
        // $loggedInUsers = User::whereHas('tokens', function ($query) {
        //     $query->where('expires_at', '>=', now())->orWhereNull('expires_at');
        // })->get();
        return view('backend.pages.dashboard.index',compact('total_groups','total_rooms','total_users','loggedInUsers'));
    }
    public function index()
    {
        // Phân trang với mỗi trang có 10 người dùng
        $users = User::paginate(20);

        return view('backend.pages.users.index', compact('users'));
    }
    // Hiển thị form cập nhật
    public function edit($id)
    {
        //$this->checkAuthorization(auth()->user(), ['admin.view']);
        $user = User::find($id);
        $groups = Course::select('id','name')->get();
        $roles = Role::all();
        //$allowed_days = json_decode($user->allowed_days, true); // Lấy danh sách các ngày mà user được phép đăng nhập
        return view('backend.pages.users.edit', compact('user','groups','roles'));
    }

    // Xử lý việc cập nhật thông tin
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        // Validate dữ liệu
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|min:4|max:25|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'type' => 'required',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->roles && $request->type != 'admin') {
            return back()->with('error', 'You cannot assign roles to a non-admin user.');
        }

        // Cập nhật thông tin người dùng
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->type = $request->type;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->status = $request->status;
        $user->save();

        $user->courses()->sync($request->groups);

        $user->roles()->detach();
        if ($request->roles && $request->type == 'admin') {
            $user->assignRole($request->roles);
        }

        // Redirect với thông báo thành công
        return redirect()->route('users.index')->with('success', 'Profile updated successfully!');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('users.index')
                        ->with('success','Profile deleted successfully');
    }
}

