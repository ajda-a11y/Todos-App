<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::all();
        return view('todos.index',[
            'todos' => $todos
        ]);
    }

    public function create()
    {
        return view('todos.create');
    }
    public function store(TodoRequest $request)
    {
        $request->validated();
        Todo::create([
            'title' => $request->title,
            'description' => $request->description,
            'is_completed' => 0

        ]);

        $request->session()->flash('alert-success','Entry created successfully');

        return to_route('todos.index');
    }
    public function show($id)
    {
        $todo = Todo::find($id);
        if(!$todo){
            request()->session()->flash('error','Unable to locate that entry!');
            return to_route('todos.index')->withErrors([
                'error' => 'Unable to locate that entry!'
            ]);
        }
        return view('todos.show', ['todo' => $todo]);
    }
    public function edit($id)
    {
        $todo = Todo::find($id);
        if(!$todo){
            request()->session()->flash('error','Unable to locate that entry!');
            return to_route('todos.index')->withErrors([
                'error' => 'Unable to locate that entry!'
            ]);
        }
        return view('todos.edit', ['todo' => $todo]);
    }
    public function update(TodoRequest $request)
    {
        $todo = Todo::find($request->todo_id);
        if(!$todo){
            request()->session()->flash('error','Unable to locate that entry!');
            return to_route('todos.index')->withErrors([
                'error' => 'Unable to locate that entry!'
            ]);
        }

        $todo->update([
            'title' => $request->title,
            'description' => $request->description,
            'is_completed' => $request->is_completed

        ]);
        $request->session()->flash('alert-info','Entry edited successfully');
        return to_route('todos.index');
    }
    public function destroy(Request $request)
    {
        $todo = Todo::find($request->todo_id);
        if(!$todo) {
            request()->session()->flash('error', 'Unable to locate that entry!');
            return to_route('todos.index')->withErrors([
                'error' => 'Unable to locate that entry!'
            ]);
        }

        $todo->delete();
        $request->session()->flash('alert-success','Entry deleted successfully');
        return to_route('todos.index');
    }
}

