<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FollowUpController extends Controller
{
    // Menampilkan laporan yang membutuhkan tindak lanjut
    public function index()
    {
        $reports = Report::with(['user', 'area'])
            ->whereIn('status', [
                'perlu_tindak_lanjut',
                'sedang_ditindaklanjuti'
            ])
            ->latest()
            ->paginate(10);

        return view('follow-ups.index', compact('reports'));
    }

    // Menampilkan detail tindak lanjut laporan
    public function show(Report $report)
    {
        if (!in_array($report->status, [
            'perlu_tindak_lanjut',
            'sedang_ditindaklanjuti',
            'selesai'
        ], true)) {
            abort(404, 'Laporan tidak tersedia untuk tindak lanjut.');
        }

        $report->load([
            'user',
            'area',
            'reviews.reviewer',
            'followUps.coordinator'
        ]);

        return view('follow-ups.show', compact('report'));
    }

    
    public function forward(Request $request, Report $report)
    {
        // Laporan hanya dapat diteruskan jika membutuhkan
        // tindak lanjut atau sedang ditindaklanjuti.
        if (!in_array($report->status, [
            'perlu_tindak_lanjut',
            'sedang_ditindaklanjuti'
        ], true)) {
            abort(403, 'Laporan tidak dapat diteruskan.');
        }

        $validated = $request->validate([
            'destination' => [
                'required',
                'in:BPBD,Damkar,Pemerintah Desa,Pengelola Wilayah'
            ],
            'notes' => 'required|string',
        ]);

        // Periksa apakah tujuan sudah pernah menerima laporan ini
        $alreadyForwarded = $report->followUps()
            ->where('destination', $validated['destination'])
            ->exists();

        if ($alreadyForwarded) {
            return back()
                ->withErrors([
                    'destination' => 'Laporan ini sudah pernah diteruskan ke tujuan tersebut.'
                ])
                ->withInput();
        }

        DB::transaction(function () use ($report, $validated) {

            // Simpan penerusan baru
            $report->followUps()->create([
                'coordinator_id' => auth()->id(),
                'destination' => $validated['destination'],
                'notes' => $validated['notes'],
                'forwarded_at' => now(),
            ]);

            // Ubah status hanya pada penerusan pertama
            if ($report->status === 'perlu_tindak_lanjut') {


                $report->update([
                    'status' => 'sedang_ditindaklanjuti',
                ]);
            }
        });

        return redirect()
            ->route('follow-ups.show', $report->id)
            ->with('success', 'Penerusan laporan berhasil disimpan.');
    }

    public function complete(Report $report)
    {
        if ($report->status !== 'sedang_ditindaklanjuti') {
            abort(403, 'Laporan belum dapat diselesaikan.');
        }

        DB::transaction(function () use ($report) {

            $report->update([
                'status' => 'selesai',
            ]);

        });

        return redirect()
            ->route('follow-ups.show', $report->id)
            ->with('success', 'Tindak lanjut laporan berhasil diselesaikan.');
    }


}