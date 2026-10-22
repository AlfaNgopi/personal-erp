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
        'enum'
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
                    'validation' => 'required|in:option1,option2,option3',
                    'label' => 'Enum',
                    'required' => false,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ]
            ]
        ];
    }


}
