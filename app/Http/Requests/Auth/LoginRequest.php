<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Ubah 'email' menjadi 'identity' (inputan user)
            'identity' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $input = $this->input('identity');
        $password = $this->input('password');

        // LOGIKA UTAMA: Cek apakah input masuk ke kolom NIM atau NIK
        // Kita coba login menggunakan NIM dulu
        $loginSuccess = Auth::attempt(['nim' => $input, 'password' => $password], $this->boolean('remember'));

        // Jika gagal pakai NIM, coba login pakai NIK
        if (! $loginSuccess) {
            $loginSuccess = Auth::attempt(['nik' => $input, 'password' => $password], $this->boolean('remember'));
        }

        if (! $loginSuccess) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'identity' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'identity' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('identity')).'|'.$this->ip());
    }
}