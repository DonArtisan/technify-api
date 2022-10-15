<?php

namespace App\GraphQL\Seller\Mutations;

use App\GraphQL\Mutations\BaseMutation;
use App\Models\Seller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class SellerRegisterMutation extends BaseMutation
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function handle(mixed $root, array $args): array
    {

        try {

            $seller = Seller::create(
                array_merge(
                    Arr::only(
                        $args['input'],
                        ['first_name', 'last_name', 'email', 'hired_at']
                    ),
                    [
                        'password' => Hash::make($args['input']['password']),
                        'carnet' => now()->year. '-' . Str::random(5),
                    ]
                )
            );

        } catch (Throwable $error) {

            throw new Error($error);
        }

        return [
            'seller' => $seller,
            'userErrors' => [],
            'sellerToken' => $seller->createToken('auth_token')->plainTextToken,
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
