<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'pelapor';
    }

    public function rules(): array
    {
        return [
            'area_id' => ['required', 'exists:areas,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'incident_time' => ['required', 'date'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'condition_level' => ['required', 'in:rendah,sedang,tinggi'],
            'evidence' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'area_id.required' => 'Wilayah harus dipilih.',
            'area_id.exists' => 'Wilayah yang dipilih tidak valid.',
            'title.required' => 'Judul laporan wajib diisi.',
            'description.required' => 'Deskripsi kejadian wajib diisi.',
            'incident_time.required' => 'Waktu kejadian wajib diisi.',
            'latitude.between' => 'Latitude harus berada antara -90 hingga 90.',
            'longitude.between' => 'Longitude harus berada antara -180 hingga 180.',
            'condition_level.in' => 'Tingkat kondisi harus rendah, sedang, atau tinggi.',
            'evidence.mimes' => 'Bukti harus berupa JPG, JPEG, PNG, atau PDF.',
            'evidence.max' => 'Ukuran bukti maksimal 2 MB.',
        ];
    }
}