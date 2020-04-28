<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TodosController extends Controller
{
    /**
     * @return Todo[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request)
    {
        return $request->auth->todos()->latest()->paginate();
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $todo = $request->auth->todos()->find($id);
        if (!$todo instanceof Todo) {
            return Response::json(["error"=>[
                "message"=> "This todo list cannot be found"
            ]], 404);
        }
        return Response::json($todo, 200);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'=> 'required|unique:todos'
        ]);
        $todo = $request->auth->addTodo(new Todo($request->all()));
        return Response::json(
            ["message"=> "The todo list has been created successfully",
             "data"=>$todo],
            201
        );
    }

    /**
     * @param Request $request
     * @param $todoId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $todoId)
    {
        $existingTodo = $request->auth->todos()->find($todoId);
        if (!$existingTodo instanceof Todo) {
            $response = Response::json(
                [
                    "error"=>[
                        "message" => "The todo list cannot be found."
                    ]],
                400
            );
            return $response;
        }
        $isTodoDuplicate = $request->auth->hasDuplicateTodo($request->name);
        if ($isTodoDuplicate) {
            return Response::json(
                ["message"=> "The todo list already exists",
                    "data"=>$existingTodo],
                400
            );
        }
        $updatedTodo=$existingTodo->update($request->all());
        return Response::json(
            ["message"=> "The todo list has been updated successfully",
              "data"=> $updatedTodo],
            200
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $todo=Todo::find($id);
        if (!$todo instanceof Todo) {
            $response = Response::json(
                [
                "error"=>[
                    "message" => "The todo list cannot be found."
            ]],
                400
            );
            return $response;
        }
        $deleteResponse = $request->auth->deleteTodo($id);
        return  Response::json($deleteResponse, 200);
        
    }
}