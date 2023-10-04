<?php

namespace App\Http\Controllers\API;

use App\Models\Todo;
use App\Http\Controllers\Controller;
use App\Http\Resources\TodoResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TodoController extends Controller
{

 
    protected $todo;

    public function __construct(Todo $todo)
    {
        $this->todo = $todo;
    }
    

    /**
     * @OA\Get(
     *     path="/api/todos",
     *     tags={"todos"},
     *     summary="Get all todos",
     *     description="This method get all todo.",
     *     operationId="index",
     *     @OA\Parameter(
     *         name="tags",
     *         in="query",
     *         description="Tags to filter by",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(
     *                 type="string",
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     ),
     * )
     */
    public function index()
    {
        $todos = $this->todo->paginate(5);

        $todosResource = TodoResource::collection($todos)->response()->getData(assoc: true);

        return response()->json([
            'data' => $todosResource
        ], status: Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/todos",
     *     tags={"todos"},
     *     summary="Add one todos",
     *     description="This method add a todo",
     *     operationId="store",
     *     @OA\Parameter(
     *         name="tags",
     *         in="query",
     *         description="Tags to filter by",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(
     *                 type="string",
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     ),
     * )
     */
    public function store(Request $request)
    {
        $todo = $this->todo->create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'due_date' => $request->input('due_date'),
        ]);

        return response()->json([
            'data' => new TodoResource($todo)
        ], status: Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/api/todos/id",
     *     tags={"todos"},
     *     summary="Get one todos",
     *     description="This method get a todo with the given id.",
     *     operationId="show",
     *     @OA\Parameter(
     *         name="tags",
     *         in="query",
     *         description="Tags to filter by",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(
     *                 type="string",
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     ),
     * )
     */
    public function show($id)
    {
        $todo = $this->todo->find($id);

        if (!$todo) {
            return response()->json([
                'message' => 'Todo not found.',
            ], status: Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'data' => new TodoResource($todo),
        ], status: Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *     path="/api/todos/id",
     *     tags={"todos"},
     *     summary="Update one todos",
     *     description="This method updates a todo with the given id.",
     *     operationId="update",
     *     @OA\Parameter(
     *         name="tags",
     *         in="query",
     *         description="Tags to filter by",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(
     *                 type="string",
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     ),
     * )
     */
    public function update(Request $request, $id)
    {
        $todo = $this->todo->find($id);

        if (!$todo) {
            return response()->json([
                'message' => 'Todo not found.',
            ], status: Response::HTTP_NOT_FOUND);
        }

        $todo->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'due_date' => $request->input('due_date'),
        ]);

        return response()->json([
            'data' => new TodoResource($todo),
        ], status: Response::HTTP_OK);
    }

    /**
     * @OA\Delete(
     *     path="/api/todos/id",
     *     tags={"todos"},
     *     summary="Delete one todos",
     *     description="This method deletes a todo with the given id.",
     *     operationId="delete",
     *     @OA\Parameter(
     *         name="tags",
     *         in="query",
     *         description="Tags to filter by",
     *         required=true,
     *         explode=true,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(
     *                 type="string",
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     ),
     * )
     */
    public function destroy($id)
    {
        $todo = $this->todo->find($id);

        if (!$todo) {
            return response()->json([
                'message' => 'Todo not found.',
            ], status: Response::HTTP_NOT_FOUND);
        }

        $todo->delete();

        return response()->json([
            'message' => 'Todo deleted successfully.',
        ], status: Response::HTTP_OK);
    }
}
