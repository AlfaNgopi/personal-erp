<?php

namespace App\Http\Controllers\Calendar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Calendar\CalendarTask;
use Yajra\DataTables\Facades\DataTables;


class CalendarTaskController extends Controller
{
    protected CalendarTask $model;


    public function __construct()
    {
        $this->model = new CalendarTask();
    }


    public function index()
    {
        $structure = (new CalendarTask())->structure();
        return view('dynamiccrud.index', $structure);
    }

    public function indexTable(Request $request)
    {
        if (request()->ajax()) {

            $calendarTasks = CalendarTask::query();
            
            return DataTables::of($calendarTasks)
                ->addIndexColumn()
                ->addColumn('foreign_id', function ($calendarTask) {
                    return $calendarTask->foreign ? $calendarTask->foreign->name : '';
                })
                ->addColumn('actions', function ($calendarTask) {
                    $actions = '';
                    
                    if (auth()->user()->hasPermission('show-calendar-tasks')) {
                        $actions .= '<a href="' . route('calendar-tasks.show', $calendarTask) . '" class="text-green-600 dark:text-green-400 hover:underline mr-3">View</a>';
                    }
                    
                    if (auth()->user()->hasPermission('edit-calendar-tasks')) {
                        $actions .= '<a href="' . route('calendar-tasks.edit', $calendarTask) . '" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>';
                    }
                    
                    if (auth()->user()->hasPermission('delete-calendar-tasks')) {
                        $actions .= '<form action="' . route('calendar-tasks.destroy', $calendarTask) . '" method="POST" class="inline" onsubmit="return confirm(\'Are you sure?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                        </form>';
                    }
                    
                    return $actions;
                })
                ->editColumn('created_at', function ($calendarTask) {
                    return $calendarTask->created_at ? $calendarTask->created_at->format('Y-m-d H:i:s') : '';
                })
                ->rawColumns(['actions',])
                ->make(true);
        }
    }

    public function show(CalendarTask $calendarTask)
    {
        $structure = $calendarTask->structure();
        $data = $calendarTask;
        return view('dynamiccrud.show', array_merge($structure, ['data' => $data]));
    }

    public function create()
    {
        $structure = (new CalendarTask())->structure();
        return view('dynamiccrud.create', $structure);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $currentWorkspaceId = session('active_workspace_id');

        $validatedData['work_space_id'] = $currentWorkspaceId;

        $calendarTask = CalendarTask::create($validatedData);

        return redirect()->route('calendar-tasks.index')->with('success', 'Calendar Task created successfully.');
    }

    public function edit(CalendarTask $calendarTask)
    {
        $structure = $calendarTask->structure();
        $data = $calendarTask;
        return view('dynamiccrud.edit', array_merge($structure, ['data' => $data]));
    }

    public function update(Request $request, CalendarTask $calendarTask)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);
        $currentWorkspaceId = session('active_workspace_id');
        $validatedData['work_space_id'] = $currentWorkspaceId;
        
        $calendarTask->update($validatedData);

        return redirect()->route('calendar-tasks.index')->with('success', 'Calendar Task updated successfully.');
    }

    public function destroy(CalendarTask $calendarTask)
    {
        $calendarTask->delete();
        return redirect()->route('calendar-tasks.index')->with('success', 'Calendar Task deleted successfully.');
    }
}
