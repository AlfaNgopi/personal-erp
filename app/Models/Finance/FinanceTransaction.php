<?php

namespace App\Models\Finance;
use App\Models\WorkSpace;
use App\Models\Finance\FinanceCategory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinanceTransaction extends Model
{
    use SoftDeletes;

    protected $table = 'finance_transactions';

    protected $fillable = [
        'work_space_id',
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
            'table' => 'finance-transactions',
            'title' => 'Finance Transaction',
            'table_has_actions' => true,
            'table_has_timestamps' => true,

            'columns' => [
                [
                    'name' => 'work_space_id',
                    'type' => 'select',
                    'display_value' => 'work_spaces.name',
                    'validation' => 'required|exists:work_spaces,id',
                    'label' => 'Work Space',
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                
                [
                    'name' => 'transaction_date',
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
