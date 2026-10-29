<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Models\Calendar\CalendarEvent;
use App\Models\Calendar\CalendarTask;
use App\Models\Calendar\CalendarReview;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarDashboardController extends Controller
{
    

    public function index()
    {

        $start = now()->startOfMonth();
        $end = now()->endOfMonth();

        $eventCount = CalendarEvent::whereBetween('start_at', [$start, $end])
            ->count();

        $pendingTaskCount = CalendarTask::where('due_date', '<=', $end->toDateString())
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhere('status', '!=', 'completed');
            })
            ->count();

        $completedTaskCount = CalendarTask::where('status', 'completed')
            ->whereBetween('due_date', [
                $start->toDateString(),
                $end->toDateString(),
            ])
            ->count();

        $reviewCount = CalendarReview::whereBetween('review_date', [
                $start->toDateString(),
                $end->toDateString(),
            ])
            ->count();

        return view('calendar.dashboard', compact(
            'eventCount',
            'pendingTaskCount',
            'completedTaskCount',
            'reviewCount'
        ));
    }

    public function data(Request $request)
    {
        $request->validate([
            'month' => ['required', 'date_format:Y-m'],
        ]);


        $month = Carbon::createFromFormat(
            '!Y-m',
            $request->month
        );

        $start = $month->copy()->startOfMonth();
        $end = $month->copy()->endOfMonth();

        $events = CalendarEvent::whereBetween('start_at', [$start, $end])
            ->get()
            ->map(fn ($event) => [
                'date' => Carbon::parse($event->start_at)->toDateString(),
                'title' => $event->title,
            ])
            ->groupBy('date');

        $tasks = CalendarTask::whereBetween('due_date', [
                $start->toDateString(),
                $end->toDateString(),
            ])
            ->get()
            ->map(fn ($task) => [
                'date' => Carbon::parse($task->due_date)->toDateString(),
                'title' => $task->title,
            ])
            ->groupBy('date');

        $reviews = CalendarReview::whereBetween('review_date', [
                $start->toDateString(),
                $end->toDateString(),
            ])
            ->get()
            ->map(fn ($review) => [
                'date' => Carbon::parse($review->review_date)
                    ->toDateString(),
                'title' => 'Daily Review',
            ])
            ->groupBy('date');

        $result = [];

        foreach ($start->toPeriod($end) as $date) {
            $key = $date->toDateString();

            $result[$key] = [
                'events' => $events->get($key, collect())->values(),
                'tasks' => $tasks->get($key, collect())->values(),
                'reviews' => $reviews->get($key, collect())->values(),
            ];
        }

        return response()->json($result);
    }

    public function day(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date_format:Y-m-d'],
        ]);

        $date = $validated['date'];

        $events = CalendarEvent::whereDate('start_at', '<=', $date)
            ->whereDate('end_at', '>=', $date)
            ->orderBy('start_at')
            ->get()
            ->map(fn ($event) => [
                'title' => $event->title,
                'type' => $event->type,
                'time' => Carbon::parse($event->start_at)->format('H:i')
                    . ' - '
                    . Carbon::parse($event->end_at)->format('H:i'),
                'description' => $event->description,
            ]);

        $tasks = CalendarTask::whereDate('due_date', $date)
            ->orderBy('priority')
            ->get()
            ->map(fn ($task) => [
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status,
                'priority' => $task->priority,
            ]);

        $reviews = CalendarReview::whereDate('review_date', $date)
            ->get()
            ->map(fn ($review) => [
                'title' => 'Daily Review',
                'rating' => $review->rating,
                'notes' => $review->notes,
            ]);

        return response()->json(compact(
            'events',
            'tasks',
            'reviews'
        ));
    }
}
