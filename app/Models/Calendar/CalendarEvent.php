<?php

namespace App\Models\Calendar;

use Illuminate\Database\Eloquent\Model;
use App\Models\WorkSpace;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;


class CalendarEvent extends Model
{
    use SoftDeletes;
    protected $table = 'calendar_events';
    protected $fillable = [
        'work_space_id',
        'title',
        'type',
        'start_at',
        'end_at',
        'description',
    ];

    public function structure()
    {
        return [
            'parent' => 'calendar',
            'table' => 'calendar-events',
            'title' => 'Calendar Event',
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
                    'name' => 'type',
                    'data' => 'type',
                    'display_value' => 'type',
                    'type' => 'enum',
                    'validation' => 'required|in:"Productive","Entertainment","Other"',
                    'label' => 'Type',
                    'options' => ['Productive', 'Entertainment', 'Other'],
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'start_at',
                    'data' => 'start_at',
                    'display_value' => 'start_at',
                    'type' => 'date',
                    'validation' => 'required|date',
                    'label' => 'Start',
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'end_at',
                    'data' => 'end_at',
                    'display_value' => 'end_at',
                    'type' => 'date',
                    'validation' => 'required|date',
                    'label' => 'End',
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
                $builder->where('calendar_events.work_space_id', session('active_workspace_id'));
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
