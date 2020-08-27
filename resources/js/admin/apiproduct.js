const apiproduct = new Vue({
    el: "#apiproduct",
    data: {
        nombre: "",
        slug: "",
        div_mensajeslug: "Slug Existe",
        div_clase_slug: "badge badge-danger",
        div_aparecer: false,
        deshabilitar_boton: 1,
        
        // variables de precios
        price_previous: 0,
        price_current: 0,
        descuento: 0,
        discount_percentage: 0,
        descuento_mensaje: "0"
    },
    computed: {
        generarSLug: function() {
            var char = {
                á: "a",
                é: "e",
                í: "i",
                ó: "o",
                ú: "u",
                Á: "A",
                É: "E",
                Í: "I",
                Ó: "O",
                Ú: "U",
                ñ: "n",
                Ñ: "N",
                " ": "-",
                _: "-"
            };
            var expr = /[áéíóúÁÉÍÓÚÑñ_ ]/g;

            this.slug = this.nombre
                .trim()
                .replace(expr, function(e) {
                    return char[e];
                })
                .toLowerCase();

            return this.slug;
            //return this.nombre.trim().replace(/ /g,'-').toLowerCase()
        },
        generardescuento: function() {
            if (this.discount_percentage > 100) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "No puedes poner un valor mayor a 100"
                });
                this.discount_percentage = 100;
                this.descuento =
                    (this.price_previous *
                        this.discount_percentage) /
                    100;
                this.price_current =
                    this.price_previous - this.descuento;
                this.descuento_mensaje =
                    "Este producto tiene el 100% de descuento, por ende es gratis.";
                return this.descuento_mensaje;
            } else if (this.discount_percentage < 0) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "No puedes poner un valor menor a 0"
                });
                this.discount_percentage = 0;
                this.descuento =
                    (this.price_previous *
                        this.discount_percentage) /
                    100;
                this.price_current =
                    this.price_previous - this.descuento;
                this.descuento_mensaje = "";
                return this.descuento_mensaje;
            } else if (this.discount_percentage > 0) {
                this.descuento =
                    (this.price_previous *
                        this.discount_percentage) /
                    100;

                this.price_current =
                    this.price_previous - this.descuento;

                if (this.discount_percentage == 100) {
                    this.descuento_mensaje =
                        "Este producto tiene el 100% de descuento, por ende es gratis.";
                } else {
                    this.descuento_mensaje =
                        "Hay un descuento de $ " + this.descuento;
                }
                return this.descuento_mensaje;
            } else {
                this.descuento = "";

                this.price_current = this.price_previous;

                this.descuento_mensaje = "";

                return this.descuento_mensaje;
            }
        }
    },
    methods: {
        eliminarImagen(imagen) {
            //console.log(imagen);
            Swal.fire({
                title: '¿Estas seguro de eliminar la imagen '+ imagen.id+ '?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '¡Si, Eliminar!',
                cancelButtonText: 'Cancelar'
              }).then((result) => {
                if (result.value) {
                    let url = '/api/eliminarimagen/'+imagen.id;
                    axios.delete(url).then(response => {
                         console.log(response.data);
                    });
                  //eliminar el elemento
                  var elemento = document.getElementById('idimagen-'+imagen.id);
                  //console.log(elemento);
                  elemento.parentNode.removeChild(elemento);
                  Swal.fire(
                    '¡Eliminado!',
                    'Su archivo ha sido eliminado.',
                    'success'
                  )
                }
              })
        },
        getProduct() {
            if (this.slug) {
                let url = "/api/product/" + this.slug;
                axios.get(url).then(response => {
                    this.div_mensajeslug = response.data;
                    if (this.div_mensajeslug === "Slug Disponible") {
                        this.div_clase_slug = 'badge badge-success';
                        this.deshabilitar_boton=0;
                    } else {
                        this.div_clase_slug = 'badge badge-danger';
                        this.deshabilitar_boton=1;
                    }
                    this.div_aparecer = true;
                    if (data.datos.nombre) {
                        if (data.datos.nombre===this.nombre) {
                            this.deshabilitar_boton=0;
                            this.div_mensajeslug='';
                            this.div_clase_slug='';
                            this.div_aparecer = false;
                        }
                    }
                })
            } else {
                this.div_clase_slug = 'badge badge-danger';
                this.div_mensajeslug="Debes escribir un producto";
                this.deshabilitar_boton=1;
                this.div_aparecer = true;
            }
        }
    },

    mounted(){
        if (data.editar=='Si') {
            this.nombre = data.datos.nombre;
            this.price_previous = data.datos.price_previous;
            this.discount_percentage=data.datos.discount_percentage;
            this.deshabilitar_boton=0;
        }
        console.log(data);
    }
});
