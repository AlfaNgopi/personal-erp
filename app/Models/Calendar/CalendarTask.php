<?php

namespace App\Models\Calendar;

use App\Models\WorkSpace;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;


class CalendarTask extends Model
{
    use SoftDeletes;
    protected $table = 'calendar_tasks';
    protected $fillable = [
        'work_space_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'completed_at',
    ];

    public function structure()
    {
        return [
            'parent' => 'calendar',
            'table' => 'calendar-tasks',
            'title' => 'Calendar Task',
            'table_has_actions' => true,
            'table_has_timestamps' => true,

            'columns' => [
                [
                    'name' => 'work_space_id',
                    'data' => 'work_space.name',
                    'display_value' => 'work_spaces.name',
                    'type' => 'select',
                    'validation' => 'nullable|exists:work_spaces,id',
                    'label' => 'Workspace',
                    'options' => WorkSpace::all()->pluck('name', 'id')->toArray(),
                    'required' => false,
                    'inTable' => false,
                    'inCreateForm' => false,
                    'inEditForm' => false,
                    'inShowPage' => false,
                ],
                [
                    'name' => 'title',
                    'data' => 'title',
                    'display_value' => 'title',
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
                    'name' => 'status',
                    'data' => 'status',
                    'display_value' => 'status',
                    'type' => 'enum',
                    'validation' => 'required|in:pending,todo,progress,completed,canceled',
                    'label' => 'Status',
                    'options' => ['pending', 'todo', 'progress', 'completed', 'canceled'],
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'priority',
                    'data' => 'priority',
                    'display_value' => 'priority',
                    'type' => 'enum',
                    'validation' => 'required|in:low,medium,high,',
                    'label' => 'Priority',
                    'options' => ['low', 'medium', 'high'],
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'due_date',
                    'data' => 'due_date',
                    'display_value' => 'due_date',
                    'type' => 'date',
                    'validation' => 'nullable|date',
                    'label' => 'Due',
                    'required' => false,
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
                    'validation' => 'nullable|string',
                    'label' => 'Description',
                    'required' => false,
                    'inTable' => false,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                
                
                
            ]
        ];
    }

    public function workSpace()
    {
        return $this->belongsTo(WorkSpace::class);
    }
    public function work_space()
    {
        return $this->belongsTo(WorkSpace::class);
    }

    protected static function booted()
    {
        // Otomatis tambahkan filter work_space_id setiap kali model dipanggil
        static::addGlobalScope('workspace', function (Builder $builder) {
            // Pastikan user sudah login dan memiliki workspace yang aktif
            if (auth()->check() && session()->has('active_workspace_id')) {
                $builder->where('calendar_tasks.work_space_id', session('active_workspace_id'));
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
