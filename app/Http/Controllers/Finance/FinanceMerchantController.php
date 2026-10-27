<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\FinanceMerchant;
use App\Models\Finance\FinanceTransaction;
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

            $financeMerchant = FinanceMerchant::with('work_space', 'default_category');

            return DataTables::of($financeMerchant)
                ->addIndexColumn()
                
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

        $financeMerchant = FinanceMerchant::create($validatedData);

        return redirect()->route('finance-merchants.index')->with('success', 'Merchant created successfully.');
    }

    public function edit(FinanceMerchant $financeMerchant)
    {
        $structure = $financeMerchant->structure();
        $structure['columns'][] =
            [
                'name' => 'apply',
                'type' => 'checkbox',
                'validation' => 'nullable|boolean',
                'label' => 'apply',
                'required' => false,
                'inEditForm' => true,
            ];

        // dd($structure);


        $data = $financeMerchant;
        return view('dynamiccrud.edit', array_merge($structure, ['data' => $data]));
    }

    public function update(Request $request, FinanceMerchant $financeMerchant)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        

        $financeMerchant->update($validatedData);


        $validateApply = $request->validate([
            'apply' => 'nullable|boolean'
        ]);

        // apply category transaksi dengan category merchant yang barusan diupdate
        if ($request->apply == 1) {
            FinanceTransaction::where('finance_merchant_id', $financeMerchant->id)->update(['finance_category_id' => $financeMerchant->default_category_id]);
        }

        

        return redirect()->route('finance-merchants.index')->with('success', 'Merchant updated successfully.');
    }

    public function destroy(FinanceMerchant $financeMerchant)
    {
        $financeMerchant->delete();
        return redirect()->route('finance-merchants.index')->with('success', 'Merchant deleted successfully.');
    }
}
