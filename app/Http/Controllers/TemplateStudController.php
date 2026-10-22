<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\TemplateStud;
use Yajra\DataTables\Facades\DataTables;

// this is not real controller, just a template for dynamic CRUD operations
class TemplateStudController extends Controller
{
    protected TemplateStud $model;


    public function __construct()
    {
        $this->model = new TemplateStud();
    }


    public function index()
    {
        $structure = (new TemplateStud())->structure();
        return view('dynamiccrud.index', compact('structure'));
    }

    public function indexTable(Request $request)
    {
        if (request()->ajax()) {

            $vendors = TemplateStud::select('template_studs.*');
            
            return DataTables::of($vendors)
                ->addColumn('foreign_id', function ($templateStud) {
                    return $templateStud->foreign ? $templateStud->foreign->name : '';
                })
                ->addColumn('actions', function ($templateStud) {
                    $actions = '';
                    
                    if (auth()->user()->hasPermission('show-template-studs')) {
                        $actions .= '<a href="' . route('template-studs.show', $templateStud) . '" class="text-green-600 dark:text-green-400 hover:underline mr-3">View</a>';
                    }
                    
                    if (auth()->user()->hasPermission('edit-template-studs')) {
                        $actions .= '<a href="' . route('template-studs.edit', $templateStud) . '" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>';
                    }
                    
                    if (auth()->user()->hasPermission('delete-template-studs')) {
                        $actions .= '<form action="' . route('template-studs.destroy', $templateStud) . '" method="POST" class="inline" onsubmit="return confirm(\'Are you sure?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                        </form>';
                    }
                    
                    return $actions;
                })
                ->editColumn('created_at', function ($templateStud) {
                    return $templateStud->created_at ? $templateStud->created_at->format('Y-m-d H:i:s') : '';
                })
                ->rawColumns(['actions',])
                ->make(true);
        }
    }

    public function show(TemplateStud $templateStud)
    {
        $structure = $templateStud->structure();
        $data = $templateStud;
        //fill value
        foreach ($structure['columns'] as &$column) {
            $column['value'] = $templateStud->{$column['name']};
        }
        return view('dynamiccrud.show', compact('structure', 'data'));
    }

    public function create()
    {
        $structure = (new TemplateStud())->structure();
        // dd($structure);
        return view('dynamiccrud.create', compact('structure'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $templateStud = TemplateStud::create($validatedData);

        return redirect()->route('template-studs.show', $templateStud)->with('success', 'Template Stud created successfully.');
    }

    public function edit(TemplateStud $templateStud)
    {
        $structure = $templateStud->structure();
        $data = $templateStud;
        return view('dynamiccrud.edit', compact('data', 'structure'));
    }

    public function update(Request $request, TemplateStud $templateStud)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $templateStud->update($validatedData);

        return redirect()->route('template-studs.show', $templateStud)->with('success', 'Template Stud updated successfully.');
    }

    public function destroy(TemplateStud $templateStud)
    {
        $templateStud->delete();
        return redirect()->route('template-studs.index')->with('success', 'Template Stud deleted successfully.');
    }
}
