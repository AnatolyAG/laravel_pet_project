<?php

namespace App\Http\Controllers;

use App\Events\UserCreated;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Exception;

// use App\Repositories\UserRepository;
// use App\Services\UserService;

class UserController extends Controller
{

    // protected UserRepository $userRepository;
    // protected UserService $userService;

    public function __construct()
    {
        // $this->userRepository = $userRepository;
        // $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view', User::class);

        if (Cache::has('users')) {
            return response()->json(Cache::get('users'));
        }

        $all_users = User::all();

        Cache::put('users', $all_users, now()->addMinutes(10));

        return response()->json($all_users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);
        // add validate
        $validatedData= $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        try {
            //create user use validated data
            $user = User::create($validatedData);

            Cache::forget('users');  // clear cache

            event(new UserCreated($user));

            return response()->json($user, 201);
        } catch (Exception $e) {
            event(new UserCreated(null, $e));
            return response()->json(['error' => $e->getMessage()], 422);
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
        $this->authorize('view', User::class);
        //add validate input data
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|min:1',
        ]);

        // If validate - fail then return error
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }


        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json($user);
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
        $this->authorize('update', User::class);
        // add validate $id another method for validate
        if (!is_numeric($id) || $id <= 0 || floor($id) != $id) {
            return response()->json(['error' => 'Invalid user ID'], 422);
        }

        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            // add validator for user data
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:users,email,'.$user->id,
                'password' => 'sometimes|string|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $user->update($request->all());

            Cache::forget('users');  // clear cache

            return response()->json($user);
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
        $this->authorize('delete', User::class);
        // add validate for user id $is
        if (!is_numeric($id) || $id <= 0 || floor($id) != $id) {
            return response()->json(['error' => 'Invalid user ID'], 422);
        }
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $user->delete();

            Cache::forget('users');  // clear cache

            return response()->json(null, 204);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
