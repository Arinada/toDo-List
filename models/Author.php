<?php

namespace App\Models;

class Author
{
    public function getIdByNameAndEmail(string $name,string $email): int
    {
        $query =  "SELECT * FROM Author WHERE name=? AND email=?";
        $stmt = DBClass::getConnection()->prepare($query);
        $stmt->bind_param("ss", $name, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return  $this->getDataByResult($result)[0]['id'] ?? 0;
    }

    public function getLastInsertId(): int
    {
        return DBClass::getConnection()->insert_id;
    }

    public function add(string $name,string $email): void
    {
        $query = "INSERT INTO Author(name, email) VALUES (?, ?)";
        $stmt = DBClass::getConnection()->prepare($query);
        $stmt->bind_param("ss", $name, $email);
        $stmt->execute();
    }

    private function getDataByResult($result): array
    {
        $counter = 0;
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[$counter] = $row;
            $counter++;
        }
        return $data;
    }
}