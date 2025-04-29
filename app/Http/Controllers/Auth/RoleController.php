<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    public function list(Request $request)
    {
        $data = Role::all()->map(function ($value) {

            return [
                'id' => $value->id,
                'name' => $value->name,
            ];
        });

        return [
            'code' => 200,
            'data' => $data,
        ];
    }
    public function dataForm(Request $request)
    {
        try {

            $rol = null;

            if ($request->input('id')) {
                $rol = Role::with(['permissions'])->find($request->input('id'));

                $permissions = $rol->permissions;
            }
            //revisar que el permiso del menú esté entre los permisos del rol de el usuario
            $menu = Menu::with(['children' => function ($query) use ($permissions) {
                $query->whereHas('permissions', function ($x) use ($permissions) {
                    $x->whereIn('name', $permissions->pluck('name'));
                });
            }, 'children.children'])->whereIn('requiredPermission', $permissions->pluck('name'))->whereNull('father')->get();

            return response()->json([
                'code' => '200',
                'message' => 'Bien',
                'rol' =>  $rol,
                'menu' =>  $menu,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'line' => $th->getLine(),
                'code' => '400',
            ], 400);
        }
    }
}
