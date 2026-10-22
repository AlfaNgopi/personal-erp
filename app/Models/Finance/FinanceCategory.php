<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinanceCategory extends Model
{
    use SoftDeletes;
    protected $table = 'finance_categories';
    protected $fillable = [
        'name',
        'type',
        'icon',
        'color'
    ];

    public function structure()
    {
        return [
            'parent' => 'finance',
            'table' => 'finance_categories',
            'title' => 'Finance Category',
            'table_has_actions' => true,
            'table_has_timestamps' => true,

            'columns' => [
                [
                    'name' => 'name',
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
                    'name' => 'type',
                    'type' => 'string',
                    'validation' => 'required|string|max:255',
                    'label' => 'Type',
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'icon',
                    'type' => 'text',
                    'validation' => 'nullable',
                    'label' => 'Icon',
                    'required' => false,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'color',
                    'type' => 'string',
                    'validation' => 'required|string|max:255',
                    'label' => 'Color',
                    'required' => false,
                    'inTable' => false,
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
