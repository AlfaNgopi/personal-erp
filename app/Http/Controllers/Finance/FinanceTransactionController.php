<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Service\PdfParserService;
use App\Models\Finance\FinanceMerchant;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Finance\FinanceTransaction;
use Carbon\Carbon;

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
        // return view('financetransaction.dashboard', $structure);
        return view('financetransaction.index', $structure);
    }

    public function indexTable(Request $request)
    {
        if (request()->ajax()) {

            $financeTrasaction = FinanceTransaction::with('finance_merchant', 'finance_category', 'work_space')
            ->select('finance_transactions.*');

            return DataTables::of($financeTrasaction)
                ->addIndexColumn()
                

                ->addColumn('actions', function ($financeTrasaction) {
                    $actions = '';

                    if (auth()->user()->hasPermission('show-finance-transactions')) {
                        $actions .= '<a href="' . route('finance-transactions.show', $financeTrasaction) . '" class="text-green-600 dark:text-green-400 hover:underline mr-3">View</a>';
                    }

                    if (auth()->user()->hasPermission('edit-finance-transactions')) {
                        $actions .= '<a href="' . route('finance-transactions.edit', $financeTrasaction) . '" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>';
                    }

                    if (auth()->user()->hasPermission('delete-finance-transactions')) {
                        $actions .= '<form action="' . route('finance-transactions.destroy', $financeTrasaction) . '" method="POST" class="inline" onsubmit="return confirm(\'Are you sure?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                        </form>';
                    }

                    return $actions;
                })
                ->editColumn('created_at', function ($financeTrasaction) {
                    return $financeTrasaction->created_at ? $financeTrasaction->created_at->format('Y-m-d H:i:s') : '';
                })
                ->rawColumns(['actions',])
                ->make(true);
        }
    }

    public function importPdf(Request $request)
    {
        $currentWorkspaceId = session('active_workspace_id');


        $path = $request->file('pdf')->getRealPath();
        $text = (new PdfParserService())->extract($path);
        // Samakan line ending
        $text = str_replace(["\r\n", "\r"], "\n", $text);

        // Pisahkan teks berdasarkan awal setiap transaksi
        $blocks = preg_split(
            '/(?=\d{2}\/\d{2}\/\d{4}\s*\n\s*\d{2}:\d{2})/',
            $text,
            -1,
            PREG_SPLIT_NO_EMPTY
        );

        $transactions = collect($blocks)
            ->map(function ($block) {
                // Ambil tanggal dan waktu
                if (!preg_match(
                    '/(\d{2}\/\d{2}\/\d{4})\s*\n\s*(\d{2}:\d{2})/',
                    $block,
                    $dateMatch
                )) {
                    return null;
                }

                $tanggal = $dateMatch[1];
                $waktu = $dateMatch[2];

                // Ambil metode pembayaran dan jumlah
                if (!preg_match(
                    '/(GoPay Coins|GoPay Saldo)\s*(-?Rp[\d.,]+|\d+)/',
                    $block,
                    $amountMatch
                )) {
                    return null;
                }

                $metode = $amountMatch[1];
                $jumlahText = trim($amountMatch[2]);

                $jumlah = (int) preg_replace('/\D/', '', $jumlahText);

                if (str_contains($jumlahText, '-')) {
                    $jumlah *= -1;
                }

                // Ambil bagian sebelum metode pembayaran
                $content = substr(
                    $block,
                    strpos($block, $dateMatch[0]) + strlen($dateMatch[0])
                );

                $content = substr(
                    $content,
                    0,
                    strpos($content, $amountMatch[0])
                );

                $content = trim($content);

                // Deteksi ID transaksi yang menempel pada nama merchant.
                // Contoh: DKRIUK JATINGALEH 0420260929130647wdDA
                if (!preg_match(
                    '/((?:01|02|03|04|05)2026\d+[A-Za-z0-9]*)/',
                    $content,
                    $idMatch,
                    PREG_OFFSET_CAPTURE
                )) {
                    return null;
                }

                $idTransaksi = $idMatch[1][0];
                $idPosition = $idMatch[1][1];

                $nama = trim(substr($content, 0, $idPosition));

                // ID dapat berlanjut pada baris berikutnya
                $remaining = trim(substr(
                    $content,
                    $idPosition + strlen($idTransaksi)
                ));

                if ($remaining !== '') {
                    $idTransaksi .= preg_replace('/\s+/', '', $remaining);
                }

                // Bersihkan whitespace nama merchant
                $nama = preg_replace('/\s+/', ' ', $nama);

                return [
                    'tanggal' => $tanggal,
                    'waktu' => $waktu,
                    'nama' => $nama,
                    'id_transaksi' => $idTransaksi,
                    'metode' => $metode,
                    'jumlah' => $jumlah,
                ];
            })
            ->filter()
            ->values();

        // dd($transactions);

        foreach ($transactions as $t) {
            //skip gopay coin
            if ($t['metode'] === 'GoPay Coins') continue;



            // tentukan merchant berdasarkan nama
            $merchant = FinanceMerchant::where('work_space_id', $currentWorkspaceId)
            ->where('name', $t['nama'])->first();

            // jika merchant tidak ada, buat baru
            if (!$merchant) {
                $merchant = FinanceMerchant::create(['name' => $t['nama'], 'work_space_id' => $currentWorkspaceId]);
            }

            // simpan transaksi dengan merchant
            FinanceTransaction::create([
                'work_space_id' => $currentWorkspaceId,
                'finance_merchant_id' => $merchant->id,
                'finance_category_id' => $merchant->defaultCategory?->id,
                'transactionid' => $t['id_transaksi'],
                'amount' => $t['jumlah'],
                'transaction_date' => Carbon::createFromFormat('d/m/Y', $t['tanggal']),
                'description' => $t['metode']
            ]);
        }

        return redirect()->route('finance-transactions.index')->with('success', 'Finance Transaction imported successfully.');
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

        $currentWorkspaceId = session('active_workspace_id');

        $validatedData['work_space_id'] = $currentWorkspaceId;

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
