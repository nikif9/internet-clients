<?php
namespace App\Service;

/**
 * Класс для валидации данных.
 */
class Validator {
    /**
     * @var array Данные для валидации.
     */
    protected $data;

    /**
     * @var array Ошибки валидации.
     */
    protected $errors = [];
    
    /**
     * Конструктор.
     *
     * @param array $data Данные для валидации.
     */
    public function __construct($data) {
        $this->data = $data;
    }
    
    /**
     * Проверяет, что поле обязательно для заполнения.
     *
     * @param string $field Имя поля.
     * @param string|null $message Сообщение об ошибке.
     * @return $this
     */
    public function required($field, $message = null) {
        if (!isset($this->data[$field]) || trim($this->data[$field]) === '') {
            $this->errors[$field] = $message ?: "$field обязательно для заполнения";
        }
        return $this;
    }
    
    /**
     * Проверяет максимальную длину поля.
     *
     * @param string $field Имя поля.
     * @param int $length Максимальная длина.
     * @param string|null $message Сообщение об ошибке.
     * @return $this
     */
    public function maxLength($field, $length, $message = null) {
        if (isset($this->data[$field]) && mb_strlen($this->data[$field]) > $length) {
            $this->errors[$field] = $message ?: "$field не должно превышать $length символов";
        }
        return $this;
    }
    
    /**
     * Возвращает список ошибок.
     *
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * Проверяет, прошла ли валидация без ошибок.
     *
     * @return bool
     */
    public function isValid() {
        return empty($this->errors);
    }
}
