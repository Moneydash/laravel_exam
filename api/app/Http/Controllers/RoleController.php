<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Roles;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function view()
    {
        $roles = DB::table('roles')->get();

        return response($roles);
    }
    public function create(Request $req)
    {
        $rolename = $req->role_name;

        $role = DB::table('roles')->where('role_name', $rolename)->first();

        if ($role) {
            $respo['success'] = false;
            $respo['message'] = 'Role Name already exists!';
        
        } else {
            DB::beginTransaction();
            $create = Roles::insert([
                'role_name' => $rolename,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            if ($create === TRUE) {
                $respo['statusCode'] = 200;
                $respo['message'] = 'Successfully Added!';
                DB::commit();
            } else {
                $respo['statusCode'] = 500;
                $respo['message'] = 'Error occur, try again later.';
                DB::rollBack();
            }
        }

        return response($respo);
    }

    public function update(Request $req)
    {
        $id = $req->segment(4);
        $rolename = $req->role_name;
        
        $role = Roles::where('role_name', $rolename)->where('id', '!=', $id)->first();

        if ($role) {
            $respo['success'] = false;
            $respo['message'] = 'Role Name already exists!';
        } else {
            DB::beginTransaction();
            $update = Roles::where('id', $id)->update([
                'role_name' => $rolename,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            if ($update == TRUE) {
                $respo['statusCode'] = 200;
                $respo['message'] = 'Successfully Updated!';
                DB::commit();
            } else {
                $respo['statusCode'] = 500;
                $respo['message'] = 'Error occur, try again later.';
                DB::rollBack();
            }
        }

        return response($respo);
    }

    public function deleteRole(Request $req)
    {
        $id = $req->segment(4);

        $role = Roles::where('id', $id)->first();

        if ($role) {
            DB::beginTransaction();
            $deleted_role = Roles::where('id', $id)->delete();

            if ($deleted_role == TRUE) {
                $respo['statusCode'] = 200;
                $respo['message'] = 'Successfully Removed!';
                DB::commit();
            } else {
                $respo['statusCode'] = 500;
                $respo['message'] = 'Error occur, try again later.';
                DB::rollBack();
            }
        }

        return response($respo);
    }
}
