<?php

namespace App\GraphQL\Mutations;

use App\Models\Person;
use App\Models\User;
use Error;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
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
            $person = Person::whereEmail($args['input']['email'])->first();
            $user = $person?->user;
        } catch (Throwable $error) {
            throw new Error($error);
        }

        return [
            'userAuth' => ! $user ?: Hash::check($args['input']['password'], $user->password),
            'userErrors' => [],
            'userToken' => $user->createToken('auth_token')->plainTextToken,
        ];
    }

    public function rules(): array
    {
        return [
            'email' => ['required', Rule::exists(Person::class, 'email')],
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
