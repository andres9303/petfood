<div>
    <div class="flex justify-between">
        <div class="bg-alternative-500 text-gray-50 shadow-md rounded p-4 w-full md:w-1/3 mb-4 md:mr-4">
            <i class="fas fa-calculator fa-2x mb-2"></i>
            <p class="text-xl font-semibold">Subtotal</p>
            <p class="text-3xl font-bold">${{ number_format($subtotal) }}</p>
        </div>
        <div class="bg-alternative-500 text-gray-50 shadow-md rounded p-4 w-full md:w-1/3 mb-4 md:mx-2">
            <i class="fas fa-percent fa-2x mb-2"></i>
            <p class="text-xl font-semibold">Impuestos</p>
            <p class="text-3xl font-bold">${{ number_format($tax) }}</p>
        </div>
        <div class="bg-alternative-500 text-gray-50 shadow-md rounded p-4 w-full md:w-1/3 mb-4 md:ml-4">
            <i class="fas fa-dollar-sign fa-2x mb-2"></i>
            <p class="text-xl font-semibold">Total</p>
            <p class="text-3xl font-bold">${{ number_format($total) }}</p>
        </div>
    </div>
    <div class="overflow-x-auto w-full md:col-span-2 block">
        <h2 class="text-2xl font-semibold mb-4">Compras</h2>
        <p class="mb-4">Lista de productos de la compra.</p>
        <a wire:click.prevent="changeModal(true)" class="inline-flex items-center justify-center mb-4 px-4 py-2 text-sm font-medium text-white bg-alternative-500 border border-transparent rounded-md hover:bg-alternative-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-alternative-500" role="button">
            <i class="fa fa-plus"></i>&nbsp; Agregar producto
        </a>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">                        
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Producto
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Unidad
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Cantidad
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Valor unitario
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Impuesto
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Subtotal
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Total
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($orders as $order)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a wire:click.prevent="removeProduct({{ $order->id }})" role="button" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-500 border border-transparent rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"><i class="fa fa-trash-alt mr-1">
                                </i> Eliminar
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $order->product->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $order->unit->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ number_format($order->cant, 4) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ number_format($order->valueu) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ number_format($order->iva) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ number_format($order->cant * $order->valueu) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ number_format($order->cant * $order->valueu * (1 + ($order->iva/100))) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table> 
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
                                {!! Form::select('unit_id', $units->pluck('name', 'id'), null, ['class' => 'block mt-1 w-full', 'wire:model' => 'unit_id']) !!}
                                <p class="text-gray-700 font-semibold text-md">Cantidad</p>
                                {!! Form::number('cant', null, ['class' => 'block mt-1 w-full'.($errors->has('cant') ? ' is-invalid' : ''), 'placeholder' => 'Cantidad preparada', 'step' => 0.0001, 'wire:model' => 'cant']) !!}
                                <p class="text-gray-700 font-semibold text-md">Valor Unitario</p>
                                {!! Form::number('valueu', null, ['class' => 'block mt-1 w-full'.($errors->has('valueu') ? ' is-invalid' : ''), 'placeholder' => 'Valor Unitario', 'step' => 0.01, 'wire:model' => 'valueu']) !!}
                                <p class="text-gray-700 font-semibold text-md">% IVA</p>
                                {!! Form::number('iva', null, ['class' => 'block mt-1 w-full'.($errors->has('iva') ? ' is-invalid' : ''), 'placeholder' => '% IVA', 'wire:model' => 'iva']) !!}
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
