<?php

namespace App\Controllers;

use App\Models\DBClass;
use App\Models\Task;
use App\Views\View;

class MainPage
{
    private string $viewName;
    private View $view;
    private const  STATUS_CODES = [
            'done' => 1,
            'not done' => 0,
            'have done' => 0
    ];

    public function __construct(string $viewName)
    {
        $this->viewName = $viewName;
        $this->view = new View();
    }

    public function showStartPage(array $params = null): void
    {
        $context = [];
        $taskModel = new Task();
        $tasks = $this->getTasksByFilter($taskModel, $params['filter'] ?? '', $params['value'] ?? '');

        foreach ($tasks as $index => $task) {
             $context[$index] = [
                 'author_name' => $task['name'],
                 'author_email' => $task['email'],
                 'description' => $task['description'],
                 'status' => $task['status']
             ];
        }
        $this->view->renderStartPage($this->viewName, $context);
        DBClass::getConnection()->close();
    }

    private function getTasksByFilter(Task $taskModel,string $filter,string $value): array
    {
        switch ($filter) {
            case 'Description':
                return $taskModel->getTasksByDescription($value);
            case 'Status':
                $status = self::STATUS_CODES[$value] ?? 0;
                return $taskModel->getTasksByStatus($status);
            case "Author name":
                return $taskModel->getTasksByAuthorName($value);
            case "Email":
                return $taskModel->getTasksByEmail($value);
            default:
                return $taskModel->getAllTasks();
        }
    }

}