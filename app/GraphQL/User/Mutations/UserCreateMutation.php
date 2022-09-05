<?php

namespace App\GraphQL\Mutations;

use Error;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\GraphQL\Mutations\BaseMutation;
use App\Models\User;
use Illuminate\Support\Arr;
use Throwable;

class UserCreateMutation extends BaseMutation
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function handle(mixed $root, array $args): array
    {
        try {
            $user = User::create(
                Arr::only(
                    $args['input'],
                    ['first_name', 'last_name', 'email', 'password']
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
            // 'userErrors' => [],
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
