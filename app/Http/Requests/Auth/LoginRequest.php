<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $identifier = trim((string) $this->input('email', ''));
        $identifierLower = Str::lower($identifier);

        $user = User::query()
            ->where(function ($q) use ($identifier, $identifierLower) {
                $q->where('email', $identifier)
                    ->orWhere('telephone', $identifier)
                    ->orWhere(function ($q2) use ($identifierLower) {
                        $q2->whereIn('role', ['admin', 'direction'])
                            ->whereRaw('LOWER(TRIM(name)) = ?', [$identifierLower]);
                    });
            })
            ->first();

        if (! $user || ! Hash::check((string) $this->input('password', ''), $user->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        Auth::login($user, $this->boolean('remember'));
        if ($user && in_array($user->role, ['commercial', 'commercial_telephonique', 'direction'], true) && ! $user->actif) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Votre compte est désactivé. Contactez l\'administration.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        $login = Str::lower(trim((string) $this->input('email', '')));

        return Str::transliterate($login.'|'.$this->ip());
    }
}
