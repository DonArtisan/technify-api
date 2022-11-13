<?php

namespace App\GraphQL\Mutations;

use App\Http\Stats\UserStats;
use App\Models\User;
use Error;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Throwable;

class UserRegisterMutation extends BaseMutation
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function handle(mixed $root, array $args): array
    {
        try {
            $user = User::create(
                array_merge(
                    Arr::only(
                        $args['input'],
                        ['first_name', 'last_name', 'email']
                    ),
                    ['password' => Hash::make($args['input']['password'])],
                )
            );
            UserStats::increase(1);
        } catch (Throwable $error) {
            throw new Error($error);
        }

        return [
            'user' => $user,
            'userErrors' => [],
            'userToken' => $user->createToken('auth_token')->plainTextToken,
        ];
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', Password::min(4)->letters()->mixedCase()->numbers()],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.string' => 'First name must be string type.',
            'last_name.string' => 'Last name must be string type.',
            'email.email' => 'It is not an email',
        ];
    }
}
