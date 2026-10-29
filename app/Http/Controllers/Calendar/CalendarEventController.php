<?php

namespace App\Http\Controllers\Calendar;

use Illuminate\Http\Request;
use App\Models\Calendar\CalendarEvent;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class CalendarEventController extends Controller
{
    protected CalendarEvent $model;


    public function __construct()
    {
        $this->model = new CalendarEvent();
    }


    public function index()
    {
        $structure = (new CalendarEvent())->structure();
        return view('dynamiccrud.index', $structure);
    }

    public function indexTable(Request $request)
    {
        if (request()->ajax()) {

            $calendarEvent = CalendarEvent::query();
            
            return DataTables::of($calendarEvent)
                ->addIndexColumn()
                ->addColumn('foreign_id', function ($calendarEvent) {
                    return $calendarEvent->foreign ? $calendarEvent->foreign->name : '';
                })
                ->addColumn('actions', function ($calendarEvent) {
                    $actions = '';
                    
                    if (auth()->user()->hasPermission('show-calendar-events')) {
                        $actions .= '<a href="' . route('calendar-events.show', $calendarEvent) . '" class="text-green-600 dark:text-green-400 hover:underline mr-3">View</a>';
                    }
                    
                    if (auth()->user()->hasPermission('edit-calendar-events')) {
                        $actions .= '<a href="' . route('calendar-events.edit', $calendarEvent) . '" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>';
                    }
                    
                    if (auth()->user()->hasPermission('delete-calendar-events')) {
                        $actions .= '<form action="' . route('calendar-events.destroy', $calendarEvent) . '" method="POST" class="inline" onsubmit="return confirm(\'Are you sure?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                        </form>';
                    }
                    
                    return $actions;
                })
                ->editColumn('created_at', function ($calendarEvent) {
                    return $calendarEvent->created_at ? $calendarEvent->created_at->format('Y-m-d H:i:s') : '';
                })
                ->rawColumns(['actions',])
                ->make(true);
        }
    }

    public function show(CalendarEvent $calendarEvent)
    {
        $structure = $calendarEvent->structure();
        $data = $calendarEvent;
        return view('dynamiccrud.show', array_merge($structure, ['data' => $data]));
    }

    public function create()
    {
        $structure = (new CalendarEvent())->structure();
        return view('dynamiccrud.create', $structure);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $currentWorkspaceId = session('active_workspace_id');

        $validatedData['work_space_id'] = $currentWorkspaceId;

        $calendarEvent = CalendarEvent::create($validatedData);

        return redirect()->route('calendar-events.index')->with('success', 'Calendar Event created successfully.');
    }

    public function edit(CalendarEvent $calendarEvent)
    {
        $structure = $calendarEvent->structure();
        $data = $calendarEvent;
        return view('dynamiccrud.edit', array_merge($structure, ['data' => $data]));
    }

    public function update(Request $request, CalendarEvent $calendarEvent)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $currentWorkspaceId = session('active_workspace_id');

        $validatedData['work_space_id'] = $currentWorkspaceId;

        $calendarEvent->update($validatedData);

        return redirect()->route('calendar-events.index')->with('success', 'Calendar Event updated successfully.');
    }

    public function destroy(CalendarEvent $calendarEvent)
    {
        $calendarEvent->delete();
        return redirect()->route('calendar-events.index')->with('success', 'Calendar Event deleted successfully.');
    }
}
