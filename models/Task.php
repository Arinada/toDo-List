<?php

namespace App\Models;

use mysqli_result;

class Task
{

    public function getAllTasks(): array
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