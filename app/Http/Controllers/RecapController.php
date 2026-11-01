<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Report;
use Illuminate\Http\Request;

class RecapController extends Controller
{
    public function index(Request $request)
    {
        // Validasi filter wilayah
        $validated = $request->validate([
            'area_id' => ['nullable', 'exists:areas,id'],
        ]);

        $selectedArea = $validated['area_id'] ?? null;

        // Query seluruh laporan
        $reportQuery = Report::query();

        if ($selectedArea) {
            $reportQuery->where('area_id', $selectedArea);
        }

        // Informasi ringkasan
        $totalReports = (clone $reportQuery)->count();

        $activeReports = (clone $reportQuery)
            ->where('status', '!=', 'selesai')
            ->count();

        $completedReports = (clone $reportQuery)
            ->where('status', 'selesai')
            ->count();

        // Rekap berdasarkan wilayah
        $areaRecaps = Area::withCount([
            'reports as total_reports',

            'reports as active_reports' => function ($query) {
                $query->where('status', '!=', 'selesai');
            },

            'reports as completed_reports' => function ($query) {
                $query->where('status', 'selesai');
            },
        ])
        ->when($selectedArea, function ($query) use ($selectedArea) {
            $query->where('id', $selectedArea);
        })
        ->orderBy('name')
        ->get();

        // Daftar wilayah untuk filter
        $areas = Area::orderBy('name')->get();

        return view('recaps.index', compact(
            'totalReports',
            'activeReports',
            'completedReports',
            'areaRecaps',
            'areas',
            'selectedArea'
        ));
    }
}
