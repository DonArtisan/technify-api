<?php

namespace App\GraphQL\ProductSale\Mutations;

use App\GraphQL\Mutations\BaseMutation;
use App\Models\ProductSale;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ProductSaleCreateMutation extends BaseMutation
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

            $user->createAsStripeCustomer();
            $user->updateDefaultPaymentMethod($args['input']['paymentMethod']);

            $productSale = $user->sales()->create(
                Arr::only(
                    $args['input'],['amount', 'tax', 'total'],
                )
            );

            $productSale->productDetails()->create(
                Arr::only(
                    $args['input'],['productId', 'quantity', 'price'],
                )
            );

            $user->invoiceFor('One Time Fee', $args['input']['amount']);
            DB::commit();
        } catch (Throwable $error) {
            DB::rollBack();

            throw new Error($error);
        }

        return [
            'productSale' => $productSale,
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
