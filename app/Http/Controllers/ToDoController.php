<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Models\ToDo;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ToDoController extends AppBaseController
{
    /**
     * Get a paginated list of ToDos for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $todos = ToDo::where('user_id', Auth::id())->paginate(10);
        
        return response()->json(['todos' => $todos]);
    }

    /**
     * Create a new ToDo for the authenticated user.
     *
     * @param TodoRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TodoRequest $request)
    {
        $input = array_merge($request->validated(), ['user_id' => Auth::id()]);
        $todo = ToDo::create($input);

        return $this->sendSuccessResponse($todo, 'ToDo created successfully');
    }

    /**
     * Display the specified ToDo if the authenticated user owns it.
     *
     * @param ToDo $todo
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ToDo $todo)
    {
        if ($todo->userOwns()) {

            return response()->json(['todo' => $todo]);
        }

        return $this->sendError('ToDo not found.');
    }

    /**
     * Update the specified ToDo if the authenticated user owns it.
     *
     * @param TodoRequest $request
     * @param ToDo $todo
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TodoRequest $request, ToDo $todo)
    {
        if ($todo->userOwns()) {
            $todo->update($request->validated());

            return $this->sendSuccessResponse($todo, 'ToDo updated successfully');
        }

        return $this->sendError('ToDo not found.');
    }

    /**
     * Remove the specified ToDo if the authenticated user owns it.
     *
     * @param ToDo $todo
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ToDo $todo)
    {
        if ($todo->userOwns()) {
            $todo->delete();

            return $this->sendSuccess('ToDo deleted successfully');
        }

        return $this->sendError('ToDo not found.');
    }

}
