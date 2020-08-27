@extends('plantilla.admin')

@section('titulo', 'Administración de Productos')

@section('breadcrumb')
    <li class="breadcrumb-item active">@yield('titulo')</li>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">@yield('titulo')</li>
@endsection

@section('contenido')

    {{-- {{ dd($products) }} --}}
    {{-- {{ $products[0] }} --}}      {{-- Me muestra el primer producto --}}
    {{-- {{ $products[0]->category }}  --}}
    <style type="text/css">
        .table1 {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            text-align: center;       
        }
        .table1 td, .table1 th {
            padding: .75rem;
            vertical-align: center;
            border-top: 1px solid #dee2e6;
        }
    </style>
    <div id="confirmareliminar" class="row">
        <span style="display: none" id="urlbase">{{ route('admin.product.index') }}</span>
        @include('custom.modal_eliminar')
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sección de productos</h3>

                    <div class="card-tools">
                        <form>
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="name" class="form-control float-right" placeholder="Search"
                                    value="{{ request()->get('name') }}">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0" style="height: 300px;">
                    @can('haveaccess','product.create')
                    <a class="m-2 float-right btn btn-primary" href="{{ route('admin.product.create') }}">Crear</a>
                    @endcan
                    <table class="table1 table-head-fixed">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Imagen</th>
                                <th>Nombre</th>
                                <th>Estado</th>
                                <th>Activo</th>
                                <th>Slider Principal</th>
                                <th colspan="3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>
                                        {{-- {{ $product->images->count() }} --}}
                                        @if ($product->images->count()<=0 )
                                            <img style="height: 100px; width: 100px;" src="/imagenes/avatar.png" class="rounded-circle">
                                        @else
                                            <img style="height: 100px; width: 100px;" src="{{ $product->images->random()->url }}" class="rounded-circle">   
                                        @endif
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->active }}</td>
                                    <td>{{ $product->slidermain }}</td>
                                    @can('haveaccess','category.show')
                                    <td><a class="btn btn-success"
                                            href="{{ route('admin.product.show', $product->slug) }}">Ver</a></td>
                                    {{-- <td><a class="btn btn-success"
                                            href="{{ route('admin.product.show', $product->slug) }}"><i
                                                class="far fa-eye"></i></a></td> --}}
                                    @endcan

                                    @can('haveaccess','category.editar')
                                    <td><a class="btn btn-info"
                                            href="{{ route('admin.product.edit', $product->slug) }}">Editar</a></td>   
                                    {{-- <td><a class="btn btn-info"
                                            href="{{ route('admin.product.edit', $product->slug) }}"><i
                                                class="far fa-edit"></i></a></td> --}}
                                    @endcan
                                    
                                    @can('haveaccess','category.destroy')
                                    <td><a class="btn btn-danger"
                                            href="{{ route('admin.product.index') }}"
                                            v-on:click.prevent="deseas_eliminar({{ $product->id }})">Eliminar</a></td>
                                    <td><a class="btn btn-danger"
                                            href="{{ route('admin.product.index', $product->slug) }}"
                                            v-on:click.prevent="deseas_eliminar({{ $product->id }})"><i
                                                class="fas fa-trash-alt"></i></a></td>
                                    @endcan

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $products->appends($_GET)->links() }}
                    {{-- {{ $products }} --}}
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- /.row -->
@endsection
