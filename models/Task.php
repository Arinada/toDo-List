<?php

namespace App\Models;

use mysqli_result;

class Task
{
    private string $selectQuery = "SELECT Task.*, Author.name, Author.email FROM Task INNER JOIN Author ON Author.id = Task.author_id";
    private string $countQuery = "SELECT count(*) as task_number FROM Task INNER JOIN Author ON Author.id = Task.author_id";

    public function add(int $authorId, $description) {
        $query = "INSERT INTO Task(author_id, description, status) VALUES (?, ?, 0)";
        $stmt = DBClass::getConnection()->prepare($query);
        $stmt->bind_param("is", $authorId, $description);
        $stmt->execute();
    }

    public function update(int $id, string $description, int $status): void
    {
        $query = "UPDATE Task SET status = ?, description = ? WHERE id = ?";
        $stmt = DBClass::getConnection()->prepare($query);
        $stmt->bind_param("isi", $status, $description, $id);
        $stmt->execute();
    }

    public function delete(int $id): void
    {
        $query = "DELETE FROM Task WHERE id = ?";
        $stmt = DBClass::getConnection()->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

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
        $stmt = DBClass::getConnection()->prepare($this->selectQuery . " LIMIT ?,?");
        $stmt->bind_param("ii", $limitTaskNumberFrom,$limitTaskNumberTo);
        $stmt->execute();
        $result = $stmt->get_result();
        return $this->getDataByResult($result);
    }

    public function getTasksByDescription(string $value, int $limitTaskNumberFrom, int $limitTaskNumberTo): array
    {
        $query =  $this->selectQuery . " WHERE description LIKE ? LIMIT ?,?";
        $param = "%$value%";
        $result = $this-> getQueryWithParamsResult($query, "s", $param, $limitTaskNumberFrom,  $limitTaskNumberTo);
        return  $this->getDataByResult($result);
    }

    public function getTasksByStatus(int $value, int $limitTaskNumberFrom, int $limitTaskNumberTo): array
    {
        $query =  $this->selectQuery . " WHERE status=? LIMIT ?,?";
        $result = $this-> getQueryWithParamsResult($query, "i", $value, $limitTaskNumberFrom, $limitTaskNumberTo);
        return  $this->getDataByResult($result);
    }

    public function getTasksByAuthorName(string $value, int $limitTaskNumberFrom, int $limitTaskNumberTo): array
    {
        $query =  $this->selectQuery .  " WHERE Author.name LIKE ? LIMIT ?,?";
        $param = "%$value%";
        $result = $this-> getQueryWithParamsResult($query, "s", $param, $limitTaskNumberFrom, $limitTaskNumberTo);
        return  $this->getDataByResult($result);
    }

    public function getTasksByEmail(string $value, int $limitTaskNumberFrom, int $limitTaskNumberTo): array
    {
        $query =  $this->selectQuery . " WHERE Author.email LIKE ? LIMIT ?,?";
        $param = "%$value%";
        $result = $this-> getQueryWithParamsResult($query, "s", $param, $limitTaskNumberFrom, $limitTaskNumberTo);
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

    private function getQueryWithParamsResult(string $query, string $paramType, string $value, int $limitTaskNumberFrom, int $limitTaskNumberTo): mysqli_result
    {
        $stmt = DBClass::getConnection()->prepare($query);
        $stmt->bind_param($paramType . "ii", $value, $limitTaskNumberFrom, $limitTaskNumberTo);
        $stmt->execute();
        return $stmt->get_result();
    }

    private function getQueryWithParamResult(string $query, string $paramType, string $value): mysqli_result
    {
        $stmt = DBClass::getConnection()->prepare($query);
        $stmt->bind_param($paramType, $value);
        $stmt->execute();
        return $stmt->get_result();
    }
}