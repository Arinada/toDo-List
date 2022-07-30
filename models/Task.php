<?php

namespace App\Models;

use mysqli_result;

class Task
{
    private string $selectQuery = "SELECT * FROM Task INNER JOIN Author ON Author.id = Task.author_id";
    private string $countQuery = "SELECT count(*) as task_number FROM Task INNER JOIN Author ON Author.id = Task.author_id";

    public function getNumberOfAllTasks(): int
    {
        $stmt = DBClass::getConnection()->prepare($this->countQuery);
        $stmt->execute();
        $result = $stmt->get_result();
        return $this->getDataByResult($result)[0]['task_number'];
    }

    public function getNumberOfTasksByDescription(string $value): int
    {
        $query =  $this->countQuery . " WHERE description LIKE ?";
        $param = "%$value%";
        $result = $this-> getQueryWithParamResult($query, "s", $param);
        return $this->getDataByResult($result)[0]['task_number'];
    }

    public function  getNumberOfTasksByStatus(int $status): int
    {
        $query =  $this->countQuery . " WHERE status=?";
        $result = $this-> getQueryWithParamResult($query, "i", $status);
        return  $this->getDataByResult($result)[0]['task_number'];
    }

    public function  getNumberOfTasksByAuthorName(string $value): int
    {
        $query =  $this->countQuery .  " WHERE Author.name LIKE ?";
        $param = "%$value%";
        $result = $this-> getQueryWithParamResult($query, "s", $param);
        return  $this->getDataByResult($result)[0]['task_number'];
    }

    public function  getNumberOfTasksByEmail(string $value): int
    {
        $query =  $this->countQuery . " WHERE Author.email LIKE ?";
        $param = "%$value%";
        $result = $this-> getQueryWithParamResult($query, "s", $param);
        return  $this->getDataByResult($result)[0]['task_number'];
    }

    public function getAllTasks(int $limitTaskNumberFrom, int $limitTaskNumberTo): array
    {
        $query = "SELECT * FROM Task INNER JOIN Author ON Author.id = Task.author_id";
        $stmt = DBClass::getConnection()->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $this->getDataByResult($result);
    }

    public function getTasksByDescription(string $value): array
    {
        $query =  "SELECT * FROM Task INNER JOIN Author ON Author.id = Task.author_id WHERE description LIKE ?";
        $param = "%$value%";
        $result = $this-> getQueryWithParamResult($query, "s", $param);
        return  $this->getDataByResult($result);
    }

    public function getTasksByStatus(string $value): array
    {
        $query =  "SELECT * FROM Task INNER JOIN Author ON Author.id = Task.author_id WHERE status=?";
        $result = $this-> getQueryWithParamResult($query, "i", $value);
        return  $this->getDataByResult($result);
    }

    public function getTasksByAuthorName(string $value): array
    {
        $query =  "SELECT * FROM Task INNER JOIN Author ON Author.id = Task.author_id WHERE Author.name LIKE ?";
        $param = "%$value%";
        $result = $this-> getQueryWithParamResult($query, "s", $param);
        return  $this->getDataByResult($result);
    }

    public function getTasksByEmail(string $value): array
    {
        $query =  "SELECT * FROM Task INNER JOIN Author ON Author.id = Task.author_id WHERE Author.email LIKE ?";
        $param = "%$value%";
        $result = $this-> getQueryWithParamResult($query, "s", $param);
        return  $this->getDataByResult($result);
    }


    private function getDataByResult(mysqli_result $result): array
    {
        $counter = 0;
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[$counter] = $row;
            $counter++;
        }
        return $data;
    }

    private function getQueryWithParamResult(string $query,string $paramType,string $value): mysqli_result
    {
        $stmt = DBClass::getConnection()->prepare($query);
        $stmt->bind_param($paramType, $value);
        $stmt->execute();
        return $stmt->get_result();
    }
}