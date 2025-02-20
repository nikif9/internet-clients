<?php
namespace App\Model;

/**
 * Модель пользователя.
 */
class User {
    /**
     * @var int Идентификатор пользователя.
     */
    public $id;

    /**
     * @var string Логин пользователя.
     */
    public $username;

    /**
     * @var string Хэш пароля.
     */
    public $password;
    
    /**
     * Ищет пользователя по логину.
     *
     * @param string $username
     * @param \PDO $pdo
     * @return User|null
     */
    public static function findByUsername($username, $pdo) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($data) {
            $user = new self();
            $user->id = $data['id'];
            $user->username = $data['username'];
            $user->password = $data['password'];
            return $user;
        }
        return null;
    }
    
    /**
     * Проверяет пароль пользователя.
     *
     * @param string $password
     * @return bool
     */
    public function verifyPassword($password) {
        return password_verify($password, $this->password);
    }
}
