<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\FinanceMerchant;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class FinanceMerchantController extends Controller
{
    protected FinanceMerchant $model;


    public function __construct()
    {
        $this->model = new FinanceMerchant();
    }


    public function index()
    {
        $structure = (new FinanceMerchant())->structure();
        return view('dynamiccrud.index', $structure);
    }

    public function indexTable(Request $request)
    {
        if (request()->ajax()) {

            $financeMerchant = FinanceMerchant::query();
            
            return DataTables::of($financeMerchant)
                ->addIndexColumn()
                ->addColumn('default_category_id', function ($templateStud) {
                    return $templateStud->default_category ? $templateStud->default_category->name : '';
                })
                ->addColumn('actions', function ($templateStud) {
                    $actions = '';
                    
                    if (auth()->user()->hasPermission('show-finance-merchants')) {
                        $actions .= '<a href="' . route('finance-merchants.show', $templateStud) . '" class="text-green-600 dark:text-green-400 hover:underline mr-3">View</a>';
                    }
                    
                    if (auth()->user()->hasPermission('edit-finance-merchants')) {
                        $actions .= '<a href="' . route('finance-merchants.edit', $templateStud) . '" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>';
                    }
                    
                    if (auth()->user()->hasPermission('delete-finance-merchants')) {
                        $actions .= '<form action="' . route('finance-merchants.destroy', $templateStud) . '" method="POST" class="inline" onsubmit="return confirm(\'Are you sure?\')">
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

    public function show(FinanceMerchant $templateStud)
    {
        $structure = $templateStud->structure();
        $data = $templateStud;
        return view('dynamiccrud.show', array_merge($structure, ['data' => $data]));
    }

    public function create()
    {
        $structure = (new FinanceMerchant())->structure();
        // dd($structure);
        return view('dynamiccrud.create', $structure);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $templateStud = FinanceMerchant::create($validatedData);

        return redirect()->route('finance-merchants.index')->with('success', 'Template Stud created successfully.');
    }

    public function edit(FinanceMerchant $financeMerchant)
    {
        $structure = $financeMerchant->structure();
        $data = $financeMerchant;
        return view('dynamiccrud.edit', array_merge($structure, ['data' => $data]));
    }

    public function update(Request $request, FinanceMerchant $financeMerchant)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $financeMerchant->update($validatedData);

        return redirect()->route('finance-merchants.index')->with('success', 'Template Stud updated successfully.');
    }

    public function destroy(FinanceMerchant $financeMerchant)
    {
        $financeMerchant->delete();
        return redirect()->route('finance-merchants.index')->with('success', 'Template Stud deleted successfully.');
    }
}
