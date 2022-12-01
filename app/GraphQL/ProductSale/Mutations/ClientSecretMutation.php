<?php

namespace App\GraphQL\ProductSale\Mutations;

use App\GraphQL\Mutations\BaseMutation;
use Illuminate\Support\Facades\DB;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ClientSecretMutation extends BaseMutation
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function handle(mixed $root, array $args, GraphQLContext $context): array
    {
        DB::beginTransaction();
        try {
            /** @var \App\Models\User $user */
            $user = $context->user();

            $intent = $user->payWith($args['input']['amount'], ['card']);

            DB::commit();
        } catch (Throwable $error) {
            DB::rollBack();

            throw new Error($error);
        }

        return [
            'clientSecret' => $intent->client_secret,
            'userErrors' => [],
        ];
    }

    public function rules(): array
    {
        return [

        ];
    }

    public function messages(): array
    {
        return [

        ];
    }
}
