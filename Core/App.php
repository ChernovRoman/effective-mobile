<?php

class WorkingWithFile
{
    public static function readFile(string $path = '../data/phone.json'): TelephoneList
    {
        if (!file_exists($path)) {
            self::writeFile(TelephoneList::getInstance(), $path);
        }

        $stdClass = self::validateFile($path);

        foreach ($stdClass->data as $key => $value) {
            $phone = (int)$value->phone;
            TelephoneList::getInstance()->data[] = new PhoneNumber($value->id, $value->name, $phone);
        }

        return TelephoneList::getInstance();
    }

    public static function validateFile(string $path = '../data/phone.json'): stdClass
    {

        $json = file_get_contents($path);

        $stdClass = json_decode($json, null);

        if (!isset($stdClass->data)) {
            self::writeFile(TelephoneList::getInstance(), $path);
            $json = file_get_contents($path);

            return $stdClass = json_decode($json, null);
        }

        return $stdClass;
    }

    public static function writeFile(TelephoneList $list, string $path = '../data/phone.json'): void
    {
        $list = json_encode($list, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        file_put_contents($path, $list, LOCK_EX);
    }

    public static function validatePhone(string $phone, TelephoneList $list): int
    {
        if (preg_match("/[7]{1}[0-9]{10}/", $phone)) {
            $phone = array_search($phone, array_column($list->data, 'phone')) === false ? $phone :
                  throw new DomainException('Номер телефона уже присутствует в справочнике');
        } else {
            throw new DomainException('Нужен номер телефона в формате 7XXXXXXXXXX');
        }

        return $phone;
    }

    public static function generateId(TelephoneList $list): int
    {
        $key = array_column($list->data, 'id');

        array_multisort($key, SORT_ASC, $list->data);

        $end = end($list->data);

        $id = $end === false ? 1 : $end->id + 1;

        return  $id;
    }
}


class TelephoneList
{
    private static $instance;
    public array $data = [];


    public static function getInstance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function countList(): int
    {
        return count($this->data);
    }

    public function deleteEntry(int $id): void
    {
        $id = array_search($id, array_column($this->data, 'id')) === false ?
        throw new DomainException('Ошибка при удалении. Значение id не верно') : array_search($id, array_column($this->data, 'id'));
        unset($this->data[$id]);
    }

    public function addEntry(PhoneNumber $entry): void
    {
        $this->data[] = $entry;
    }
}


class PhoneNumber
{
    public readonly int $id;
    public readonly string $name;
    public readonly int $phone;

    public function __construct(int $id, string $name, int $phone)
    {
        $this->id = $id;
        $this->name = $name;
        $this->phone = $phone;
    }
}
