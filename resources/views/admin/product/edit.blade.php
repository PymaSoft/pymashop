@extends('plantilla.admin')

@section('titulo', 'Editar Producto')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.product.index') }}">Productos</a></li>
    <li class="breadcrumb-item active">@yield('titulo')</li>
@endsection

@section('estilos')
    <!-- Select2 -->
    <link rel="stylesheet" href="/adminlte/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Ekko Lightbox -->
    <link rel="stylesheet" href="/adminlte/plugins/ekko-lightbox/ekko-lightbox.css">
@endsection

@section('scripts')
    <!-- Select2 -->
    <script src="/adminlte/plugins/select2/js/select2.full.min.js"></script>

    <script src="/adminlte/ckeditor/ckeditor.js"></script>

    <!-- Ekko Lightbox -->
    <script src="/adminlte/plugins/ekko-lightbox/ekko-lightbox.min.js"></script>

    <script>
        window.data = {
            editar:'Si',
            datos: {
                "nombre":"{{$producto->name}}",
                "price_previous":"{{$producto->price_previous}}",
                "discount_percentage":"{{$producto->discount_percentage}}"
            }
        }

        $(function() {
            //Initialize Select2 Elements
            $('#category_id').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            $(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox({
                    alwaysShowClose: true
                });
            });
        });

    </script>
@endsection

