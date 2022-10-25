<?php

namespace App\GraphQL\Seller\Mutations;

use App\GraphQL\Mutations\BaseMutation;
use App\Models\Seller;
use Error;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Throwable;

class SellerLoginMutation extends BaseMutation
{
    public function handle(mixed $root, array $args): array
    {
        $seller = Seller::query()->where('email', $args['email'])->first();

        return [
            'seller' => $seller,
            'userErrors' => [],
            'sellerToken' => $seller->createToken('auth_token')->plainTextToken,
        ];
    }

    public function rules(): array
    {
        return [
            'email' => ['required', Rule::exists(Seller::class, 'email')],
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
