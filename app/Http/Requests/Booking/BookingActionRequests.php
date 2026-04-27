<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

// ──────────────────────────────────────────────────────────────────────────────
// Update Booking Request
// ──────────────────────────────────────────────────────────────────────────────

class UpdateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $booking = $this->route('booking');
        $user    = Auth::user();

        // Admin/staff bisa update semua, user hanya miliknya sendiri
        if (in_array($user->role->name, ['admin', 'staff'])) {
            return true;
        }

        return $booking && $booking->user_id === $user->id;
    }

    public function rules(): array
    {
        return [
            'title'                    => ['required', 'string', 'max:200'],
            'notes'                    => ['nullable', 'string', 'max:1000'],
            'items'                    => ['required', 'array', 'min:1'],
            'items.*.resource_id'      => ['required', 'integer', 'exists:resources,id'],
            'items.*.start_datetime'   => ['required', 'date'],
            'items.*.end_datetime'     => ['required', 'date', 'after:items.*.start_datetime'],
            'items.*.quantity'         => ['nullable', 'integer', 'min:1'],
            'items.*.notes'            => ['nullable', 'string', 'max:500'],
        ];
    }
}


// ──────────────────────────────────────────────────────────────────────────────
// Approve Booking Request
// ──────────────────────────────────────────────────────────────────────────────

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
class ApproveBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array(Auth::user()?->role?->name, ['admin', 'staff']);
    }

    public function rules(): array
    {
        return [
            'reason' => ['nullable', 'string', 'max:500'],
        ];
    }
}


// ──────────────────────────────────────────────────────────────────────────────
// Reject Booking Request
// ──────────────────────────────────────────────────────────────────────────────

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
class RejectBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array(Auth::user()?->role?->name, ['admin', 'staff']);
    }

    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'reason.required' => 'Alasan penolakan wajib diisi.',
        ];
    }
}


// ──────────────────────────────────────────────────────────────────────────────
// Cancel Booking Request
// ──────────────────────────────────────────────────────────────────────────────

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
class CancelBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'reason.required' => 'Alasan pembatalan wajib diisi.',
        ];
    }
}