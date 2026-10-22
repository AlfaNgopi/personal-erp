<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


// this is not real Model, just a template structure for copy paste purposes
class TemplateStud extends Model
{
    use SoftDeletes;
    protected $table = 'template_studs';
    protected $fillable = [
        'name',
        'description',
        'date',
        'number',
        'enum',
        'foreign_id'
    ];

    public function structure()
    {
        return [
            'parent' => null,
            'table' => 'template_studs',
            'title' => 'Template Stud',
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
                    'name' => 'description',
                    'type' => 'text',
                    'validation' => 'required|string|max:255',
                    'label' => 'Description',
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'date',
                    'type' => 'date',
                    'validation' => 'required|date',
                    'label' => 'Date',
                    'required' => false,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'number',
                    'type' => 'number',
                    'validation' => 'required|integer|min:0',
                    'label' => 'Number',
                    'required' => false,
                    'inTable' => false,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'enum',
                    'type' => 'enum',
                    'validation' => 'nullable|in:option1,option2,option3',
                    'label' => 'Enum',
                    'required' => false,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'foreign_id',
                    'display_value' => 'foreigns.name',
                    'type' => 'select',
                    'validation' => 'required|exists:foreigns,id',
                    'label' => 'Foreign',
                    'options' => TemplateStud::all()->pluck('name', 'id')->toArray(),
                    'required' => true,
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
