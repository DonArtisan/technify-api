<?php

namespace App\GraphQL\ProductSale\Mutations;

use App\GraphQL\Mutations\BaseMutation;
use App\Models\Delivery;
use Illuminate\Support\Arr;

class DeliveryCreateMutation extends BaseMutation
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function handle(mixed $root, array $args): array
    {
        try {
            $delivery = Delivery::create(
                Arr::only(
                    $args['input'],
                    ['productSaleId', 'sellerId', 'deliveryDate', 'deliveryPlace', 'status']
                )
            );
        } catch (Throwable $error) {
            throw new Error($error);
        }

        return [
            'delivery' => $delivery,
            'userErrors' => [],
        ];
    }

    public function rules(): array
    {
        return [];
    }

    public function messages(): array
    {
        return [];
    }
}
