<?php

namespace App\Models\Finance;

use App\Models\WorkSpace;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class FinanceCategory extends Model
{
    use SoftDeletes;
    protected $table = 'finance_categories';
    protected $fillable = [
        'work_space_id',
        'name',
        'type',
        'icon',
        'color'
    ];

    public function structure()
    {
        return [
            'parent' => 'finance',
            'routing' => 'finance-categories',
            'table' => 'finance-categories',
            'title' => 'Finance Category',
            'table_has_actions' => true,
            'table_has_timestamps' => true,

            'columns' => [
                [
                    'name' => 'work_space_id',
                    'data' => 'work_space.name',
                    'display_value' => 'work_spaces.name',
                    'type' => 'select',
                    'validation' => 'nullable|exists:work_spaces,id',
                    'label' => 'Work Space',
                    'options' => WorkSpace::all()->pluck('name', 'id')->toArray(),
                    'required' => false,
                    'inTable' => false,
                    'inCreateForm' => false,
                    'inEditForm' => false,
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
                    'name' => 'type',
                    'data' => 'type',
                    'display_value' => 'type',
                    'type' => 'enum',
                    'validation' => 'required|in:income,expense',
                    'label' => 'Type',
                    'options' => [
                        'income' => 'Income',
                        'expense' => 'Expense',
                    ],
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'icon',
                    'type' => 'text',
                    'data' => 'icon',
                    'display_value' => 'icon',
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
                    'data' => 'color',
                    'display_value' => 'color',
                    'validation' => 'nullable|string|max:255',
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

    protected static function booted()
    {
        // Otomatis tambahkan filter work_space_id setiap kali model dipanggil
        static::addGlobalScope('workspace', function (Builder $builder) {
            // Pastikan user sudah login dan memiliki workspace yang aktif
            if (auth()->check() && session()->has('active_workspace_id')) {
                $builder->where('finance_categories.work_space_id', session('active_workspace_id'));
            }
        });
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
