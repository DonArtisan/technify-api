<?php

namespace App\GraphQL\ProductSale\Mutations;

use App\GraphQL\Mutations\BaseMutation;
use App\Http\Stats\SalesStats;
use Illuminate\Support\Arr;
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
            logger($args);

            $total = $args['input']['amount'] * 100;

            $intent = $user->payWith($args['input']['amount'], ['card']);

            $productSale = $user->sales()->create(
                array_merge(
                    Arr::only($args['input'], ['amount']),
                    ['tax' => 15, 'total' => $total + ($total * .15)]
                )
            );

            $data = collect($args['input']['products'])->map(function ($d) {
                return Arr::except($d, ['name', 'description']);
            })->toArray();

            $productSale->saleDetails()->createMany($data);
            SalesStats::increase(1);
            logger($intent);
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
