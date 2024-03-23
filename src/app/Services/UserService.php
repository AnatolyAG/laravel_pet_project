<?php

namespace App\Services;

use App\Events\UserCreated;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Cache;
use illuminate\Support\Facades\Event;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(array $userData)
    {
        try {
            $user = $this->userRepository->create($userData);

            event(new UserCreated($user)); // Отправляем событие о создании пользователя
            $this->clearUserCache();
            return $user;
        } catch (Exception $e) {
            event(new UserCreated(null, $e));
        }
        return null;
    }

    public function getAllUsers()
    {
        $users_arr = $this->getUserCache();
        if ($users_arr) {
            return $users_arr;
        }
        $users_arr = $this->userRepository->all();
        $this->putUserCache($users_arr);
        return $users_arr;
    }
    /**
     * @param int $user_id
     */
    public function getUserById(int $user_id)
    {
        $userById = $this->userRepository->findById($user_id);
        return $userById;
    }




    private function clearUserCache()
    {
        Cache::forget('users');
    }
    private function getUserCache()
    {
        if (Cache::has('users')) {
            return Cache::get('users');
        }
    }
    /**
     * @param \Illuminate\Database\Eloquent\Collection $users
     * @param int $duration = 10
     */
    private function putUserCache(Collection $users, int $duration = 10)
    {
        Cache::put('users', $users, now()->addMinutes($duration));
    }
}
