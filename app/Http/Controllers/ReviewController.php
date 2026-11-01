<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Menampilkan daftar laporan yang perlu ditinjau
    public function index()
    {
        $reports = Report::with(['user', 'area'])
            ->latest()
            ->paginate(10);

        return view('reviews.index', compact('reports'));
    }

    // Menampilkan detail laporan untuk ditinjau
    public function show(Report $report)
    {
        $report->load([
            'user',
            'area',
            'reviews.reviewer',
            'followUps.coordinator',
            'logs.user'
        ]);

        return view('reviews.show', compact('report'));
    }

    // Memulai proses peninjauan laporan
    public function startReview(Report $report)
    {
        // Memastikan laporan masih menunggu peninjauan
        if ($report->status !== 'menunggu_peninjauan') {
            abort(403, 'Laporan ini tidak dapat mulai ditinjau.');
        }

        // Mengubah status laporan
        $oldStatus = $report->status;

        $report->update([
            'status' => 'sedang_ditinjau',
        ]);


        return redirect()
            ->route('reviews.show', $report->id)
            ->with('success', 'Peninjauan laporan berhasil dimulai.');
    }

    // Menyimpan catatan hasil peninjauan
    public function store(Request $request, Report $report)
    {
        // Memastikan laporan sedang ditinjau
        if ($report->status !== 'sedang_ditinjau') {
            abort(403, 'Laporan belum dapat diberikan catatan peninjauan.');
        }

        // Validasi catatan
        $validated = $request->validate([
            'review_note' => ['required', 'string'],
        ], [
            'review_note.required' => 'Catatan peninjauan wajib diisi.',
        ]);

        // Menyimpan catatan peninjauan
        $report->reviews()->create([
            'reviewer_id' => auth()->id(),
            'review_note' => $validated['review_note'],
        ]);

        return redirect()
            ->route('reviews.show', $report->id)
            ->with('success', 'Catatan peninjauan berhasil disimpan.');
    }

    // Menyimpan keputusan hasil peninjauan
    public function decide(Request $request, Report $report)
    {
        // Memastikan laporan sedang ditinjau
        if ($report->status !== 'sedang_ditinjau') {
            abort(403, 'Laporan tidak dapat diproses.');
        }

        // Memastikan sudah ada catatan peninjauan
        if (!$report->reviews()->exists()) {
            abort(403, 'Isi catatan peninjauan terlebih dahulu.');
        }

        // Validasi keputusan
        $validated = $request->validate([
            'status' => [
                'required',
                'in:perlu_tindak_lanjut,selesai'
            ],
        ], [
            'status.required' => 'Keputusan peninjauan wajib dipilih.',
            'status.in' => 'Keputusan peninjauan tidak valid.',
        ]);

        $oldStatus = $report->status;
        $newStatus = $validated['status'];

        // Mengubah status laporan
        $report->update([
            'status' => $newStatus,
        ]);

        return redirect()
            ->route('reviews.show', $report->id)
            ->with('success', 'Keputusan peninjauan berhasil disimpan.');
    }
}