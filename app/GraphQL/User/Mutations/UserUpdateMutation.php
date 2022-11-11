<?php

namespace App\GraphQL\User\Mutations;

use App\GraphQL\Mutations\BaseMutation;
use App\Models\User;
use Error;
use Illuminate\Support\Arr;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Throwable;

class UserUpdateMutation extends BaseMutation
{
    public function handle(mixed $root, array $args, GraphQLContext $context): array
    {
        try {
            /** @var User $user */
            $user = $context->user();

            $user->update(
                array_merge(
                    Arr::only(
                        $args['input'],
                        ['first_name', 'last_name', 'email', 'isBlocked']
                    ),
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
