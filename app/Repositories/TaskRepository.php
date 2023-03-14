<?php
  namespace App\Repositories;
  use App\Interfaces\TaskRepositoryInterface;
  use App\Models\Task;

  class TaskRepository implements TaskRepositoryInterface
  {
    public function getAllTasks()
    {
      return Task::with('project')->get();
    }

    public function getHighTaskPriority()
    {
      return Task::max('priority');
    }
    public function createTask($newTask)
    {
      return Task::insert($newTask);
    }

    public function showTask($taskId)
    {
      return Task::with('project')->find($taskId);
    }

    public function deleteTask($taskId)
    {
      $taskToDelete = Task::find($taskId);
      $taskPriority = $taskToDelete->priority;

      // query all tasks with high priority in order to reduce them by 1
      $allTasksWithHighPriority = Task::where('priority', '>', $taskPriority)->get();
      if (count($allTasksWithHighPriority) > 0) {
        foreach ($allTasksWithHighPriority as $task) {
          $task->update([
            'priority' => $task->priority - 1
          ]);
        }
      }
      return Task::find($taskId)->delete();
    }

    public function updateTaskByField($taskId, $updatedTask)
    {
      return Task::find($taskId)->update([
        'name' => $updatedTask['name']
      ]);
    }    
  }
?>