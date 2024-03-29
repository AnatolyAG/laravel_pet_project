<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\http\Response;
use Illuminate\Support\Facades\Cache;
use Exception;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Cache::has('roles')) {
            // Если данные найдены в кеше, возвращаем их
            return response()->json(Cache::get('roles'));
        }

        $this->authorize('view', Role::class);

        $all_roles = Role::all();

        Cache::put('roles', $all_roles, now()->addMinutes(10));

        return response()->json($all_roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Role::class);
        // add validate
        $validatedData = $request->validate([
            'name'        => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            // create used validated data
            $role = Role::create($validatedData);
            if (!$role) {
                return response()->json(['error' => 'Role not created'], 422);
            }
            Cache::forget('roles');  // clear cache
            return response()->json($role, 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('view', Role::class);
        if (!is_numeric($id) || $id <= 0 || floor($id) != $id) {
            return response()->json(['error' => 'Invalid role ID'], 422);
        }
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        return response()->json($role);
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
        $this->authorize('update', Role::class);

        if (!is_numeric($id) || $id <= 0 || floor($id) != $id) {
            return response()->json(['error' => 'Invalid role ID'], 422);
        }

        try {
            $role = Role::find($id);
            if (!$role) {
                return response()->json(['error' => 'Role not found'], 404);
            }

            // add validate
            $validatedData = $request->validate([
                'name'        => 'sometimes|string|max:255|unique:roles',
                'descr' => 'sometimes|nullable|string|max:255',
            ]);
            $role->update($validatedData);
            Cache::forget('roles');  // clear cache
            return response()->json($role);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', Role::class);
        //add validate role id
        if (!is_numeric($id) || $id <= 0 || floor($id) != $id) {
            return response()->json(['error' => 'Invalid role ID'], 422);
        }

        try {
            $role = Role::find($id);
            if (!$role) {
                return response()->json(['error' => 'Role not found'], 404);
            }
            $role->delete();
            Cache::forget('roles');  // clear cache
            return response()->json(null, 204);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
