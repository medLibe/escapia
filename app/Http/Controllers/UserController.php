<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        try{
            $countUser = User::where('is_active', 1)->count();

            return response()->json([
                'success'   => true,
                'message'   => 'Count user data',
                'user'      => $countUser,
            ]);
        }catch(Exception $err){
            return response()->json($err->getMessage(), 500);
        }
    }

    public function all(Request $request)
    {
        try{
            $data = User::where('is_active', 1)->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    // ->addColumn('action', function($row) {
                    //     $actionBtn = '<div><a class="btn btn-sm btn-warning mb-1" data-bs-toggle="modal" data-bs-target="#editModal' . $row->id .'"><i class="fa-solid fa-edit"></i></a> <a class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal" data-bs-target="#deleteModal' . $row->id .'"><i class="fa-solid fa-trash"></i></a></div>';
                    //     return $actionBtn;
                    // })
                    // ->rawColumns(['action'])
                    ->make(true);

        }catch(Exception $err){
            return response()->json($err->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try{
            $validated = Validator::make($request->all(), [
                'username'  => 'required|unique:users',
                'password' => 'required|confirmed|min:6',
            ],
            [
                'username.required' => 'Username code must be filled.',
                'username.unique'   => 'Username was registered.',
                'password.required' => 'Password must be filled.',
                'password.min'      => 'Password length min is 6 characters.',
                'password.confirmed'=> 'Password confirmation do not match.',
            ]);

            if($validated->fails()){
                return response()->json([
                    'success'   => false,
                    'message'   => 'Something went wrong with your input.',
                    'errors'    => $validated->errors(),
                ], 400);
            }

            User::create([
                'username'      => $request->username,
                'password'      => Hash::make($request->password),
                'created_by'    => $request->creator,
                'updated_by'    => $request->creator,
            ]);

            return response()->json([
                'success'   => true,
                'message'   => 'New user successfully added.',
            ]);
        }catch(Exception $err){
            return response()->json($err->getMessage(), 500);
        }
    }
}
