<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class WorkSpace extends Model
{
    use SoftDeletes;

    protected static function booted(): void
    {
        
    }

    protected $table = 'work_spaces';
    protected $fillable = [
        'name',
        'base_currency',
        'timezone',
        'status'
    ];

    public function structure()
    {
        return [
            'parent' => null,
            'table' => 'work-spaces',
            'title' => 'Work Space',
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
                    'name' => 'base_currency',
                    'data' => 'base_currency',
                    'display_value' => 'base_currency',
                    'type' => 'string',
                    'validation' => 'required|string|max:255',
                    'label' => 'Base Currency',
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'timezone',
                    'data' => 'timezone',
                    'display_value' => 'timezone',
                    'type' => 'string',
                    'validation' => 'required|string|max:255',
                    'label' => 'Timezone',
                    'required' => false,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'status',
                    'data' => 'status',
                    'display_value' => 'status',
                    'type' => 'string',
                    'validation' => 'required|string|max:255',
                    'label' => 'Status',
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'slug',
                    'data' => 'slug',
                    'display_value' => 'slug',
                    'type' => 'string',
                    'validation' => 'required|string|max:255',
                    'label' => 'Slug',
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ]
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

    public function members()
    {
        return $this->hasMany(WorkSpaceMember::class);
    }


    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'work_space_members',
            'work_space_id',
            'user_id'
        )->withPivot('role')
            ->withTimestamps();
    }
}
