<?php

namespace App\Http\Controllers;

use App\Events\UserCreated;
use App\Http\Requests\CreateUserRequest;
// use App\Http\Requests\ShowUserRequest;

use App\Http\Requests\ShowUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Validator;
use Exception;

use App\Repositories\UserRepository;
use App\Services\UserService;

class UserController extends Controller
{

    protected UserRepository $userRepository;
    protected UserService $userService;

    public function __construct(UserRepository $userRepository, UserService $userService)
    {
        $this->userRepository = $userRepository;
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $this->authorize('view', User::class);
        $users_arr = $this->userService->getAllUsers();

        return UserResource::collection($users_arr);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateUserRequest  $request
     * @return \App\Http\Resources\UserResource
     */
    public function store(CreateUserRequest $request)
    {
        $this->authorize('create', User::class);
        $user = $this->userService->createUser($request->validated());

        return response()->json(UserResource::make($user), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $userId
     * @return \App\Http\Resources\UserResource
     */
    public function show(int $userId)
    {
        $this->authorize('view', User::class);
        $user = $this->userService->getUserById($userId);

        return UserResource::make($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateUserRequest  $request
     * @param  int  $id
     * @return \App\Http\Resources\UserResource
     */
    public function update(UpdateUserRequest $request, int $id)
    {
        $this->authorize('update', User::class);
        $validated = $request->validated();
        $updated_user = $this->userService->updateUser($validated, $id);

        return UserResource::make($updated_user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(int $id)
    {
        $this->authorize('delete', User::class);

        $user_deleted_status = $this->userService->deleteUser($id);
        if ($user_deleted_status === true) {
            return response()->json(null, 204);
        } else {
            return response()->json(['error' => 'Failed to delete user'], 500);
        }
    }

}
