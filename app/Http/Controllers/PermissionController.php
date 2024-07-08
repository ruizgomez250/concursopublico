<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{


    public function __construct()
    {
        $this->middleware('permission:Listar Permisos',['only'=>['index','show']]);
        $this->middleware('permission:Guardar Permisos',['only'=>['store','create']]);
        $this->middleware('permission:Actualizar Permisos',['only'=>['update','edit']]);
        $this->middleware('permission:Eliminar Permisos',['only'=>['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::get();
        return view('role-permission.permission.index',['permissions'=>$permissions]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('role-permission.permission.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['name' => ['required', 'string', 'unique:permissions,name']]);
       // $role = Role::create(['name' => 'writer']);
        Permission::create(['name' => $request->name]);
        return redirect('permissions')->with('status','Permiso creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        return view('role-permission.permission.edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate(['name' => ['required', 'string', 'unique:permissions,name,'.$permission->id]]);
        // $role = Role::create(['name' => 'writer']);
        $permission->update(['name' => $request->name]);
         return redirect('permissions')->with('status','Permiso actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
       $permission->delete();
       return redirect('permissions')->with('status','Permiso eliminar exitosamente');
    }
}
