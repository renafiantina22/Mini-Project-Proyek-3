<div class="card">
    <h3>Riwayat Perubahan Status</h3>

    <div class="report-list">
        @forelse ($report->logs->sortByDesc('changed_at') as $log)
            <div class="report-card">
                <div class="report-item-header">
                    <h3>
                        {{ ucwords(str_replace('_', ' ', $log->new_status)) }}
                    </h3>

                    <span class="text-muted">
                        {{ \Carbon\Carbon::parse($log->changed_at)->translatedFormat('d F Y, H:i') }}
                    </span>
                </div>

                <p>
                    <strong>Status sebelumnya:</strong>
                    {{ ucwords(str_replace('_', ' ', $log->old_status)) }}
                </p>

                <p>
                    <strong>Diubah oleh:</strong>
                    {{ $log->user->name ?? 'Sistem' }}
                </p>
            </div>
        @empty
            <p class="text-muted">
                Belum ada riwayat perubahan status.
            </p>
        @endforelse
    </div>
</div>