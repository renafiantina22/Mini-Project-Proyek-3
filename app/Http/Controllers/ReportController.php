<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Area;
use Illuminate\Http\Request;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with(['user', 'area'])
            ->latest();

        if ($request->user()->role === 'pelapor') {
            $query->where('user_id', $request->user()->id);
        }

        $reports = $query->paginate(10);

        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        $areas = Area::orderBy('name')->get();
        return view('reports.create', compact('areas'));
    }

    public function store(StoreReportRequest $request)
    {
        $validated = $request->validated();

        $evidencePath = null;

        if ($request->hasFile('evidence')) {
            $evidencePath = $request->file('evidence')
                ->store('reports/evidence', 'public');
        }

        $request->user()->reports()->create([
            'area_id' => $validated['area_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'incident_time' => $validated['incident_time'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'condition_level' => $validated['condition_level'],
            'evidence_path' => $evidencePath,
            'status' => 'menunggu_peninjauan',
        ]);

        return redirect()
            ->route('reports.index')
            ->with('success', 'Laporan berhasil dikirim.');
    }

    public function show(Report $report)
    {
        $user = auth()->user();

        if (
            $user->role === 'pelapor'
            && $report->user_id !== $user->id
        ) {
            abort(403, 'Kamu tidak memiliki akses ke laporan ini.');
        }

        $report->load(['user', 'area']);

        return view('reports.show', compact('report'));
    }

    public function edit(Report $report)
    {
        $user = auth()->user();

        // Memastikan laporan milik pelapor yang sedang login
        if ($report->user_id !== $user->id) {
            abort(403, 'Kamu tidak memiliki akses ke laporan ini.');
        }

        // Memastikan laporan masih menunggu peninjauan
        if ($report->status !== 'menunggu_peninjauan') {
            abort(403, 'Laporan yang sudah ditinjau tidak dapat diedit.');
        }

        $areas = Area::orderBy('name')->get();

        return view('reports.edit', compact('report', 'areas'));
    }

    public function update(UpdateReportRequest $request, Report $report)
    {
        $user = auth()->user();

        // Memastikan laporan milik pelapor yang sedang login
        if ($report->user_id !== $user->id) {
            abort(403, 'Kamu tidak memiliki akses ke laporan ini.');
        }

        // Memastikan laporan masih menunggu peninjauan
        if ($report->status !== 'menunggu_peninjauan') {
            abort(403, 'Laporan yang sudah ditinjau tidak dapat diedit.');
        }

        $validated = $request->validated();

        // Mempertahankan bukti lama jika tidak mengunggah bukti baru
        $evidencePath = $report->evidence_path;

        if ($request->hasFile('evidence')) {
            if ($evidencePath) {
                Storage::disk('public')->delete($evidencePath);
            }

            $evidencePath = $request->file('evidence')
                ->store('reports/evidence', 'public');
        }

        $report->update([
            'area_id' => $validated['area_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'incident_time' => $validated['incident_time'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'condition_level' => $validated['condition_level'],
            'evidence_path' => $evidencePath,
        ]);

        return redirect()
            ->route('reports.show', $report->id)
            ->with('success', 'Laporan berhasil diperbarui.');
    }
}