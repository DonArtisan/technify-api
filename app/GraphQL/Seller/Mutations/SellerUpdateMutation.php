<?php

namespace App\GraphQL\Seller\Mutations;

use App\GraphQL\Mutations\BaseMutation;
use App\Models\Seller;
use Error;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Throwable;

class SellerUpdateMutation extends BaseMutation
{
    public function handle(mixed $root, array $args): array
    {
        try {
            $seller = Seller::query()->find($args['input']['id']);

            $data = Arr::only($args['input'], ['first_name', 'last_name']);

            if (isset($args['input']['password'])) {
                $data['password'] = Hash::make($args['input']['password']);
            }

            /** @var Seller $seller */
            $seller = $seller::update($data);
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
            'first_name' => ['sometimes', 'string'],
            'id' => ['required', Rule::exists(Seller::class)],
            'last_name' => ['sometimes', 'string'],
            'password' => ['sometimes', 'confirmed', Password::min(4)->letters()->mixedCase()->numbers()],
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
