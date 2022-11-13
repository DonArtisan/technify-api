<main>
    <div class="p-4 bg-white block sm:flex items-center justify-between lg:mt-1.5 lg:pt-12">
        <div class="mb-1 w-full">
            <div class="mb-4">
                <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">Categorías</h1>
            </div>
            <div class="sm:flex">
                <div class="hidden sm:flex items-center sm:divide-x sm:divide-gray-100 mb-3 sm:mb-0">
                    <form class="lg:pr-3" action="#" method="GET">
                        <label for="search" class="sr-only">Search</label>
                        <div class="mt-1 relative lg:w-64 xl:w-96">
                            <input wire:model.debounce.1000ms="search" type="text" name="email" id="search"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-cyan-600 focus:border-cyan-600 block w-full p-2.5"
                                   placeholder="Buscar por name">
                        </div>
                    </form>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-3 ml-auto">
                    <button wire:click="showAddModal" type="button" data-modal-toggle="add-user-modal" class="w-1/2 text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium inline-flex items-center justify-center rounded-lg text-sm px-3 py-2 text-center sm:w-auto">
                        <svg class="-ml-1 mr-2 h-6 w-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                        Agregar category
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
                                Nombre
                            </th>
                            <th scope="col" class="p-4 text-left text-xs font-medium text-gray-500 uppercase">
                                Acciones
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($categories as $category)
                            <tr class="hover:bg-gray-100">
                                <td class="p-4 flex items-center whitespace-nowrap space-x-6 mr-12 lg:mr-0">
                                    <div class="text-sm font-normal text-gray-500 capitalize">
                                        <div class="text-base font-semibold text-gray-900">{{ $category->name }}</div>
                                    </div>
                                </td>
                                <td class="p-4 whitespace-nowrap space-x-2">
                                    <button wire:click="edit({{ $category->id }})" type="button" data-modal-toggle="user-modal" class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm inline-flex items-center px-3 py-2 text-center">
                                        <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg>
                                        Editar category
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-modal :open="$showModal" cleanAction="resetValues">
        <x-slot:title>
            {{ $isEdit ? 'Editar Categoría' :'Agregar Categoría'}}
        </x-slot:title>

        <form wire:submit.prevent="save">
            <div>
                <div>
                    <x-inputs.text wire:model="name" type="text" name="color" id="color" label="Name" />
                    @error('name')
                    <p class="text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>

            </div>

            <div class="items-center pt-2 mt-4 border-t border-gray-200 rounded-b">
                <button class="text-white bg-cyan-600 hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="submit">
                    Save
                </button>
            </div>
        </form>
    </x-modal>


    <div class="bg-white p-4 sticky bottom-0">
        {{ $categories->links() }}
    </div>
</main>
