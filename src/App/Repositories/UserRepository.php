<?php
namespace ASPTest\App\Repositories;

use ASPTest\App\Repositories\Interfaces\UserRepositoryInterface;
use ASPTest\App\Models\User;
use ASPTest\App\Database\Database;

class UserRepository implements UserRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(User $user): User {
        $connection = (new Database())->getConnection();

        $parameters = [
            $user->name,
            $user->surname,
            $user->email,
            $user->age,
        ];

        try {
            $connection->prepare("INSERT INTO users(name, surname, email, age) VALUES(?, ?, ?, ?);")->execute($parameters);
        } catch (\PDOException $e) {
            throw $e;
        }

        $user->id = $connection->lastInsertId();
        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function find(int $id): User {
        $connection = (new Database())->getConnection();

        try {
            $query = $connection->prepare("SELECT * FROM users WHERE id = ?");
            $exec = $query->execute([$id]);
            $user = $query->fetchObject("ASPTest\App\Models\User");
        } catch (\PDOException $e) {
            throw $e;
        }

        if (!($user instanceof User)) {
            throw new \Exception('User not found');
        }

        return $user;
    }

    /**
     * @{inheritdoc}
     */
    public function updatePassword(User $user, string $password): void {
        $connection = (new Database())->getConnection();

        $user->password = password_hash($password, PASSWORD_BCRYPT, ['salt' => random_bytes(22), 'cost' => 10]);

        $params = [
            'id' => $user->id,
            'password' => $user->password
        ];

        try {
            $connection->prepare("UPDATE users SET password=:password WHERE id=:id")->execute($params);
        } catch (\PDOException $e) {
            throw $e;
        }
    }
}