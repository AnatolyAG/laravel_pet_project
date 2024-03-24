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
     * @param int $userId
     */
    public function getUserById(int $userId)
    {
        $userById = $this->userRepository->findById($userId);
        return $userById;
    }

    public function updateUser($data,int $userId)
    {

        $userById = $this->userRepository->findById($userId);
        try {
            $new_data_user = $this->userRepository->update($userById,$data);
            $this->clearUserCache();
            return $new_data_user;
        } catch (Exception $e) {
            // event(new UserUpdated(null, $e)); // if you have this event
        }
        return null;
    }
    public function deleteUser(int $userId):?bool
    {

        $userById = $this->userRepository->findById($userId);
        try {
            $res_bool = $this->userRepository->delete($userById);
            $this->clearUserCache();
            return $res_bool;
        } catch (Exception $e) {
            // event(new UserDeleted(null, $e)); // if you have this event
        }
        return null;
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
