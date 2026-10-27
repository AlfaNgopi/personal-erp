<?php

namespace App\Models\Finance;
use App\Models\WorkSpace;
use App\Models\Finance\FinanceCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinanceTransaction extends Model
{
    use SoftDeletes;

    protected $table = 'finance_transactions';

    protected $fillable = [
        'work_space_id',
        'finance_merchant_id',
        'finance_category_id',
        'amount',
        'transaction_date',
        'description'
    ];
    
    protected $dates = [
        'transaction_date',
        'deleted_at'
    ];

    public function structure()
    {
        return [
            'parent' => 'finance',
            'routing' => 'finance-transactions',
            'table' => 'finance-transactions',
            'title' => 'Finance Transaction',
            'table_has_actions' => true,
            'table_has_timestamps' => true,

            'columns' => [
                [
                    'name' => 'work_space_id',
                    'data' => 'work_space.name',
                    'display_value' => 'work_spaces.name',
                    'type' => 'select',
                    'validation' => 'required|exists:work_spaces,id',
                    'label' => 'Work Space',
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'finance_merchant_id',
                    'data' => 'finance_merchant.name',
                    'display_value' => 'finance_merchants.name',
                    'type' => 'select',
                    'validation' => 'required|exists:finance_merchants,id',
                    'label' => 'Merchant',
                    'options' => FinanceMerchant::all()->pluck('name', 'id')->toArray(),
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                
                [
                    'name' => 'transaction_date',
                    'data' => 'transaction_date',
                    'display_value' => 'transaction_date',
                    'type' => 'date',
                    'validation' => 'required|date',
                    'label' => 'Date',
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'amount',
                    'data' => 'amount',
                    'display_value' => 'amount',
                    'type' => 'number',
                    'validation' => 'required|integer|min:0',
                    'label' => 'Amount',
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'finance_category_id',
                    'data' => 'finance_category.name',
                    'display_value' => 'finance_categories.name',
                    'type' => 'select',
                    'validation' => 'required|exists:finance_categories,id',
                    'label' => 'Finance Category',
                    'options' => FinanceCategory::all()->pluck('name', 'id')->toArray(),
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'description',
                    'data' => 'description',
                    'display_value' => 'description',
                    'type' => 'text',
                    'validation' => 'required|string',
                    'label' => 'Description',
                    'required' => false,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
            ]
        ];
    }

    protected static function booted()
    {
        // Otomatis tambahkan filter work_space_id setiap kali model dipanggil
        static::addGlobalScope('workspace', function (Builder $builder) {
            // Pastikan user sudah login dan memiliki workspace yang aktif
            if (auth()->check() && session()->has('active_workspace_id')) {
                $builder->where('work_space_id', session('active_workspace_id'));
            }
        });
    }

    public function workspace()
    {
        return $this->belongsTo(WorkSpace::class, 'work_space_id');
    }
    public function work_space()
    {
        return $this->belongsTo(WorkSpace::class, 'work_space_id');
    }

    public function financeCategory()
    {
        return $this->belongsTo(FinanceCategory::class, 'finance_category_id');
    }
    public function finance_category()
    {
        return $this->belongsTo(FinanceCategory::class, 'finance_category_id');
    }

    public function financeMerchant()
    {
        return $this->belongsTo(FinanceMerchant::class, 'finance_merchant_id');
    }
    public function finance_merchant()
    {
        return $this->belongsTo(FinanceMerchant::class, 'finance_merchant_id');
    }

    public function getValidationRules(): array
    {
        $structure = $this->structure();

        $rules = [];

        foreach ($structure['columns'] as $column) {
            if (!empty($column['validation'])) {
                $rules[$column['name']] = $column['validation'];
            }
        }

        return $rules;
    }
}
