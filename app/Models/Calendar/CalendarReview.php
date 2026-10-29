<?php

namespace App\Models\Calendar;

use Illuminate\Database\Eloquent\Model;
use App\Models\WorkSpace;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;


class CalendarReview extends Model
{
    use SoftDeletes;
    protected $table = 'calendar_reviews';
    protected $fillable = [
        'work_space_id',
        'review_date',
        'rating',
        'notes',
    ];

    public function structure()
    {
        return [
            'parent' => 'calendar',
            'table' => 'calendar-reviews',
            'title' => 'Calendar Review',
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
                    'name' => 'review_date',
                    'data' => 'review_date',
                    'display_value' => 'review_date',
                    'type' => 'date',
                    'validation' => 'required|date',
                    'label' => 'Review Date',
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'rating',
                    'data' => 'rating',
                    'display_value' => 'rating',
                    'type' => 'enum',
                    'validation' => 'required|in:1,2,3,4,5',
                    'label' => 'Rating',
                    'options' => [1,2,3,4,5],
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                
                [
                    'name' => 'notes',
                    'data' => 'notes',
                    'display_value' => 'notes',
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
                $builder->where('calendar_reviews.work_space_id', session('active_workspace_id'));
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
