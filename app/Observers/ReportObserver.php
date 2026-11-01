<?php

namespace App\Observers;

use App\Models\Report;
use App\Models\ReportLog;

class ReportObserver
{
    /**
     * Handle the Report "updated" event.
     */
    public function updated(Report $report): void
    {
        // Periksa apakah status laporan berubah
        if ($report->wasChanged('status')) {

            ReportLog::create([
                'report_id' => $report->id,
                'user_id' => auth()->id(),
                'old_status' => $report->getOriginal('status'),
                'new_status' => $report->status,
                'changed_at' => now(),
            ]);
        }
    }
}
