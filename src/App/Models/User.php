<?php

namespace ASPTest\App\Models;

class User
{
    /**
     * @var int $id
     * User id.
     */
    protected int $id;

    /**
     * @var string $name
     * User name.
     */
    protected string $name;

    /**
     * @var string $surname
     * User surname.
     */
    protected string $surname;

    /**
     * @var string $email
     * User email.
     */
    protected string $email;

    /**
     * @var null|int $age
     * User age.
     */
    protected ?int $age;

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }
}
