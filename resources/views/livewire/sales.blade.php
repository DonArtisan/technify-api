<main>
    <div class="p-4 bg-white block sm:flex items-center justify-between lg:mt-1.5 lg:pt-12">
        <div class="mb-1 w-full">
            <div class="mb-4">
                <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Ventas</h1>
            </div>
            <div class="sm:flex">
                <div class="hidden sm:flex items-center sm:divide-x sm:divide-gray-100 mb-3 sm:mb-0 gap-4">
                    <div>
                        <x-inputs.select wire:model="salesType" id="sales_type" class="pr-8">
                            <option value="">seleccionar por tipo de venta</option>
                            <option value="user">Online</option>
                            <option value="customer">Local</option>
                        </x-inputs.select>
                    </div>
                    <div>
                        <x-inputs.text wire:model="filteredDate" id="filteredDate" type="date" :max="now()->toDateString()" />
                    </div>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-3 ml-auto">
                    <button wire:click="showAddModal" type="button" data-modal-toggle="add-user-modal" class="w-1/2 text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium inline-flex items-center justify-center rounded-lg text-sm px-3 py-2 text-center sm:w-auto">
                        <svg class="-ml-1 mr-2 h-6 w-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                        Realizar venta
                    </button>
                    <a href="#" class="w-1/2 text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-cyan-200 font-medium inline-flex items-center justify-center rounded-lg text-sm px-3 py-2 text-center sm:w-auto">
                        <svg class="-ml-1 mr-2 h-6 w-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"></path></svg>
                        Export
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col px-4">
        <div class="overflow-x-auto">
            <div class="align-middle inline-block min-w-full">
                <div class="shadow overflow-hidden">
                    <table class="table-fixed min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                        <tr>
                            <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase">
                                Fecha
                            </th>
                            <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase">
                                Cliente
                            </th>
                            <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase">
                                Seller
                            </th>
                            <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase">
                                Amount
                            </th>
                            <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase">
                                Total
                            </th>
                            <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase">
                                Acciones
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($sales as $sale)
                            <tr class="hover:bg-gray-100">
                                <td class="p-4 whitespace-nowrap text-base font-medium text-gray-900">
                                    {{ $sale->created_at->toDateString() }}
                                </td>
                                <td class="p-4 whitespace-nowrap text-base font-medium text-gray-900">
                                    {{ $sale->buyerable->name }}
                                </td>
                                <td class="p-4 whitespace-nowrap text-base font-medium text-gray-900">
                                    {{ $sale->seller?->name }}
                                </td>
                                <td class="p-4 whitespace-nowrap text-base font-medium text-gray-900">{{ $sale->amount }}</td>
                                <td class="p-4 whitespace-nowrap text-base font-medium text-gray-900">{{ $sale->total }}</td>
                                <td class="p-4 whitespace-nowrap space-x-2">
                                    <button wire:click="$set('saleIdToDisplay', {{ $sale->id }})" type="button" data-modal-toggle="user-modal" class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm inline-flex items-center px-3 py-2 text-center">
                                        <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path>
                                        </svg>
                                        Ver venta
                                    </button>
{{--                                    @if($sale->order_status == \App\Enums\OrderStatus::COMPLETED())--}}
{{--                                        <button disabled type="button" data-modal-toggle="delete-user-modal" class="disabled:opacity-25 text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm inline-flex items-center px-3 py-2 text-center">--}}
{{--                                            <svg class="mr-2 h-5 w-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">--}}
{{--                                                <path d="M256 512c141.4 0 256-114.6 256-256S397.4 0 256 0S0 114.6 0 256S114.6 512 256 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/>--}}
{{--                                            </svg>--}}
{{--                                            Order aprobada--}}
{{--                                        </button>--}}
{{--                                    @else--}}
{{--                                        <button wire:click="$set('orderIdToApprove', {{ $sale->id }})" type="button" data-modal-toggle="delete-user-modal" class="text-white bg-green-600 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm inline-flex items-center px-3 py-2 text-center">--}}
{{--                                            <svg class="mr-2 h-5 w-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">--}}
{{--                                                <path d="M448 240v96c0 3.084-.356 6.159-1.063 9.162l-32 136C410.686 499.23 394.562 512 376 512H168a40.004 40.004 0 0 1-32.35-16.473l-127.997-176c-12.993-17.866-9.043-42.883 8.822-55.876 17.867-12.994 42.884-9.043 55.877 8.823L104 315.992V40c0-22.091 17.908-40 40-40s40 17.909 40 40v200h8v-40c0-22.091 17.908-40 40-40s40 17.909 40 40v40h8v-24c0-22.091 17.908-40 40-40s40 17.909 40 40v24h8c0-22.091 17.908-40 40-40s40 17.909 40 40zm-256 80h-8v96h8v-96zm88 0h-8v96h8v-96zm88 0h-8v96h8v-96z"/>--}}
{{--                                            </svg>--}}
{{--                                            Aprobar Order--}}
{{--                                        </button>--}}
{{--                                    @endif--}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Create Sale --}}
    <x-modal :open="$showModal" size="lg" cleanAction="resetValues">
        <x-slot:title>
             Agregar Venta
        </x-slot:title>

        <form wire:submit.prevent="save">
            <div class="flex mt-2">
                <div class="flex-1">
                    <x-inputs.text readonly type="text" name="customer" id="customer" label="Cliente"
                                   value="{{ $customerSelected ? $customerSelected?->person->first_name.' - '.$customerSelected?->person->last_name : null }}" />
                </div>
                <button wire:click="$set('showModalCustomer', true)" type="button" class="btn-primary px-3 py-3.5 mt-auto"><i class="fas fa-mouse-pointer"></i></button>
                @error('customer')
                <p class="text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-2">
                <x-inputs.text wire:model="productSearch" type="text" name="product_search" id="product_search" label="Buscar producto" placeholder="Buscar producto..." />
            </div>

            @if($this->modelsIdSelected)
                <strong>Productos Seleccionados</strong>
                <div class="mt-2 max-h-[300px] overflow-auto">
                    <table class="table-fixed min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-800">
                        <tr>
                            <th scope="col" class="p-4 text-left text-xs font-medium text-white uppercase">
                                Marca - Modelo
                            </th>
                            <th scope="col" class="p-4 text-left text-xs font-medium text-white uppercase">
                                Nombre
                            </th>
                            <th scope="col" class="p-4 text-left text-xs font-medium text-white uppercase">
                                Cantidad a vender
                            </th>
                            <th scope="col" class="p-4 text-left text-xs font-medium text-white uppercase">
                                Stock
                            </th>
                            <th scope="col" class="p-4 text-left text-xs font-medium text-white uppercase">
                                Precio
                            </th>
                            <th scope="col" class="p-4 text-left text-xs font-medium text-white uppercase">
                                Acciones
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($modelsSelected as $model)
                            <tr class="hover:bg-gray-100" wire:key="{{ 'selected_'.$model->id }}">
                                <td class="p-4 whitespace-nowrap text-base font-medium text-gray-900">
                                    <div class="text-base font-semibold text-gray-900">{{ $model->brand->name }} - {{ $model->model_name }}</div>
                                </td>
                                <td class="p-4 whitespace-nowrap text-base font-medium text-gray-900">{{ $model->product->name }}</td>
                                <td class="p-4 whitespace-nowrap text-base font-medium text-gray-900">
                                    <x-inputs.text wire:model.defer="quantities.{{ $model->id }}" type="number"
                                                   :id="'quantity_'.$model->id"
                                                   :name="'quantity_'.$model->id" min="1" :max="$model->product->stock->quantity"
                                                    @input="(event) => window.validate(event, {{ $model->product->stock->quantity }})"/>
                                </td>
                                <td class="p-4 whitespace-nowrap text-base font-medium text-gray-900">{{ $model->product->stock->quantity }}</td>
                                <td class="p-4 whitespace-nowrap text-base font-medium text-gray-900">{{ $model->product->price->price }}</td>
                                <td class="p-4 whitespace-nowrap space-x-2">
                                    <button wire:click="removeModel({{ $model->id }})" type="button" data-modal-toggle="user-modal" class="text-white bg-red-600 hover:bg-red-700 focus:ring-4 focus:ring-red-200 font-medium rounded-lg text-sm inline-flex items-center px-3 py-2 text-center">
                                        <svg class="w-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                            <path d="M256 512c141.4 0 256-114.6 256-256S397.4 0 256 0S0 114.6 0 256S114.6 512 256 512zM184 232H328c13.3 0 24 10.7 24 24s-10.7 24-24 24H184c-13.3 0-24-10.7-24-24s10.7-24 24-24z"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <p>No hay registros para este filtro.</p>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            @endif

            <hr>

            <div class="mt-2 max-h-[300px] overflow-auto">
                <table class="table-fixed min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase">
                            Marca - Modelo
                        </th>
                        <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase">
                            Nombre
                        </th>
                        <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase">
                            Stock
                        </th>
                        <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase">
                            Price
                        </th>
                        <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase">
                            Acciones
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($models as $model)
                        <tr class="hover:bg-gray-100" wire:key="{{ 'searched_'.$model->id }}">
                            <td class="p-4 whitespace-nowrap text-base font-medium text-gray-900">
                                <div class="text-base font-semibold text-gray-900">{{ $model->brand->name }} - {{ $model->model_name }}</div>
                            </td>
                            <td class="p-4 whitespace-nowrap text-base font-medium text-gray-900">{{ $model->product->name }}</td>
                            <td class="p-4 whitespace-nowrap text-base font-medium text-gray-900">{{ $model->product->stock->quantity }}</td>
                            <td class="p-4 whitespace-nowrap text-base font-medium text-gray-900">{{ $model->product->price->price }}</td>
                            <td class="p-4 whitespace-nowrap space-x-2">
                                <button wire:click="selectModel({{ $model->id }})" type="button" data-modal-toggle="user-modal" class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm inline-flex items-center px-3 py-2 text-center">
                                    <svg class="w-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M448 240v96c0 3.084-.356 6.159-1.063 9.162l-32 136C410.686 499.23 394.562 512 376 512H168a40.004 40.004 0 0 1-32.35-16.473l-127.997-176c-12.993-17.866-9.043-42.883 8.822-55.876 17.867-12.994 42.884-9.043 55.877 8.823L104 315.992V40c0-22.091 17.908-40 40-40s40 17.909 40 40v200h8v-40c0-22.091 17.908-40 40-40s40 17.909 40 40v40h8v-24c0-22.091 17.908-40 40-40s40 17.909 40 40v24h8c0-22.091 17.908-40 40-40s40 17.909 40 40zm-256 80h-8v96h8v-96zm88 0h-8v96h8v-96zm88 0h-8v96h8v-96z"/></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <p>No hay registros para este filtro.</p>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end my-4">
                <ul class="space-y-1">
                    <li class="flex gap-2 items-center justify-end">
                        <span>Subtotal</span>
                        <span class="w-32">
                            <x-inputs.text id="subtotal" :value="$this->subtotal"/>
                        </span>
                    </li>
                    <li class="flex gap-2 items-center justify-end">
                        <span>IVA</span>
                        <label class="w-32">
                            <x-inputs.text id="iva" :value="$this->subtotal * 0.15" />
                        </label>
                    </li>
                    <li class="flex gap-2 items-center justify-end">
                        <span>Total</span>
                        <span class="w-32">
                            <x-inputs.text id="total" :value="$this->total" />
                        </span>
                    </li>
                </ul>
            </div>

            <div class="space-y-4">
                <label>
                    <input wire:model="isDelivery"
                        id="delivery"
                       type="checkbox"
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                           name="delivery">
                    Delivery?
                </label>

                @if($isDelivery)
                    <div>
                        <label class="mr-4">
                            <input wire:model="deliveryTypeAddress"
                                   id="delivery_type_address"
                                   type="radio"
                                   value="client"
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                   name="delivery_type_address"
                                    @checked(old('delivery_type_address') == 'client')
                            >
                            Misma dirección del cliente
                        </label>

                        <label>
                            <input wire:model="deliveryTypeAddress"
                                   id="delivery_type_address"
                                   type="radio"
                                   value="custom"
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                   name="delivery_type_address"
                                   @checked(old('delivery_type_address') == 'custom')
                            >
                            Dirección personalizada
                        </label>
                    </div>

                    <div>
                        <div class="grid grid-cols-6 gap-6">

                            @if($deliveryTypeAddress == 'custom')
                                <div class="col-span-6 sm:col-span-3">
                                    <x-inputs.text wire:model.defer="deliveryInfo.address" type="text" name="address" id="address" label="Dirección" />
                                    @error('deliveryInfo.address')
                                    <p class="text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif
                            <div class="col-span-6 sm:col-span-3">
                                <x-inputs.text wire:model.defer="deliveryInfo.date" type="date" name="delivery_date" id="delivery_date" label="Fecha"/>
                                @error('deliveryInfo.date')
                                <p class="text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="items-center pt-2 mt-4 border-t border-gray-200 rounded-b">
                <button type="button" wire:click="calculate" class="text-white bg-green-600 hover:bg-green-700 focus:green-4 focus:ring-green-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                    Calcular
                </button>
               @if($this->total)
                    <button class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="submit">
                        Guardar
                    </button>
               @endif
            </div>
        </form>
    </x-modal>

    {{-- Customer --}}
    <x-modal :open="$showModalCustomer" size="lg" cleanAction="resetModalCustomer">
        <x-slot:title>
            Seleccionar Cliente
        </x-slot:title>

        <form>
            <div>
                <div>
                    <x-inputs.text wire:model.debounce.1000ms="customerSearch" type="text" name="customer" id="customer" label="Buscar Cliente" />
                    @error('customerSearch')
                    <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-2 max-h-[500px] overflow-auto">
                <table class="table-fixed min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase">
                            Acciones
                        </th>
                        <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase">
                            Name
                        </th>
                        <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase">
                            DNI
                        </th>
                        <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase">
                            Phone
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-gray-100">
                            <td class="p-4 whitespace-nowrap space-x-2">
                                <button wire:click="selectCustomer({{ $customer->id }})" type="button" data-modal-toggle="user-modal" class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm inline-flex items-center px-3 py-2 text-center">
                                    <svg class="w-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M448 240v96c0 3.084-.356 6.159-1.063 9.162l-32 136C410.686 499.23 394.562 512 376 512H168a40.004 40.004 0 0 1-32.35-16.473l-127.997-176c-12.993-17.866-9.043-42.883 8.822-55.876 17.867-12.994 42.884-9.043 55.877 8.823L104 315.992V40c0-22.091 17.908-40 40-40s40 17.909 40 40v200h8v-40c0-22.091 17.908-40 40-40s40 17.909 40 40v40h8v-24c0-22.091 17.908-40 40-40s40 17.909 40 40v24h8c0-22.091 17.908-40 40-40s40 17.909 40 40zm-256 80h-8v96h8v-96zm88 0h-8v96h8v-96zm88 0h-8v96h8v-96z"/></svg>
                                </button>
                            </td>
                            <td class="p-4 flex items-center whitespace-nowrap space-x-6 mr-12 lg:mr-0">
                                <div class="text-sm font-normal text-gray-500">
                                    <div class="text-base font-semibold text-gray-900">{{ $customer->name }}</div>
                                </div>
                            </td>
                            <td class="p-4 whitespace-nowrap text-base font-medium text-gray-900">{{ $customer->person?->dni }}</td>
                            <td class="p-4 whitespace-nowrap text-base font-medium text-gray-900">{{ $customer->person?->phone_number }}</td>
                        </tr>
                    @empty
                        <p>No hay registros para este filtro.</p>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </x-modal>

    {{-- Display Sale --}}
    <x-modal :open="$saleIdToDisplay && $saleToDisplay" size="lg" cleanAction="resetSaleIds">
        <x-slot:title>
            Venta
        </x-slot:title>

        @if($saleIdToDisplay && $saleToDisplay)
            <div class="space-y-2">
                <div>
                    <x-inputs.text readonly type="date" id="order_required_date" label="Fecha" :value="$saleToDisplay->created_at->format('Y-m-d')"/>
                </div>
                <div>
                    <x-inputs.text readonly type="text" name="sale_customer" id="sale_customer" label="Cliente"
                                   :value="$saleToDisplay->buyerable->name" />
                </div>

                <h4 class="my-2">Productos</h4>
                <div class="mt-2 max-h-[500px] overflow-auto">
                    <table class="table-fixed min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-800">
                        <tr>
                            <th scope="col" class="p-4 text-left text-xs font-medium text-white uppercase">
                                Modelo
                            </th>
                            <th scope="col" class="p-4 text-left text-xs font-medium text-white uppercase">
                                Nombre
                            </th>
                            <th scope="col" class="p-4 text-left text-xs font-medium text-white uppercase">
                                Marca
                            </th>
                            <th scope="col" class="p-4 text-left text-xs font-medium text-white uppercase">
                                Cantidad
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($saleDetails as $saleDetail)
                            <tr class="hover:bg-gray-100" wire:key="{{ 'displayed_'.$saleDetail->id }}">
                                <td class="p-4 whitespace-nowrap text-base font-medium text-gray-900">
                                    <div class="text-base font-semibold text-gray-900">{{ $saleDetail->product->model->model_name }}</div>
                                </td>
                                <td class="p-4 whitespace-nowrap text-base font-medium text-gray-900">{{ $saleDetail->product->name }}</td>
                                <td class="p-4 whitespace-nowrap text-base font-medium text-gray-900">{{ $saleDetail->product->model->brand->name }}</td>
                                <td class="p-4 whitespace-nowrap text-base font-medium text-gray-900">
                                    <x-inputs.text :value="$saleDetail->quantity" readonly type="number" :id="'selected_'.$saleDetail->id" :name="'selected_'.$saleDetail->id" min="1" />
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </x-modal>
</main>
