<?php

namespace App\Models\Finance;

use App\Models\WorkSpace;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinanceMerchant extends Model
{
    use SoftDeletes;
    protected $table = 'finance_merchants';
    protected $fillable = [
        'work_space_id',
        'name',
        'default_category_id',
    ];

    public function structure()
    {
        return [
            'parent' => 'finance',
            'table' => 'finance-merchants',
            'title' => 'Finance Merchant',
            'table_has_actions' => true,
            'table_has_timestamps' => true,

            'columns' => [
                [
                    'name' => 'work_space_id',
                    'data' => 'work_space.name',
                    'display_value' => 'work_spaces.name',
                    'type' => 'string',
                    'validation' => 'required|exists:work_spaces,id',
                    'label' => 'Work Space',
                    'options' => WorkSpace::all()->pluck('name', 'id')->toArray(),
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'name',
                    'data' => 'name',
                    'display_value' => 'name',
                    'type' => 'string',
                    'validation' => 'required|string|max:255',
                    'label' => 'Name',
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                
                [
                    'name' => 'default_category_id',
                    'data' => 'default_category.name',
                    'display_value' => 'default_categories.name',
                    'type' => 'select',
                    'validation' => 'required|exists:finance_categories,id',
                    'label' => 'Category',
                    'options' => FinanceCategory::all()->pluck('name', 'id')->toArray(),
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
            ]
        ];
    }

    public function defaultCategory()
    {
        return $this->belongsTo(FinanceCategory::class, 'default_category_id');
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
