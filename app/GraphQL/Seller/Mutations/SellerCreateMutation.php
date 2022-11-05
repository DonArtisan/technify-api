<?php

namespace App\GraphQL\Seller\Mutations;

use App\GraphQL\Mutations\BaseMutation;
use App\Models\Seller;
use Error;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Throwable;

class SellerRegisterMutation extends BaseMutation
{
    public function handle(mixed $root, array $args): array
    {
        try {
            $seller = Seller::create(
                [
                    'password' => Hash::make($args['input']['password']),
                    'carnet' => now()->year.'-'.Str::random(5),
                    ...Arr::only(
                        $args['input'],
                        ['first_name', 'last_name', 'email', 'hired_at']
                    ),
                ]
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
            'password' => ['required', 'confirmed', Password::min(4)->letters()->mixedCase()->numbers()],
            'hired_at' => ['required', 'date'],
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
