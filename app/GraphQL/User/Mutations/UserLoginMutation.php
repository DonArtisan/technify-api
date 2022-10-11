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
            $user = User::whereEmail($args['email'])->firts();

            return !$user ?: Hash::check($args['password'], $user->password);
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
            'password' => ['required']
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