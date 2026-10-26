<?php

namespace App\Http\Controllers\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Finance\FinanceCategory;
use Yajra\DataTables\Facades\DataTables;


class FinanceCategoryController extends Controller
{
    protected FinanceCategory $model;


    public function __construct()
    {
        $this->model = new FinanceCategory();
    }


    public function index()
    {
        $structure = (new FinanceCategory())->structure();
        return view('dynamiccrud.index', $structure);
    }

    public function indexTable(Request $request)
    {
        if (request()->ajax()) {

            $finacecategories = FinanceCategory::query();
            
            return DataTables::of($finacecategories)
                ->addIndexColumn()
                ->addColumn('foreign_id', function ($fc) {
                    return $fc->foreign ? $fc->foreign->name : '';
                })
                ->addColumn('actions', function ($fc) {
                    $actions = '';
                    
                    if (auth()->user()->hasPermission('show-template-studs')) {
                        $actions .= '<a href="' . route('template-studs.show', $fc) . '" class="text-green-600 dark:text-green-400 hover:underline mr-3">View</a>';
                    }
                    
                    if (auth()->user()->hasPermission('edit-template-studs')) {
                        $actions .= '<a href="' . route('template-studs.edit', $fc) . '" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>';
                    }
                    
                    if (auth()->user()->hasPermission('delete-template-studs')) {
                        $actions .= '<form action="' . route('template-studs.destroy', $fc) . '" method="POST" class="inline" onsubmit="return confirm(\'Are you sure?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                        </form>';
                    }
                    
                    return $actions;
                })
                ->editColumn('created_at', function ($fc) {
                    return $fc->created_at ? $fc->created_at->format('Y-m-d H:i:s') : '';
                })
                ->rawColumns(['actions',])
                ->make(true);
        }
    }

    public function show(FinanceCategory $fc)
    {
        $structure = $fc->structure();
        $data = $fc;
        return view('dynamiccrud.show', array_merge($structure, ['data' => $data]));
    }

    public function create()
    {
        $structure = (new FinanceCategory())->structure();
        // dd($structure);
        return view('dynamiccrud.create', $structure);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $fc = FinanceCategory::create($validatedData);

        return redirect()->route('finance-categories.index')->with('success', 'Finance Category created successfully.');
    }

    public function edit(FinanceCategory $fc)
    {
        $structure = $fc->structure();
        $data = $fc;
        return view('dynamiccrud.edit', array_merge($structure, ['data' => $data]));
    }

    public function update(Request $request, FinanceCategory $fc)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $fc->update($validatedData);

        return redirect()->route('finance-categories.index')->with('success', 'Finance Category updated successfully.');
    }

    public function destroy(FinanceCategory $fc)
    {
        $fc->delete();
        return redirect()->route('finance-categories.index')->with('success', 'Finance Category deleted successfully.');
    }
}
