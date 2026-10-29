<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Sabre\VObject\Reader;
use Throwable;

class IcalendarImportService
{
    /**
     * Import events from an uploaded .ics file.
     */
    public function import(
        UploadedFile $file,
        int $workspaceId,
        int $createdBy,
        ?int $calendarImportId = null,
        string $defaultTimezone = 'Asia/Jakarta',
    ): array {
        $vcalendar = Reader::read(
            file_get_contents($file->getRealPath())
        );

        $imported = 0;
        $skipped = 0;

        DB::transaction(function () use (
            $vcalendar,
            $workspaceId,
            $createdBy,
            $calendarImportId,
            $defaultTimezone,
            &$imported,
            &$skipped,
        ) {
            foreach ($vcalendar->VEVENT as $event) {
                // DTSTART is required for a usable event.
                if (!isset($event->DTSTART)) {
                    $skipped++;
                    continue;
                }

                $startProperty = $event->DTSTART;
                $startDate = $startProperty->getDateTime();

                // Detect all-day events: DTSTART;VALUE=DATE:20261028
                $isAllDay = $startProperty->getValueType() === 'DATE';

                $endDate = isset($event->DTEND)
                    ? $event->DTEND->getDateTime()
                    : null;

                // Support events using DURATION instead of DTEND.
                if (!$endDate && isset($event->DURATION)) {
                    $endDate = clone $startDate;
                    $endDate->add(
                        $event->DURATION->getDateInterval()
                    );
                }

                // A missing end time defaults to the start time.
                $endDate ??= clone $startDate;

                // Use DTSTART's TZID when available.
                $timezone = $startProperty->parameters['TZID']
                    ? (string) $startProperty->parameters['TZID']
                    : $startDate->getTimezone()->getName();

                // Fall back if no usable timezone was provided.
                try {
                    new \DateTimeZone($timezone);
                } catch (Throwable $e) {
                    $timezone = $defaultTimezone;
                }

                $startAt = Carbon::instance($startDate)
                    ->setTimezone($timezone);

                $endAt = Carbon::instance($endDate)
                    ->setTimezone($timezone);

                $uid = isset($event->UID)
                    ? trim((string) $event->UID)
                    : null;

                $data = [
                    'title' => mb_substr(
                        isset($event->SUMMARY)
                            ? (string) $event->SUMMARY
                            : '(Untitled event)',
                        0,
                        255
                    ),

                    'description' => isset($event->DESCRIPTION)
                        ? (string) $event->DESCRIPTION
                        : null,

                    'location' => isset($event->LOCATION)
                        ? mb_substr(
                            (string) $event->LOCATION,
                            0,
                            255
                        )
                        : null,

                    'start_at' => $startAt->format('Y-m-d H:i:s'),
                    'end_at' => $endAt->format('Y-m-d H:i:s'),
                    'is_all_day' => $isAllDay,
                    'timezone' => $timezone,

                    'source' => 'ical',
                    'external_uid' => $uid,
                    'calendar_import_id' => $calendarImportId,
                    'created_by' => $createdBy,
                    'updated_at' => now(),
                ];

                if ($uid !== null && $uid !== '') {
                    // Re-importing the same event updates it instead
                    // of creating a duplicate.
                    $existing = DB::table('events')
                        ->where('workspace_id', $workspaceId)
                        ->where('external_uid', $uid)
                        ->first();

                    if ($existing) {
                        DB::table('events')
                            ->where('id', $existing->id)
                            ->update($data);
                    } else {
                        $data['workspace_id'] = $workspaceId;
                        $data['created_at'] = now();

                        DB::table('events')->insert($data);
                    }
                } else {
                    // No UID: cannot reliably deduplicate.
                    $data['workspace_id'] = $workspaceId;
                    $data['created_at'] = now();

                    DB::table('events')->insert($data);
                }

                $imported++;
            }
        });

        return [
            'imported' => $imported,
            'skipped' => $skipped,
        ];
    }
}