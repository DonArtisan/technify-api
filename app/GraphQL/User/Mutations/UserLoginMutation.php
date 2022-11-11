<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use Error;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Throwable;

class UserLoginMutation extends BaseMutation
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function handle(mixed $root, array $args): array
    {
        try {
            $user = User::whereEmail($args['input']['email'])->first();

            if  ( ! $user || ! Hash::check($args['input']['password'], $user->password)) {
                return [
                    'userErrors' => [
                        [
                            'field' => ['password'],
                            'message' => 'The password is incorrect',
                        ]
                    ],
                ];
            }
        } catch (Throwable $error) {
            throw new Error($error);
        }

        return [
            'userAuth' => $user,
            'userErrors' => [],
            'userToken' => $user->createToken('auth_token')->plainTextToken,
        ];
    }

    public function rules(): array
    {
        return [
            'email' => ['required', Rule::exists(User::class, 'email')],
            'password' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'email' => 'The email does not exists.',
            'password' => 'Wrong password.',
        ];
    }
}
