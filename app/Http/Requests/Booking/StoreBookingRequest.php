<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $minNoticeHours = (int) (settings('min_booking_hours_notice') ?? 2);
        $maxDaysAhead   = (int) (settings('max_booking_days_ahead')   ?? 90);

        return [
            'title'                    => ['required', 'string', 'max:200'],
            'notes'                    => ['nullable', 'string', 'max:1000'],

            'items'                    => ['required', 'array', 'min:1'],
            'items.*.resource_id'      => ['required', 'integer', 'exists:resources,id'],
            'items.*.start_datetime'   => [
                'required', 'date',
                "after:+{$minNoticeHours} hours",
                "before:+{$maxDaysAhead} days",
            ],
            'items.*.end_datetime'     => ['required', 'date', 'after:items.*.start_datetime'],
            'items.*.quantity'         => ['nullable', 'integer', 'min:1', 'max:999'],
            'items.*.notes'            => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'                  => 'Judul booking wajib diisi.',
            'items.required'                  => 'Minimal satu resource harus dipilih.',
            'items.min'                       => 'Minimal satu resource harus dipilih.',
            'items.*.resource_id.required'    => 'Resource wajib dipilih.',
            'items.*.resource_id.exists'      => 'Resource tidak valid.',
            'items.*.start_datetime.required' => 'Waktu mulai wajib diisi.',
            'items.*.start_datetime.after'    => 'Waktu mulai minimal :value dari sekarang.',
            'items.*.start_datetime.before'   => 'Waktu mulai maksimal :value ke depan.',
            'items.*.end_datetime.required'   => 'Waktu selesai wajib diisi.',
            'items.*.end_datetime.after'      => 'Waktu selesai harus setelah waktu mulai.',
            'items.*.quantity.min'            => 'Jumlah minimal 1.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Pastikan quantity default 1 jika tidak diisi
        if ($this->has('items')) {
            $items = array_map(function ($item) {
                $item['quantity'] = $item['quantity'] ?? 1;
                return $item;
            }, $this->items);

            $this->merge(['items' => $items]);
        }
    }
}