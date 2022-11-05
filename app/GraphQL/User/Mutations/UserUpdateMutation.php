<?php

namespace App\GraphQL\User\Mutations;

use App\GraphQL\Mutations\BaseMutation;
use App\Models\User;
use Error;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Throwable;

class UserUpdateMutation extends BaseMutation
{
    public function handle(mixed $root, array $args): array
    {
        try {
            /** @var User $user */
            $user = User::find($args['input']['id']);

            $user->update(
                array_merge(
                    Arr::only(
                        $args['input'],
                        ['first_name', 'last_name', 'email', 'isBlocked']
                    ),
                    ['password' => Hash::make($args['input']['password'])],
                )
            );
        } catch (Throwable $error) {
            throw new Error($error);
        }

        return [
            'user' => $user,
            'userErrors' => [],
        ];
    }

    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'string'],
            'last_name' => ['sometimes', 'string'],
            'email' => ['sometimes', 'email'],
            'password' => ['sometimes', Password::min(4)->letters()->mixedCase()->numbers()],
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
