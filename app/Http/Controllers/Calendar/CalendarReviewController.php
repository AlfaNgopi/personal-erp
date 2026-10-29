<?php

namespace App\Http\Controllers\Calendar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Calendar\CalendarReview;
use Yajra\DataTables\Facades\DataTables;


class CalendarReviewController extends Controller
{
    protected CalendarReview $model;


    public function __construct()
    {
        $this->model = new CalendarReview();
    }


    public function index()
    {
        $structure = (new CalendarReview())->structure();
        return view('dynamiccrud.index', $structure);
    }

    public function indexTable(Request $request)
    {
        if (request()->ajax()) {

            $calendarReview = CalendarReview::query();
            
            return DataTables::of($calendarReview)
                ->addIndexColumn()
                ->addColumn('foreign_id', function ($calendarReview) {
                    return $calendarReview->foreign ? $calendarReview->foreign->name : '';
                })
                ->addColumn('actions', function ($calendarReview) {
                    $actions = '';
                    
                    if (auth()->user()->hasPermission('show-calendar-reviews')) {
                        $actions .= '<a href="' . route('calendar-reviews.show', $calendarReview) . '" class="text-green-600 dark:text-green-400 hover:underline mr-3">View</a>';
                    }
                    
                    if (auth()->user()->hasPermission('edit-calendar-reviews')) {
                        $actions .= '<a href="' . route('calendar-reviews.edit', $calendarReview) . '" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>';
                    }
                    
                    if (auth()->user()->hasPermission('delete-calendar-reviews')) {
                        $actions .= '<form action="' . route('calendar-reviews.destroy', $calendarReview) . '" method="POST" class="inline" onsubmit="return confirm(\'Are you sure?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                        </form>';
                    }
                    
                    return $actions;
                })
                ->editColumn('created_at', function ($calendarReview) {
                    return $calendarReview->created_at ? $calendarReview->created_at->format('Y-m-d H:i:s') : '';
                })
                ->rawColumns(['actions',])
                ->make(true);
        }
    }

    public function show(CalendarReview $calendarReview)
    {
        $structure = $calendarReview->structure();
        $data = $calendarReview;
        return view('dynamiccrud.show', array_merge($structure, ['data' => $data]));
    }

    public function create()
    {
        $structure = (new CalendarReview())->structure();
        return view('dynamiccrud.create', $structure);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $currentWorkspaceId = session('active_workspace_id');

        $validatedData['work_space_id'] = $currentWorkspaceId;

        $calendarReview = CalendarReview::create($validatedData);

        return redirect()->route('calendar-reviews.index')->with('success', 'Calendar Review created successfully.');
    }

    public function edit(CalendarReview $calendarReview)
    {
        $structure = $calendarReview->structure();
        $data = $calendarReview;
        return view('dynamiccrud.edit', array_merge($structure, ['data' => $data]));
    }

    public function update(Request $request, CalendarReview $calendarReview)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $currentWorkspaceId = session('active_workspace_id');

        $validatedData['work_space_id'] = $currentWorkspaceId;

        $calendarReview->update($validatedData);

        return redirect()->route('calendar-reviews.index')->with('success', 'Calendar Review updated successfully.');
    }

    public function destroy(CalendarReview $calendarReview)
    {
        $calendarReview->delete();
        return redirect()->route('calendar-reviews.index')->with('success', 'Calendar Review deleted successfully.');
    }
}
