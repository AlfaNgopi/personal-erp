<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkSpace;
use Yajra\DataTables\Facades\DataTables;

class WorkSpacesController extends Controller
{
    protected WorkSpace $model;

    public function __construct()
    {
        $this->model = new WorkSpace();
    }

    public function index()
    {
        $structure = $this->model->structure();

        return view('dynamiccrud.index', $structure);
    }

    public function indexTable(Request $request)
    {
        if (request()->ajax()) {

            $vendors = WorkSpace::select('work_spaces.*');

            return DataTables::of($vendors)
                ->addIndexColumn()
                ->addColumn('foreign_id', function ($workSpace) {
                    return $workSpace->foreign ? $workSpace->foreign->name : '';
                })
                ->addColumn('actions', function ($workSpace) {
                    $actions = '';

                    if (auth()->user()->hasPermission('show-work-spaces')) {
                        $actions .= '<a href="' . route('work-spaces.show', $workSpace) . '" class="text-green-600 dark:text-green-400 hover:underline mr-3">View</a>';
                    }

                    if (auth()->user()->hasPermission('edit-work-spaces')) {
                        $actions .= '<a href="' . route('work-spaces.edit', $workSpace) . '" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>';
                    }

                    if (auth()->user()->hasPermission('delete-work-spaces')) {
                        $actions .= '<form action="' . route('work-spaces.destroy', $workSpace) . '" method="POST" class="inline" onsubmit="return confirm(\'Are you sure?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                        </form>';
                    }

                    return $actions;
                })
                ->editColumn('created_at', function ($workSpace) {
                    return $workSpace->created_at ? $workSpace->created_at->format('Y-m-d H:i:s') : '';
                })
                ->rawColumns(['actions',])
                ->make(true);
        }
    }

    public function switchWorkspace(Request $request, $workspaceId)
    {
        $user = auth()->user();

        $hasAccess = $user->workSpaces()->where('work_spaces.id', $workspaceId)->exists();

        if (!$hasAccess) {
            abort(403, 'Unauthorized access to this workspace.');
        }

        // Update session active_workspace_id dengan yang baru
        session(['active_workspace_id' => $workspaceId]);

        return redirect()->back()->with('success', 'Workspace berhasil diubah.');
    }

    public function show(WorkSpace $workSpace)
    {
        $structure = $workSpace->structure();
        $data = $workSpace;

        return view('dynamiccrud.show', array_merge($structure, ['data' => $data]));
    }

    public function create()
    {
        $structure = (new WorkSpace())->structure();
        // dd($structure);
        return view('dynamiccrud.create', $structure);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $workSpace = WorkSpace::create($validatedData);

        return redirect()->route('work-spaces.index')->with('success', 'Work Space created successfully.');
    }

    public function edit(WorkSpace $workSpace)
    {
        $structure = $workSpace->structure();
        $data = $workSpace;
        return view('dynamiccrud.edit', array_merge($structure, ['data' => $data]));
    }

    public function update(Request $request, WorkSpace $workSpace)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $workSpace->update($validatedData);

        return redirect()->route('work-spaces.index')->with('success', 'Work Space updated successfully.');
    }

    public function destroy(WorkSpace $workSpace)
    {
        $workSpace->delete();
        return redirect()->route('work-spaces.index')->with('success', 'Work Space deleted successfully.');
    }
}
