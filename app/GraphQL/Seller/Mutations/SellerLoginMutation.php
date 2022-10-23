<?php

namespace App\GraphQL\Seller\Mutations;

use App\GraphQL\Mutations\BaseMutation;
use App\Models\Seller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SellerLoginMutation extends BaseMutation
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function handle(mixed $root, array $args): array
    {
        try {
            $seller = Seller::whereEmail($args['email'])->firts();

            return ! $seller ?: Hash::check($args['password'], $seller->password);
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