@section('contenido')

    <div id="apiproduct">
        <form action="{{ route('admin.product.update',$producto->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- SELECT2 EXAMPLE -->
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Datos generados automáticamente</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Visitas</label>
                                    <input class="form-control" type="number" id="visits" name="visits" readonly value="{{ $producto->visits }}">
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                <!-- /.col -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ventas</label>
                                        <input class="form-control" type="number" id="sales" name="sales" readonly value="{{ $producto->sales }}">
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                        </div>
                    </div>
                    <!-- /.card -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Datos del producto</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nombre</label>
                                        <input 
                                            v-model="nombre" @blur="getProduct"
                                            @focus="div_aparecer= false"
                                            class="form-control" type="text" id="name" name="name">
                                        <label>Slug</label>
                                        <input readonly 
                                        v-model="generarSLug" 
                                        class="form-control" type="text" id="slug" name="slug" >
                                        <div v-if="div_aparecer" v-bind:class="div_clase_slug">
                                            @{{ div_mensajeslug }}
                                        </div>
                                        <br v-if="div_aparecer">
                                           
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                <!-- /.col -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Categoría</label>
                                        <select name="category_id" id="category_id" class="form-control "
                                            style="width: 100%;">
                                            @foreach ($categorias as $categoria)
                                            @if ($categoria->name == $producto->category->name)
                                                    <option value="{{ $categoria->id }}" selected="selected">
                                                        {{ $categoria->name }}</option>
                                                @else
                                                    <option value="{{ $categoria->id }}">{{ $categoria->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <label>Cantidad</label>
                                        <input class="form-control" type="number" id="quantity" name="quantity" value="{{ $producto->quantity }}">
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                        </div>
                    </div>
                    <!-- /.card -->
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Sección de Precios</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Precio anterior</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input v-model="price_previous" class="form-control" type="number"
                                                id="price_previous" name="price_previous" min="0" value="0" step=".01">
                                        </div>
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                <!-- /.col -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Precio actual</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input v-model="price_current" class="form-control" type="number"
                                                id="price_current" name="price_current" min="0" value="0" step=".01">
                                        </div>
                                        <br>
                                        <span id="descuento">
                                            @{{ generardescuento }}
                                        </span>
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                <!-- /.col -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Porcentaje de descuento</label>
                                        <div class="input-group">
                                            <input v-model="discount_percentage" class="form-control" type="number"
                                                id="discount_percentage" name="discount_percentage" step="any" min="0"
                                                max="100" value="0" />
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                        <br />
                                        <div class="progress">
                                            <div id="barraprogreso" class="progress-bar" role="progressbar"
                                                v-bind:style="{width: discount_percentage+'%'}" aria-valuenow="0"
                                                aria-valuemin="0" aria-valuemax="100">
                                                @{{ discount_percentage }}%
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer"></div>
                    </div>
                    <!-- /.card -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Descripciones del producto</h3>
                                </div>
                                <div class="card-body">
                                    <!-- Date dd/mm/yyyy -->
                                    <div class="form-group">
                                        <label>Descripción corta:</label>
                                        <textarea class="form-control ckeditor" name="short_description"
                                            id="short_description" rows="3">
                                            {!! $producto->short_description !!}</textarea>
                                    </div>
                                    <!-- /.form group -->
                                    <div class="form-group">
                                        <label>Descripción larga:</label>
                                        <textarea class="form-control ckeditor" name="long_description"
                                            id="long_description" rows="5">
                                            {!! $producto->long_description !!}
                                        </textarea>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col-md-6 -->
                        <div class="col-md-6">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Especificaciones y otros datos</h3>
                                </div>
                                <div class="card-body">
                                    <!-- Date dd/mm/yyyy -->
                                    <div class="form-group">
                                        <label>Especificaciones:</label>
                                        <textarea class="form-control ckeditor" name="specs" id="specs" rows="3">
                                            {!! $producto->specs !!}
                                        </textarea>
                                    </div>
                                    <!-- /.form group -->
                                    <div class="form-group">
                                        <label>Datos de interes:</label>
                                        <textarea class="form-control ckeditor" name="data_of_interest"
                                            id="data_of_interest" rows="5">
                                            {!! $producto->data_of_interest !!}
                                        </textarea>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col-md-6 -->
                    </div>
                    <!-- /.row -->
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Imágenes</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <label for="imagenes">Añadir imágenes</label>
                                <input type="file" class="form-control-file" name="imagenes[]" id="imagenes[]" multiple
                                    accept="image/*">
                                <div class="description">
                                    Un número ilimitado de archivos pueden ser cargados en este campo.
                                    <br>
                                    Límite de 2048 MB por imagen.
                                    <br>
                                    Tipos permitidos: jpeg, png, jpg, gif, svg.
                                    <br>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        {{-- {{ $producto->images}} --}}

                        <div class="card card-primary">
                            <div class="card-header">
                              <div class="card-title">
                                Galería de imágenes
                              </div>
                            </div>
                            <div class="card-body">
                              <div class="row">
                                @foreach ($producto->images as $image)
                                    <div id="idimagen-{{$image->id}}" class="col-sm-2">
                                        <a href="{{ $image->url }}" data-toggle="lightbox" data-title="Id: {{ $image->id }}" data-gallery="gallery">
                                        <img style="width: 150px; height: 150px;" src="{{ $image->url }}" class="img-fluid mb-2"/>
                                        </a>
                                        <br>
                                        <a href="{{ $image->url }}"
                                            v-on:click.prevent="eliminarImagen({{ $image }})"
                                            >
                                            <i class="fas fa-trash-alt" style="color:red"></i> Id:{{ $image->id }}
                                        </a>
                                    </div>                   
                                @endforeach
                              </div>
                            </div>
                          </div>

                        <div class="card-footer">
                        </div>
                    </div>
                    <!-- /.card -->
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Administración</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{-- <label>Estado</label>
                                        <input type="text" class="form-control" id="state" name="state" value="{{ $producto->state }}"> --}}
                                        <label>Estado</label>
                                        <select name="state" id="state" class="form-control" style="width: 100%;">
                                            @foreach ($estados_productos as $state)
                                                @if ($state == $producto->state)
                                                    <option value="{{ $state }}" selected="selected">{{ $state }}</option>
                                                @else
                                                    <option value="{{ $state }}">{{ $state }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-6">
                                    <!-- checkbox -->
                                    <div class="form-group clearfix">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="active" name="active"
                                            @if ($producto->active=='Si')
                                                checked
                                            @endif
                                            >
                                            <label class="custom-control-label" for="active">Activo</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="slidermain"
                                                name="slidermain"
                                                @if ($producto->slidermain=='Si')
                                                checked
                                            @endif
                                                >
                                            <label class="custom-control-label" for="slidermain">Aparece en el Slider
                                                principal</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.row -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <a class="btn btn-danger"
                                            href="{{ route('cancelar', 'admin.product.index') }}">Cancelar</a>
                                        <input :disabled="deshabilitar_boton==1" type="submit" value="Guardar"
                                            class="btn btn-primary">
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                        </div>
                    </div>
                    <!-- /.card -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </form>
    </div>
@endsection
