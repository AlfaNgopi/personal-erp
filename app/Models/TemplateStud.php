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
            'table' => 'template-studs',
            'title' => 'Template Stud',
            'table_has_actions' => true,
            'table_has_timestamps' => true,

            'columns' => [
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
                    'name' => 'description',
                    'data' => 'description',
                    'display_value' => 'description',
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
                    'data' => 'date',
                    'display_value' => 'date',
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
                    'data' => 'number',
                    'display_value' => 'number',
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
                    'data' => 'enum',
                    'display_value' => 'enum',
                    'type' => 'enum',
                    'validation' => 'nullable|in:option1,option2,option3',
                    'label' => 'Enum',
                    'options' => ['option1', 'option2', 'option3'],
                    'required' => false,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'checkbox',
                    'data' => 'checkbox',
                    'display_value' => 'checkbox',
                    'type' => 'checkbox',
                    'validation' => 'nullable|boolean',
                    'label' => 'checkbox',
                    'required' => false,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'foreign_id',
                    'data' => 'foreign.name',
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
