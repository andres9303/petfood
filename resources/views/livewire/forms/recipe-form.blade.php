<div>
    <div class="flex justify-center">
        <div class="w-full">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                <div class="bg-alternative-500 text-gray-50 shadow-md rounded px-8 pt-6 pb-8 mb-4 w-full">
                    <div class="flex justify-center items-center mb-4">
                        <div class="w-32 h-32 bg-gray-300 rounded-full flex justify-center items-center">
                            <img src="{{ $diet->image ? asset('storage/' . $diet->image->url) : asset('storage/producto/default.png') }}" alt="Imagen {{ $diet->name }}" class="w-28 h-28 rounded-full">
                        </div>
                    </div>
                    <div class="text-center mb-4">
                        <p class="text-xl font-semibold">Dieta: {{ $diet->name }}</p>
                        <p class="text-lg font-semibold">{{ $diet->category->name }}</p>                        
                        <p class="text-sm">Código: {{ $diet->code }}</p>
                        <p class="text-sm">${{ number_format($diet->valueu) }} por {{ $diet->unit->name }}</p>
                        <p class="text-sm mt-2">
                            <span class="px-2 py-1 rounded-full {{ $diet->state == 1 ? 'bg-green-500' : 'bg-red-500' }}">
                                {{ $diet->state == 1 ? 'Activo' : 'Inactivo' }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="bg-gray-50 text-gray-500 shadow-md rounded px-8 pt-6 pb-8 mb-4 w-full md:col-span-2 block">
                    <h2 class="text-2xl font-semibold mb-4">Ingredientes</h2>
                    <p class="mb-4">Las cantidades están basadas en una porción de 1 {{ $diet->unit->name }} de la dieta ya preparada.</p>
                    <a wire:click.prevent="changeModal(true)" class="inline-flex items-center justify-center mb-4 px-4 py-2 text-sm font-medium text-white bg-alternative-500 border border-transparent rounded-md hover:bg-alternative-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-alternative-500" role="button">
                        <i class="fa fa-plus"></i>&nbsp; Agregar Ingrediente
                    </a>
                    <livewire:table.master.recipe-table diet_id="{{ $diet->id }}"/>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="product-modal" class="fixed z-10 inset-0 overflow-y-auto {{ $showModal ? 'block' : 'hidden' }}">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
          <!-- Background overlay -->
          <div class="fixed inset-0 transition-opacity" aria-hidden="true">
              <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
          </div>
          <!-- Modal content -->
          <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle w-full sm:w-11/12 sm:p-6">
            <div class="flex items-center bg-red-100 text-red-500 text-sm font-bold px-4 py-3 {{ $error ? 'block' : 'hidden' }}" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span class="align-middle">{{ $error }}</span>
            </div>
            <div class="sm:flex sm:items-start">
                <!-- Categorías de productos -->
                <div class="w-full sm:w-48">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Categorías</h3>
                    <ul class="divide-y divide-gray-200">
                        <li class="py-2">
                            <a wire:click.prevent="changeCategory(0)" role="button" class="text-gray-700 hover:text-gray-900">Todos</a>
                        </li>
                        @foreach ($categories as $item)
                        <li class="py-2">
                            <a wire:click.prevent="changeCategory({{ $item->id }})" role="button" class="text-gray-700 hover:text-gray-900">{{ $item->name }}</a>
                        </li>
                        @endforeach
                    </ul>
                    <div class="mt-4">
                        <a wire:click.prevent="changeModal(false)" class="block mr-4 mb-4 items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-500 border border-transparent rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" role="button"><i class="fas fa-window-close"></i>&nbsp; Cancelar</a>
                    </div>
                </div>
                  <!-- Lista de productos -->
                  <div class="w-full">
                    <div class="flex flex-col sm:flex-row">
                      <h3 class="text-lg font-medium text-gray-900 mb-3">Productos</h3>
                      <div class="flex-grow sm:ml-4">
                        <input wire:model="search" type="text" class="w-full border-gray-300 border-2 px-4 py-2 rounded-md mb-4" placeholder="Buscar productos...">
                      </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                      @foreach ($products as $product)
                          <div class="bg-white overflow-hidden shadow rounded-lg flex flex-row">
                            <div class="w-24 h-24 bg-gray-300 rounded-full flex justify-center items-center">
                                <img src="{{ $product->image ? asset('storage/' . $product->image->url) : asset('storage/producto/default.png') }}" alt="Imagen {{ $product->name }}" class="w-20 h-20 rounded-full">
                            </div>
                            <div class="px-4 py-3 flex-grow">
                                <h4 class="text-lg font-medium text-gray-900">{{ $product->name }}</h4>
                                <p class="text-gray-700">{{ $product->unit->name }}</p>                                  
                                <a wire:click.prevent="addCant(-1)" role="button" class="inline-flex items-center justify-center p-2 text-sm font-medium text-white bg-red-500 border border-transparent rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" role="button"><i class="fas fa-minus-circle"></i></a>
                                <a wire:click.prevent="addCant(1)" role="button" class="inline-flex items-center justify-center p-2 text-sm font-medium text-white bg-green-500 border border-transparent rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" role="button"><i class="fas fa-plus-circle"></i></a>
                                <p class="text-gray-700 font-semibold text-md">Cant. preparada</p>
                                {!! Form::number('cant', null, ['class' => 'block mt-1 w-full'.($errors->has('cant') ? ' is-invalid' : ''), 'placeholder' => 'Cantidad preparada', 'step' => 0.0001, 'wire:model' => 'cant']) !!}
                                <p class="text-gray-700 font-semibold text-md">Cant. sin preparar</p>
                                <p class="text-green-700 font-semibold text-xl">{{ $cant && $product->factor && $product->factor != 0 ? number_format($cant/$product->factor, 4) : 0 }}</p>
                                {!! Form::select('unit_id', $units->pluck('name', 'id'), null, ['class' => 'block mt-1 w-full', 'wire:model' => 'unit_id']) !!}
                                {!! Form::text('text', null, ['class' => 'block mt-1 w-full'.($errors->has('text') ? ' is-invalid' : ''), 'placeholder' => 'Observaciones', 'wire:model' => 'text']) !!}
                                <a wire:click.prevent="selectProduct({{ $product->id }})" role="button" class="mt-4 inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-alternative-500 hover:bg-alternative-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-alternative-500">
                                    <i class="fas fa-plus mr-2"></i> Agregar
                                </a>
                            </div>
                          </div>
                      @endforeach
                    </div>                 
                  </div>
              </div>
            </div>
        </div>
    </div>

</div>
