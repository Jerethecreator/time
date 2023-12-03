<?php

namespace App\Http\Controllers;

use App\Models\reg_employee_mst;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use RealRashid\SweetAlert\Facades\Alert;
use DataTables;

class RolesUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
    
     */
    public function create()
    {
        return view('RolesUsers.create',[
            'users' => reg_employee_mst::get(),
            'roles' => Role::get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $user = reg_employee_mst::find($request->user);
        $roles = explode(",",$request->input('dataField')); 
        foreach ($roles as $role) {
            $getRoles = Role::findByName($role);
            $user->assignRole($getRoles);
           
        }

        toast('User Assigned Role(s) Successfully!','success');
        return redirect()->back();   
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function remove()
    {
        return view('RevokeRoles.create',[
            'users' => reg_employee_mst::get(),
            'roles' => Role::get()
        ]);
    }






    public function revoke(Request $request){
       
        $user = reg_employee_mst::find($request->user);
        $roles = explode(",",$request->input('dataField')); 
        foreach ($roles as $role) {
            $getRoles = Role::findByName($role);

          
if ($user->hasRole($role)) {
    $user->removeRole($getRoles);
}

else{
    toast('This User was not assigned roles to revoke!','error');
    return redirect()->back();    
}
            
           
        }

        toast('User Role(s) Removed Successfully!','success');
        return redirect()->back();  
    }



    // get all permissions for the user, either directly, or from roles, or from both
    public function show_all_permissions_from_a_user(){
        return view('AllPermissions.index');  
    }




    public function get_all_permissions_from_a_user(){
       
        $data = reg_employee_mst::with('roles.permissions')->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('user', function ($data) {
                return $data->firstname . ' ' . $data->lastname;
            })
            ->addColumn('email', function ($data) {
                return $data->email;
            })
            ->addColumn('permissions', function ($data) {
                $permissions = $data->roles->flatMap(function ($role) {
                    return $role->permissions;
                })->pluck('name')->implode(', ');
    
                return $permissions;
            })
            ->rawColumns(['user', 'email', 'permissions'])
            ->make(true);
    }
}
