<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkSpaceMember extends Model
{
    use SoftDeletes;
    protected $table = 'work_space_members';
    protected $fillable = [
        'work_space_id',
        'user_id',
        'role',
        'status'
    ];

    public function structure()
    {
        return [
            'parent' => 'work_spaces',
            'table' => 'work_space_members',
            'title' => 'Work Space Member',
            'table_has_actions' => true,
            'table_has_timestamps' => true,

            'columns' => [
                [
                    'name' => 'work_space_id',
                    'display_value' => 'work_spaces.name',
                    'type' => 'select',
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
                    'name' => 'user_id',
                    'type' => 'select',
                    'display_value' => 'users.name',
                    'validation' => 'required|exists:users,id',
                    'label' => 'User',
                    'options' => User::all()->pluck('name', 'id')->toArray(),
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'role',
                    'type' => 'string',
                    'validation' => 'required|string|max:255',
                    'label' => 'Role',
                    'required' => false,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'status',
                    'type' => 'string',
                    'validation' => 'required|string|max:255',
                    'label' => 'Status',
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
