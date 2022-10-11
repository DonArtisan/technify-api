<?php

namespace App\GraphQL\Mutations;

use Error;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\GraphQL\Mutations\BaseMutation;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
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

        } catch (Throwable $error) {

            throw new Error($error);
        }

        return [
            'userEdge' => [
                'cursor' => User::count(),
                'node' => $user->fresh(),
            ],
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
            'password' => ['required', 'min:2', 'max:20'],
        ];
    }
}
