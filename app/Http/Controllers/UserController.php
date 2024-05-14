<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::get();

            return Datatables::of($users)
                ->addIndexColumn()

            //     ->addColumn('action', function ($row) {

            //         $edit_url = route('product.edit', $row->id);
            //         $view_url = route('product.view', $row->id);
            //         $btn = '<a href="' . $edit_url . '" class="btn btn-warning"><i class="fa fa-edit"></i></a>
            // <a href="' . $view_url . '" class="btn btn-warning mt-2"><i class="fa fa-eye"></i></a>';
            //         return $btn;
            //     })
                ->rawColumns([ 'image'])
                ->make(true);
        }


        return view('user.index');
    }
}
