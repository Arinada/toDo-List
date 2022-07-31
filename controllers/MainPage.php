<?php

namespace App\Controllers;

use App\Models\DBClass;
use App\Models\Task;
use App\Views\View;

class MainPage
{
    private string $viewName;
    private const TASKS_NUMBER_BY_PAGE = 3;
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
        $filter = $_GET['filter'] ?? "Filter";
        $value = $_GET['value'] ?? "";
        $pageNumber = $_GET['page'] ?? 1;
        $context['authorized'] = $_SESSION['is_admin'];

        $limitTaskNumberFrom = self::TASKS_NUMBER_BY_PAGE * ($pageNumber - 1);
        $limitTaskNumberTo = self::TASKS_NUMBER_BY_PAGE * $pageNumber;

        $numberOfTasks = $this->getTasksNumberByFilter($taskModel, $filter,  $value);
        $tasks = $this->getTasksByFilter($taskModel, $filter, $value, $limitTaskNumberFrom, $limitTaskNumberTo);

        $context['pages_number'] = $this->divRoundUp($numberOfTasks, self::TASKS_NUMBER_BY_PAGE);


        foreach ($tasks as $index => $task) {
             $context['tasks'][$index] = [
                 'id' => $task['id'],
                 'author_name' => $task['name'],
                 'author_email' => $task['email'],
                 'description' => $task['description'],
                 'status' => $task['status']
             ];
        }
        $this->view->renderTemplate($this->viewName, $context);
        DBClass::getConnection()->close();
    }

    private function divRoundUp(int $dividend,int $divider): int
    {
        if ($dividend === 0) {
            return 1;
        }
        $result = (int)($dividend / $divider);
        $remainder = $dividend % $divider;
        if ($remainder > 0) {
            return $result + 1;
        }
        return $result;
    }

    private function getTasksNumberByFilter(Task $taskModel, string $filter, string $value): int
    {
        switch ($filter) {
            case 'Description':
                return $taskModel->getNumberOfTasksByDescription($value);
            case 'Status':
                $status = self::STATUS_CODES[$value] ?? -1;
                return $taskModel->getNumberOfTasksByStatus($status);
            case "Author name":
                return $taskModel->getNumberOfTasksByAuthorName($value);
            case "Email":
                return $taskModel->getNumberOfTasksByEmail($value);
            default:
                return $taskModel->getNumberOfAllTasks();
        }
    }

    private function getTasksByFilter(Task $taskModel,string $filter,string $value, int $limitTaskNumberFrom, int $limitTaskNumberTo): array
    {
        switch ($filter) {
            case 'Description':
                return $taskModel->getTasksByDescription($value, $limitTaskNumberFrom, $limitTaskNumberTo);
            case 'Status':
                $status = self::STATUS_CODES[$value] ?? -1;
                return $taskModel->getTasksByStatus($status, $limitTaskNumberFrom, $limitTaskNumberTo);
            case "Author name":
                return $taskModel->getTasksByAuthorName($value, $limitTaskNumberFrom, $limitTaskNumberTo);
            case "Email":
                return $taskModel->getTasksByEmail($value, $limitTaskNumberFrom, $limitTaskNumberTo);
            default:
                return $taskModel->getAllTasks($limitTaskNumberFrom, $limitTaskNumberTo);
        }
    }

}