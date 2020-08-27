@extends('plantilla.admin')

@section('titulo', 'Administración de Categorías')

@section('breadcrumb')
    <li class="breadcrumb-item active">@yield('titulo')</li>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active">@yield('titulo')</li>
@endsection

@section('contenido')

    {{-- {{ dd($categorias) }} --}}
    <div id="confirmareliminar" class="row">
        <span style="display: none" id="urlbase">{{ route('admin.category.index') }}</span>
        @include('custom.modal_eliminar')
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sección de categorías</h3>

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
                    @can('haveaccess','category.create')
                    <a class="m-2 float-right btn btn-primary" href="{{ route('admin.category.create') }}">Crear</a>
                    @endcan
                    <table class="table table-head-fixed">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Slug</th>
                                <th>Descripción</th>
                                <th>Fecha Creación</th>
                                <th>Fecha Modificación</th>
                                <th colspan="3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categorias as $categoria)
                                <tr>
                                    <td>{{ $categoria->id }}</td>
                                    <td>{{ $categoria->name }}</td>
                                    <td>{{ $categoria->slug }}</td>
                                    <td>{{ $categoria->description }}</td>
                                    <td>{{ $categoria->created_at }}</td>
                                    <td>{{ $categoria->updated_at }}</td>
                                    @can('haveaccess','category.show')
                                    <td><a class="btn btn-success"
                                            href="{{ route('admin.category.show', $categoria->slug) }}">Ver</a>
                                    </td>
                                    {{-- <td><a class="btn btn-success"
                                            href="{{ route('admin.category.show', $categoria->slug) }}"><i
                                                class="far fa-eye"></i></a></td> --}}
                                    @endcan
                                    
                                    @can('haveaccess','category.edit')
                                    <td><a class="btn btn-info"
                                            href="{{ route('admin.category.edit', $categoria->slug) }}">Editar</a></td>
                                    {{-- <td><a class="btn btn-info"
                                            href="{{ route('admin.category.edit', $categoria->slug) }}"><i
                                                class="far fa-edit"></i></a></td> --}}
                                    @endcan
                                    
                                    @can('haveaccess','category.destroy')
                                    <td><a class="btn btn-danger"
                                            href="{{ route('admin.category.index') }}"
                                            v-on:click.prevent="deseas_eliminar({{ $categoria->id }})">Eliminar</a></td>
                                    {{-- <td><a class="btn btn-danger"
                                            href="{{ route('admin.category.index', $categoria->slug) }}"
                                            v-on:click.prevent="deseas_eliminar({{ $categoria->id }})"><i
                                                class="fas fa-trash-alt"></i></a></td> --}}
                                    @endcan

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $categorias->appends($_GET)->links() }}
                    {{-- {{ $categorias }} --}}
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- /.row -->
@endsection
