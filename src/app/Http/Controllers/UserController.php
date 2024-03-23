<?php

namespace App\Http\Controllers;

use App\Events\UserCreated;
use App\Http\Requests\CreateUserRequest;
// use App\Http\Requests\ShowUserRequest;

use App\Http\Requests\ShowUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\http\Response;

use Illuminate\Support\Facades\Validator;
use Exception;

use App\Repositories\UserRepository;
use App\Services\UserService;

class UserController extends Controller
{

    protected UserRepository $userRepository;
    protected UserService $userService;

    public function __construct(UserRepository $userRepository,UserService $userService)
    {
        $this->userRepository = $userRepository;
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view', User::class);
        $users_arr = $this->userService->getAllUsers();
        return response()->json($users_arr);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        $this->authorize('create', User::class);
       
        try {
            $user = $this->userService->createUser($request->validated());
            return response()->json($user, 201);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $userId)
    {
        $this->authorize('view', User::class);
        $user = $this->userService->getUserById($userId);
      
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
