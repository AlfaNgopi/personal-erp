<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkSpaceMember;
use Yajra\DataTables\Facades\DataTables;

class WorkSpaceMembersController extends Controller
{
    protected WorkSpaceMember $model;


    public function __construct()
    {
        $this->model = new WorkSpaceMember();
    }


    public function index()
    {
        $structure = (new WorkSpaceMember())->structure();
        return view('dynamiccrud.index', $structure);
    }

    public function indexTable(Request $request)
    {
        if (request()->ajax()) {

            $workspacemembers = WorkSpaceMember::with('user', 'workSpace');

            return DataTables::of($workspacemembers)
                
                ->addColumn('actions', function ($workSpaceMember) {
                    $actions = '';

                    if (auth()->user()->hasPermission('show-work-space-members')) {
                        $actions .= '<a href="' . route('work-space-members.show', $workSpaceMember) . '" class="text-green-600 dark:text-green-400 hover:underline mr-3">View</a>';
                    }

                    if (auth()->user()->hasPermission('edit-work-space-members')) {
                        $actions .= '<a href="' . route('work-space-members.edit', $workSpaceMember) . '" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>';
                    }

                    if (auth()->user()->hasPermission('delete-work-space-members')) {
                        $actions .= '<form action="' . route('work-space-members.destroy', $workSpaceMember) . '" method="POST" class="inline" onsubmit="return confirm(\'Are you sure?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                        </form>';
                    }

                    return $actions;
                })
                ->editColumn('created_at', function ($workSpaceMember) {
                    return $workSpaceMember->created_at ? $workSpaceMember->created_at->format('Y-m-d H:i:s') : '';
                })
                ->rawColumns(['actions',])
                ->make(true);
        }
    }

    public function show(WorkSpaceMember $workSpaceMember)
    {
        $structure = $workSpaceMember->structure();
        $data = $workSpaceMember;
        return view('dynamiccrud.show', array_merge($structure, ['data' => $data]));
    }

    public function create()
    {
        $structure = (new WorkSpaceMember())->structure();
        return view('dynamiccrud.create', $structure);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $workSpaceMember = WorkSpaceMember::create($validatedData);

        return redirect()->route('work-space-members.index')->with('success', 'Work Space Member created successfully.');
    }

    public function edit(WorkSpaceMember $workSpaceMember)
    {
        $structure = $workSpaceMember->structure();
        $data = $workSpaceMember;
        return view('dynamiccrud.edit', array_merge($structure, ['data' => $data]));
    }

    public function update(Request $request, WorkSpaceMember $workSpaceMember)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $workSpaceMember->update($validatedData);

        return redirect()->route('work-space-members.index')->with('success', 'Work Space Member updated successfully.');
    }

    public function destroy(WorkSpaceMember $workSpaceMember)
    {
        $workSpaceMember->delete();
        return redirect()->route('work-space-members.index')->with('success', 'Work Space Member deleted successfully.');
    }
}
