<?php

namespace App\Http\Controllers\API;

use App\Models\Todo;
use App\Http\Controllers\Controller;
use App\Http\Resources\TodoResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TodoController extends Controller
{

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
    $todos = Todo::all();

    return response()->json($todos, Response::HTTP_OK);
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
        $data = $request->only(['name', 'body']);
    
        // Tạo một bản ghi mới
        $todo = Todo::create($data);
    
        return response()->json($todo, Response::HTTP_CREATED);
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
        $todo = Todo::find($id);
    
        if (!$todo) {
            return response()->json([
                'message' => 'Todo not found.',
            ], Response::HTTP_NOT_FOUND);
        }
    
        return response()->json($todo, Response::HTTP_OK);
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
    // Tìm kiếm Todo theo ID, nếu không tìm thấy sẽ trả về lỗi 404
    $todo = Todo::findOrFail($id);

    // Kiểm tra xem Todo có tồn tại không
    if (!$todo) {
        return response()->json([
            'message' => 'Todo not found.',
        ], Response::HTTP_NOT_FOUND);
    }

    // Lấy dữ liệu từ request
    $data = $request->only(['name', 'body']);

    // Cập nhật dữ liệu của Todo
    $todo->update($data);

    // Trả về Todo đã được cập nhật
    return response()->json($todo, Response::HTTP_OK);
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
    $todo = Todo::findOrFail($id);

    if (!$todo) {
        return response()->json([
            'message' => 'Todo not found.',
        ], Response::HTTP_NOT_FOUND);
    }

    $todo->delete();

    return response()->json([
        'message' => 'Todo deleted successfully.',
    ], Response::HTTP_OK);
}

}
