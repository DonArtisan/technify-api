<?php

namespace App\GraphQL\ProductSale\Mutations;

use App\GraphQL\Mutations\BaseMutation;
use App\Http\Stats\SalesStats;
use App\Models\Delivery;
use App\Models\ProductSaleDetail;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Collection;
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
            logger($total);

            $intent = $user->payWith($total, ['card']);

            $productSale = $user->sales()->create(
                array_merge(
                    Arr::only($args['input'], ['amount']),
                    ['tax' => 15, 'total' => $total + ($total * .15)]
                )
            );

            $data = collect($args['input']['products'])->map(function ($values) {
                return Arr::except($values, ['name', 'description', 'image']);
            })->toArray();

            logger($args['input']['deliveryPlace']);

            /** @var Collection<ProductSaleDetail> $productDetails */
            $productDetails = $productSale->saleDetails()->createMany($data);

            $productDetailsByKey = $productDetails->keyBy('product_id');

            SalesStats::increase(1);
            logger($intent);
            $delivery = Delivery::create(
                [
                    'status' => '0',
                    'sale_id' => $productSale->id,
                    'delivery_place' => $args['input']['deliveryPlace'],
                    'delivery_date' => now()->addDays(7),

                ]
            );

            Stock::query()
                ->whereIn('product_id', $productDetails->pluck('product_id'))
                ->each(function (Stock $stock) use ($productDetailsByKey) {
                    $stock->update([
                        'quantity' => $stock->quantity - $productDetailsByKey[$stock->product_id]['quantity'],
                    ]);
                });

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
