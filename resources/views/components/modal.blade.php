<div x-data x-transition @class(["overflow-x-hidden overflow-y-auto fixed top-4 left-0 right-0 md:inset-0 z-50 justify-center items-center h-modal sm:h-full flex bg-gray-900/[0.2]", 'hidden' => !$open]) id="modal" aria-modal="true" role="dialog">
    <div class="relative w-full px-4 h-full md:h-auto {{ 'modal-'.$size }}">
        <div {{ $cleanAction ? "@click.outside=\$wire.$cleanAction()" : ''}} class="bg-white rounded-lg shadow relative">
            <div class="flex items-start justify-between p-5 border-b rounded-t">
                <h3 class="text-xl font-semibold">
                    {{ $title }}
                </h3>
                <button {{ $cleanAction ? "@click=\$wire.$cleanAction()" : ''}} type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-toggle="add-user-modal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6 space-y-6">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
