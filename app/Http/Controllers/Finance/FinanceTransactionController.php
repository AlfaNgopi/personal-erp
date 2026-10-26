<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Finance\FinanceTransaction;

class FinanceTransactionController extends Controller
{
    protected FinanceTransaction $model;


    public function __construct()
    {
        $this->model = new FinanceTransaction();
    }


    public function index()
    {
        $structure = (new FinanceTransaction())->structure();
        return view('dynamiccrud.index', $structure);
    }

    public function indexTable(Request $request)
    {
        if (request()->ajax()) {

            $templateStuds = FinanceTransaction::query();
            
            return DataTables::of($templateStuds)
                ->addIndexColumn()
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

    public function show(FinanceTransaction $financeTransaction)
    {
        $structure = $financeTransaction->structure();
        $data = $financeTransaction;
        return view('dynamiccrud.show', array_merge($structure, ['data' => $data]));
    }

    public function create()
    {
        $structure = (new FinanceTransaction())->structure();
        // dd($structure);
        return view('dynamiccrud.create', $structure);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $financeTransaction = FinanceTransaction::create($validatedData);

        return redirect()->route('finance-transactions.index')->with('success', 'Finance Transaction created successfully.');
    }

    public function edit(FinanceTransaction $financeTransaction)
    {
        $structure = $financeTransaction->structure();
        $data = $financeTransaction;
        return view('dynamiccrud.edit', array_merge($structure, ['data' => $data]));
    }

    public function update(Request $request, FinanceTransaction $financeTransaction)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $financeTransaction->update($validatedData);

        return redirect()->route('finance-transactions.index')->with('success', 'Finance Transaction updated successfully.');
    }

    public function destroy(FinanceTransaction $financeTransaction)
    {
        $financeTransaction->delete();
        return redirect()->route('finance-transactions.index')->with('success', 'Finance Transaction deleted successfully.');
    }
}
