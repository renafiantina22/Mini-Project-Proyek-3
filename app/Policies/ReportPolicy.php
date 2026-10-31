<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    /**
     * Menentukan siapa yang boleh melihat daftar laporan. */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [
            'pelapor',
            'relawan',
            'koordinator',
        ]);
    }

    /**
     * Menentukan siapa yang boleh melihat detail laporan.
     */
    public function view(User $user, Report $report): bool
    {
        // Relawan dan koordinator dapat melihat semua laporan.
        if (in_array($user->role, ['relawan', 'koordinator'])) {
            return true;
        }

        // Pelapor hanya dapat melihat laporan miliknya.
        return $user->role === 'pelapor'
            && $report->user_id === $user->id;
    }

    /**
     * Hanya pelapor yang boleh membuat laporan.
     */
    public function create(User $user): bool
    {
        return $user->role === 'pelapor';
    }

    /**
     * Hanya pemilik laporan yang boleh mengedit,
     * selama status masih menunggu peninjauan.
     */
    public function update(User $user, Report $report): bool
    {
        return $user->role === 'pelapor'
            && $report->user_id === $user->id
            && $report->status === 'menunggu_peninjauan';
    }

    /**
     * Penghapusan laporan tidak diperbolehkan.
     */
    public function delete(User $user, Report $report): bool
    {
        return false;
    }
}