<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
DB::enableQueryLog(); // string output of query builder

class UserController extends Controller
{
    public function register(Request $request)
    {
        $full_name = $request->full_name;
        $email = $request->email;
        $password = Hash::make($request->password, [
            'memory' => 1024,
            'time' => 2,
            'threads' => 2
        ]);
        $role = $request->role;
        $token = Str::random(256);

        date_default_timezone_set("Asia/manila");
        $check_name = DB::table('users')->where('name', $full_name)->first();
        
        if ($check_name <= 0) {
            DB::beginTransaction();
            $insert = User::insert([
                'name' => $full_name,
                'email' => $email,
                'password' => $password,
                'api_token' => hash('sha256', $token),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'role_id' => $role
            ]);

            if ($insert == TRUE) {
                $respo = array(
                    'statusCode' => 200,
                    'message' => 'Successfully registered!'
                );
                DB::commit();
            } else {
                $respo = array(
                    'statusCode' => 500,
                    'message' => 'Error occur, try again later.'
                );
                DB::rollBack();
            }   
        }

        return response($respo);
    }

    public function getUser(Request $request)
    {
        $email = $request->segment(4);

        $user = DB::table('users')->where('email', $email)->first();

        if ($user) {
            $respo['success'] = true;
            $respo['data'] = $user;
        } else {
            $respo['success'] = false;
            $respo['message'] = 'User not found';
        }

        return response($respo);
    }

    public function getUsers() {
        $user = DB::table('users')->get();

        return response($user);
    }

    public function updateUser(Request $request)
    {
        $id = $request->segment(4);
        $full_name = $request->full_name;
        $email = $request->email;
        $password = Hash::make($request->password, [
            'memory' => 1024,
            'time' => 2,
            'threads' => 2
        ]);
        $role = $request->role;

        $user = DB::table('users')->where('id', '!=', $id)->where('email', $email)->Where('name', $full_name)->first();

        if ($user) {
            $respo['success'] = false;
            $respo['message'] = 'Email or Name already exists!';
        } else {
            DB::beginTransaction();
            $update = DB::table('users')->where('id', $id)->update([
                'name' => $full_name,
                'email' => $email,
                'password' => $password,
                'role_id' => $role
            ]);

            if ($update == TRUE) {
                $respo = array(
                    'statusCode' => 200,
                    'message' => 'Successfully Updated!'
                );
                DB::commit();
            } else {
                $respo = array(
                    'statusCode' => 500,
                    'message' => 'Error occur, try again later.'
                );
                DB::rollBack();
            }
        }

        return response($respo);
    }

    public function deleteUser(Request $request)
    {
        $id = $request->segment(4);

        $user = DB::table('users')->where('id', $id)->first();

        if ($user) {
            DB::beginTransaction();
            $deleted_user = DB::table('users')->where('id', $id)->delete();

            if ($deleted_user == TRUE) {
                $respo = array(
                    'statusCode' => 200,
                    'message' => 'Successfully Deleted!'
                );
                DB::commit();
            } else {
                $respo = array(
                    'statusCode' => 500,
                    'message' => 'Error occur, try again later.'
                );
                DB::rollBack();
            }
        } else {
            $respo['success'] = false;
            $respo['message'] = 'User not found!';
        }

        return response($respo);
    }
}
