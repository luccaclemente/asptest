<?php
namespace ASPTest\App\Repositories\Interfaces;

use ASPTest\App\Models\User;

interface UserRepositoryInterface
{
    /**
     * Create method to persist an User to the database.
     */
    public function create(User $user): User;

    /**
     * Find method to get a User data from database.
     *
     * @throws \Exception
     */
    public function find(int $id): User;

    /**
     * Update password method to change the informed user password.
     *
     * @throws \Exception
     */
    public function updatePassword(User $user, string $password): void;
}