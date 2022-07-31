<?php

namespace App\Controllers;

use App\Models\Author;
use App\Models\Task;

class CardController
{
    public function changeCard()
    {
        if (isset($_POST['save-btn'])) {
            $this->save();
        }

        if (isset($_POST['delete-btn'])) {
            $this->delete();
        }
    }

    public function save(): void
    {
        $status = 0;
        if(isset($_POST['task-status'])) {
            $status = 1;
        }
        $taskModel = new Task();
        $taskModel->update($_POST['task-id'],  $_POST['task-description'], $status);
        $previousPageUrl =  $_SERVER['HTTP_REFERER'] ?? "/";
        header('Location: ' . $previousPageUrl);
    }

    public function delete(): void
    {
        $taskModel = new Task();
        $taskModel->delete($_POST['task-id']);
        $previousPageUrl =  $_SERVER['HTTP_REFERER'] ?? "/";
        header('Location: ' . $previousPageUrl);
    }

    public function add()
    {
        $name = $_POST['author-name'];
        $email = $_POST['email'];
        $authorModel = new Author();
        $taskModel = new Task();
        $authorId = $authorModel->getIdByNameAndEmail($name, $email);

        if ($authorId === 0) {
            $authorModel->add($name, $email);
            $authorId = $authorModel->getLastInsertId();
        }
        $taskModel->add($authorId, $_POST['description']);

        header('Location: /');
    }
}