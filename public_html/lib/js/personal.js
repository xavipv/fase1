//--- VARIABLES GLOBALES -----------------------------------------------------//

var gNumApar = 209;     // Numero de apartamentos.
var gNumPort = 15;      // Numero de portales.

//--- EDITOR Y CALENDARIO ----------------------------------------------------//

/**
 * Crea un editor de texto con los botones basicos.
 */
function js_editor() {
    $('.editor').trumbowyg({
        lang: 'es',
        svgPath: '/lib/css/icons.svg',
        autogrow: true,
        autogrowOnEnter: true,
        removeformatPasted: true,
        btns: [
        ['viewHTML'],
        ['undo', 'redo'], // Only supported in Blink browsers
        ['strong', 'em', 'del'],
        ['superscript', 'subscript'],
        // ['insertImage'],
        ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
        ['unorderedList', 'orderedList'],
        ['preformatted'],
        ['horizontalRule'],
        // ['formatting'],
        // ['link'],
        ['removeformat'],
        ['fullscreen']
    ]
    });
}

/**
 * Crea un calendario que permite seleccionar fechas.
 * 
 * @param {boolean} bBor Si es true se mostrara el boton de Borrar, si es false se ocultara.
 * @param {boolean} bHoy Si es true se mostrara el boton de Hoy, si es false se ocultara.
 */
function js_calendario(bBor=true, bHoy=true) {
    var bor = (bBor) ? true : false;
    var hoy = (bHoy) ? true : false;
    $( function() { 
        $('.calendario').datepicker({
            format: 'dd-mm-yyyy', 
            language: 'es', 
            autoclose: true, 
            todayHighlight: true,
            clearBtn: bor,
            todayBtn: hoy
        }).on('changeDate',function(e){ 
            js_onCalendario(location.pathname.substr(location.pathname.lastIndexOf('/')+1), this.id); 
        }); 
    });
}

/**
 * Se llama al cambiar la fecha del calendario.
 * 
 * @param {string} pag Pagina desde la que se llama.
 * @param {string} id Identificador del elemento.
 * @returns {undefined}
 */
function js_onCalendario(pag, id) {
    // Obtiene la parte numerica del identificador.
    var num = id.replace(/[^0-9.]/g, "");
    
    switch(pag) {
        case "propietarios.php" : 
        case "propper.php" : 
            $("#boton"+num).prop("disabled",false);
            if(num === "0" && $('#nombre0').val() === "") {
                $("#boton"+num).prop("disabled",true);
            }
            break;
        case "votaciones.php" : 
            js_cambiarFechaVotacion($('#fecha').val(), $('#fechainicial').val()); 
            break;
        case "juntas.php" : 
            if(!$('#boasistentes').is(':visible')) { 
                xajax_setJuntaDatosForm($('#fecha').val()); 
            } 
            break;    
    }
}

//--- AJUSTE DE TAMAÑO AUTOMATICO --------------------------------------------// 

/**
 * Lanza el ajuste de tamaño cuando el documento se carga.
 */
$(document).ready(function() { 
    js_redimensionar();
    
    /**
     * Lanza el ajuste de tamaño cuando se cambia el tamaño de la ventana.
     */
    $(window).resize(js_redimensionar); 
} );

/**
 * Ajusta el tamaño de la cabecera 'cabecera' y el contenedor 'contenedor' del documento.
 * <ul>
 * <li>cabecera - Cabecera del menu principal y submenues.</li>
 * <li>divlistado - Listado de la parte izquierda.</li>
 * <li>contenedor - Datos de la parte derecha.</li>
 * </ul>
 * Despues llama a la funcion que ajusta los contenidos.
 */
function js_redimensionar() {
    $('#contenedor').height(0);
    var altoPag = $(window).innerHeight();
    var altoCab = $('#cabecera').outerHeight(true);
    var altura  = altoPag - altoCab;
    
    $('#contenedor').outerHeight(altura);
    if($('#divlistado').length > 0) {
        $('#divlistado').outerHeight(altura);
    }
    if($('#contenido').length > 0) {
        $('#contenido').outerHeight(altura);
    }
    setTimeout(js_redimensionarContenido, 100, altura);
}

/**
 * Ajusta el tamaño del contendio del documento.
 * <ul>
 * <li>divcabecera - Datos de la parte superior derecha del contenedor.</li>
 * <li>divcontenido - Datos de la parte inferior derecha del contenedor.</li>
 * <li>divbusqueda - Datos de la parte inferior derecha del contenedor (se alterna con divcontenido).</li>
 * </ul>
 * 
 * @param {int} altura Altura del contenedor (sin cabecera).
 */
function js_redimensionarContenido(altura) {
    $("#divcontenido").css("height",0);
    $("#divbusqueda").css("height",0);
    var divcabe = ($('#divcabecera').length > 0) ? $('#divcabecera').outerHeight(true) : 0;
    var divcont = altura - divcabe;
    
    if($('#divcontenido').length > 0) {
        $('#divcontenido').outerHeight(divcont);
    }
    
    if($('#divbusqueda').length > 0) {
        $('#divbusqueda').outerHeight(divcont);
    }
    //alert("Altura: "+altura+"\nDivcabe: "+divcabe+"\nDivcont: "+divcont);
}

//--- SUMAS Y CALCULOS -------------------------------------------------------//

/**
 * Suma todas las columnas.
 * 
 * @param {int} por Numero de portal.
 */
function js_sumarTodos(por) {
    js_sumar(por, "me");    // Metros
    js_sumar(por, "te");    // Terraza
    js_sumar(por, "cf");    // Coeficiente
    js_sumar(por, "cb");    // Coeficiente bloque
}

/**
 * Fuerza la suma de las deudas.
 */
function js_sumarDeudas() {
    js_sumar(1, "or");
}

/**
 * Realiza las sumas de cada portal.
 * 
 * @param {int} por Numero de portal.
 * @param {string} id Id de columna.
 */
function js_sumar(por, id) {
    var col = id.substr(0, 2);      // Nombre comun de la columna.
    var spo = "p" + col + por;      // Nombre de la casilla del portal.
    var apa = js_apartPortal(por);  // Primer y ultimo apartamento del portal.
    var ain = apa[0];               // Apartamento inicial.
    var afi = apa[1];               // Apartamento final.
    var sup = 0;                    // Suma del portal.
    var nom;                        // Nombre de la casilla del apartamento.
    var dec = (col === "me" || col === "te" || col === "cb" || col === "or" || col === "ex" || col === "su") ? 2 : 4;
    
    // Obtiene la suma del portal.
    for(i=ain; i<=afi; i++) {
        nom = col + i;
        sup += js_aFloat($('#'+nom).val());
        if (col === "or" || col === "ex") {
            $('#su'+i).val((js_aFloat($('#or'+i).val()) + js_aFloat($('#ex'+i).val())).toFixed(2));
        }
    }
    
    $('#'+spo).val(sup.toFixed(dec));
    
    if (col === "or" || col === "ex") {
        js_sumar(por, "su");
        js_ponerDeudores();
        js_copiarTotal();
    }
    
    js_sumarTotal(col);
}

/**
 * 
 * @param {string} col Id de la columna.
 */
function js_sumarTotal(col) {
    var a = [1, gNumPort];  // Primer y ultimo portal.
    var nom;                // Nombre de la casilla que se leera.
    var sut = 0;            // Suma de la columna.
    var dec = (col === "me" || col === "te" || col === "cb" || col === "or" || col === "ex" || col === "su") ? 2 : 4;
    
    // Recorre los apartamentos de los portales.
    for(i=a[0]; i<=a[1]; i++) {
        nom = "p" + col + i;
        sut += js_aFloat($('#'+nom).val());
    }
    
    // Pone los totales.
    $('#t'+col).val(sut.toFixed(dec));
}

/**
 * Obtiene las sumas totales.
 * 
 * @param {string} col Id de columna.
 *
function js_sumarTotal0(col) {
    var f1 = "f" + col + "I";
    var f2 = "f" + col + "II";
    var dec = (col === "me" || col === "te" || col === "cb" || col === "or" || col === "ex" || col === "su") ? 2 : 4;
    var sut = js_aFloat($('#'+f1).val()) + js_aFloat($('#'+f2).val());
    
    $('#t'+col).val(sut.toFixed(dec));
    
    if(col === "cf") {
        var sur = js_aFloat($('#fcrI').val()) + js_aFloat($('#fcrII').val());
        $('#tcr').val(sur.toFixed(5));
    }
} */

/**
 * Copia los resultados del final de las deudas en la cabecera de la pagina.
 */
function js_copiarTotal() {
    $('#tdordi').html($('#tor').val() + " €");
    $('#tdextr').html($('#tex').val() + " €");
    $('#tdsuma').html($('#tsu').val() + " €");
}

/**
 * Actualiza el numero de deudores y calcula el porcentaje.
 */
function js_ponerDeudores() {
    var tapa = 0;   // Total apartamentos.
    var tpor = 0;   // Porcentaje total de deuda.
    var sumt = 0;   // Suma total de deudores.
    
    // Recorre los portales.
    for(var p=1; p<=gNumPort; p++) {
        var aps = js_apartPortal(p);            // Apartamento inicial y final del portal.
        var apa = parseInt($('#pap'+p).html()); // Numero de apartamentos en el portal.
        var deu = 0;                            // Numero de deudores en el portal.
        
        // Recorre los apartamentos del portal.
        for(var i=aps[0]; i<=aps[1]; i++) {
            var nom1 = "or" + i;    // Nombre deuda ordinaria.
            var nom2 = "ex" + i;    // Nombre deuda extraordinaria.
            deu += (js_aFloat($('#'+nom1).val()) > 0 || js_aFloat($('#'+nom2).val()) > 0) ? 1 : 0;
        }
        var por = deu * 100 / apa;                      // Porcentaje de deudores del portal.
        $('#pde'+p).html(deu);                          // Numero de deudores del portal.
        $('#ppo'+p).html("(" + por.toFixed(2) + " %)"); // Porcentaje de deudores del protal.
        sumt += deu;                                    // Numero de deudores total.
    }
    tapa = parseInt($('#tap').html());              // Total apartamentos.
    tpor = sumt * 100 / tapa;                       // Porcentaje total de deudores.
    $('#tde').html(sumt);                           // Total deudores.
    $('#tdapar').html(sumt);                        // Total deudores en la cabecera.
    $('#tpo').html("(" + tpor.toFixed(2) + " %)");  // Porcentaje total de deudores al final.
    $('#tdporc').html(tpor.toFixed(2) + " %");      // Porcentaje total de deudores en la cabecera.
}

/**
 * Obtiene un numero float y si no es correcto pone 0.
 * 
 * @param {string} valor Valor a convertir.
 * @returns {Number} Numero float.
 */
function js_aFloat(valor) {
    return js_esNumeroCero(parseFloat(valor.toString().replace(/[,]/,'')));
}

/**
 * Formatea un numero ajustandolo a los decimales indicados.
 * 
 * @param {string} id Identificador del elemento.
 * @param {int} dec Numero de decimales.
 */
function js_formatear(id, dec) {
    var valor = js_aFloat($('#'+id).val());
    $('#'+id).val(valor.toFixed(dec));
}

/**
 * Si el valor pasado no es un numero valido lo convierte en 0.
 * 
 * @param {mixed} num Valor a comprobar.
 * @returns {Number} El numero o 0.
 */
function js_esNumeroCero(num) {
    if (isNaN(num)) {
        num = 0;
    }
    return num;
}

/**
 * Obtiene el primer y ultimo numero de apartamento de un portal.
 * 
 * @param {int} por Numero de portal.
 * @returns {Array} Con el primer y ultimo numero de apartamento.
 */
function js_apartPortal(por) {
    var a = [0, 0];
    switch(por) {
        case 1: a = [1, 15]; break;
        case 2: a = [16, 29]; break;
        case 3: a = [30, 41]; break;
        case 4: a = [42, 57]; break;
        case 5: a = [58, 70]; break;
        case 6: a = [71, 90]; break;
        case 7: a = [91, 106]; break;
        case 8: a = [107, 122]; break;
        case 9: a = [123, 135]; break;
        case 10: a = [136, 149]; break;
        case 11: a = [150, 164]; break;
        case 12: a = [165, 173]; break;
        case 13: a = [174, 181]; break;
        case 14: a = [182, 193]; break;
        case 15: a = [194, 209]; break;
    }
    return a;
}

//--- UTILIDADES -------------------------------------------------------------//

/**
 * Ejecuta la funcion js_soloNumeros() de forma automatica.
 */
$(function () {
    js_soloNumeros();
});

/**
 * Hace que todos los INPUT que sean de clase 'solonumeros' solo permitan el uso
 * de numeros y el punto decimal (una vez), los otros caracteres seran ignorados.
 * Al recibir el foco se seleccionara el todo el texto.
 */
function js_soloNumeros() {
    // Solo permite numeros y el punto no repetido.
    $(".solonumeros").keydown(function (event) {
        if (event.shiftKey === true) {
            event.preventDefault();
        }
        
        // Numeros 0 a 9 normales.                          Numeros 0 a 9 numerico.                          Borrar.                Tabulador.             Flecha atras.           Flecha adelante.        Suprimir.               Punto numerico.          Punto.                   Guion numerico.          Guion.
        if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode === 8 || event.keyCode === 9 || event.keyCode === 37 || event.keyCode === 39 || event.keyCode === 46 || event.keyCode === 110 || event.keyCode === 190 || event.keyCode === 109 || event.keyCode === 173) {
 
        } else {
            event.preventDefault();
        }
        
        // Solo permite un punto.
        if($(this).val().indexOf('.') !== -1 && (event.keyCode === 110 || event.keyCode === 190)) {
            event.preventDefault();
        }
        
        // Solo permite un menos.
        if($(this).val().indexOf('-') !== -1 && (event.keyCode === 109 || event.keyCode === 173)) { 
            event.preventDefault();
        }
        
        // El menos solo puede estar al principio.
        if($(this).val().length > 0 && (event.keyCode === 109 || event.keyCode === 173)) {
            event.preventDefault();
        } 
    });
    
    // Selecciona el contenido cuando recibe el foco.
    $(".solonumeros").focus(function () { $(this).select(); });

}

/**
 * Comprueba que no hay ningun boton activado, esto significa que falta algo por grabar.
 * 
 * @returns {Boolean} Devuelve true si hay algun dato por grabar, sino devuelve false.
 */
function js_comprobarBotones() {
    for (var i=0; i<=gNumPort; i++) {
        if ( $('#boton' + i) && $('#boton' + i).prop('disabled') === false ) {
            if (confirm("El dato número " + i + " no se ha guardado. ¿Quieres grabarlo?") === true) {
                $('#boton' + i).focus();
                return false;
            }
        } 
    }
    return true;
}

//--- TOOLTIPS ---------------------------------------------------------------//

/**
 * Crea los tooltips.
 */
$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});

function js_existeTooltip(apa, txt) {
    var id = "#apartamento" + apa;
    if( $(id).attr('data-original-title') !== "" ) {
        $(id).tooltip({
            title: txt, 
            placement: 'left', 
            trigger: 'manual', 
            animation: false 
        }); 
        $(id).css('cursor','pointer'); 
        $(id).css('color','green');
        $(id).click(function() { $(id).tooltip('toggle'); }); 
        $(id).tooltip('show');
    } 
}

/**
 * Oculta los tooltips que no valen.
 * 
 * @param {boolean} ch Si es true muestra el tooltip sino lo aculta.
 */
function js_eliminarTooltips(ch) {
    for (var i=0; i<=gNumPort; i++) {
        if ( ch === true ) {
            $('#apartamento' + i).tooltip('show');
        } else { 
            $('#apartamento' + i).tooltip('hide');
        }
    }
}

//--- POPOVERS ---------------------------------------------------------------//

/**
 * Crea los Popover.
 */
$(function () {
    $('[data-toggle="popover"]').popover({
        trigger: 'focus',
        html: true,
        title: '',
        content: js_popoverContenido(),
        container: 'body',
        placement: 'top',
        boundary: 'window',
        delay: { 'show': 100, 'hide': 200 }
    });
});

/**
 * Crea el contenido del popover.
 * 
 * @returns {String} Codigo HTML del popover.
 */
function js_popoverContenido() {
    var h = "";
    // Botones de los portales.
    for(var i=1; i<=gNumPort; i++) {
        h += "<a class=\"btn btn-outline-primary btn-portal\" href=\"#ini" + i + "\" role=\"button\" onclick=\"$(this).popover('hide');\">" + i + "</a>";
        h += (i % 3 === 0) ? "<br />" : "";
    }
    h += "<a class=\"btn btn-outline-primary btn-portal\" href=\"#iniciolista\" role=\"button\" onclick=\"$(this).popover('hide');\"><span class=\"oi oi-arrow-thick-top\"></span></a><a class=\"btn btn-outline-danger btn-portal\" href=\"javascript:void(0);\" role=\"button\" onclick=\"$(this).popover('hide');\"><span class=\"oi oi-x\"></span></a><a class=\"btn btn-outline-primary btn-portal\" href=\"#finallista\" role=\"button\" onclick=\"$(this).popover('hide');\"><span class=\"oi oi-arrow-thick-bottom\"></span></a>";
    return h;
}

//--- VOTACIONES -------------------------------------------------------------//

/**
 * Pone los datos de las opciones para las votaciones.
 * 
 * @param {int} op Numero de opcion.
 */
function js_ponerEtiquetas(op) {
    // Lee los valores de la opcion.
    var o = $('#opcion' + op).val();
    
    // Si etá vacío pone Opc. X
    o = (o === "") ? "Opc. " + op : o;
    
    // Pone los datos en las cabeceras.
    for(var i=1; i<=gNumPort; i++) {
        $('#titop' + op + i).attr('title',o);
        $('#titop' + op + i).html(o.substr(0,8));
    }
}

/**
 * Marca las opciones de voto y presente cuando se marca o desmarca la de asistencia.
 * 
 * @param {string} id Identificador de la casilla.
 * @param {int} a Número de apartamento actual.
 * @param {array} ap Apartamentos del propietario.
 * @param {boolean} ch Chequear o no chequear.
 */
function js_marcarOpc(id, a, ap, ch) {
    var x, i;
    
    a  = (!Array.isArray(a)) ? [a] : a;
    ap = (!Array.isArray(ap)) ? [ap] : ap;
    i  = ($("#sincro").prop("checked") === true) ? ap : a;
    
    if(id.substr(0,4) === "asis") {
        for(x in i) {
            $("#asis"+i[x]).prop("checked",ch);
            $("#vota"+i[x]).prop("checked",ch);
            $("#pres"+i[x]).prop("checked",ch);
        }
    } else if(id.substr(0,4) === "vota") {
        for(x in i) {
            $("#vota"+i[x]).prop("checked",ch);
        }
    } else if(id.substr(0,4) === "pres") {
        for(x in i) {
            $("#pres"+i[x]).prop("checked",ch);
        }
    }
    js_comprOpc(i);
}

/**
 * Activa o desactiva las casillas de las votaciones según la asistencia.
 * 
 * @param {array} i Números de apartamentos.
 *
 */
function js_comprOpc(i) {
    var x;
    if(!Array.isArray(i)) {
        i = [i];
    }
    for(x in i) {
        if($("#asis"+i[x]).prop("checked") === false) {
            // No asiste. Desactiva todo.
            $("#vota"+i[x]).prop("disabled",true);
            $("#pres"+i[x]).prop("disabled",true);
            $("#res1"+i[x]).prop("disabled",true);
            $("#res2"+i[x]).prop("disabled",true);
            $("#res3"+i[x]).prop("disabled",true);
            $("#res4"+i[x]).prop("disabled",true);
        } else {
            // Sí asiste. Activa el voto y presente.
            $("#vota"+i[x]).prop("disabled",false);
            $("#pres"+i[x]).prop("disabled",false);

            // Comprueba si la casilla voto o presente están elegidas.
            if($("#vota"+i[x]).prop("checked") === false || $("#pres"+i[x]).prop("checked") === false) {
                // Alguna de las dos no está elegida. Desactiva.
                $("#res1"+i[x]).prop("disabled",true);
                $("#res2"+i[x]).prop("disabled",true);
                $("#res3"+i[x]).prop("disabled",true);
                $("#res4"+i[x]).prop("disabled",true);
            } else {
                // Ambas están elegidas. Activa.
                $("#res1"+i[x]).prop("disabled",false);
                $("#res2"+i[x]).prop("disabled",false);
                $("#res3"+i[x]).prop("disabled",false);
                $("#res4"+i[x]).prop("disabled",false);
            }
        }
    }
    js_sumarAsistentes();
}

/**
 * Sincroniza los votos de un propietario con varios apartamentos.
 * 
 * @param {array} i Códigos de apartamentos.
 * @param {int} p Numero de opcion.
 */
function js_sincronizar(i,p) {
    var x;
    
    // Comprueba si la casilla de sincronizar se ha marcado.
    if($("#sincro").prop("checked") === true) {
        // Sincroniza los votos
        if(!Array.isArray(i)) {
            i = [i];
        }
        // Marca los apartamentos del propietario.
        for(x in i) {
            $("#res"+p+i[x]).prop("checked",true); 
        }
    }
    // Recalcula los datos.
    js_sumarVotos();
}

/**
 * Pone en la cabecera del formulario de votaciones la suma de datos de asistencia.
 */
function js_sumarAsistentes() {
    var v1=0, v2=0, v3=0, v4=0;
    
    // Recorre los apartamentos.
    for(var i=1; i<=gNumApar; i++) {
        asi = document.getElementById("asis"+i);
        vot = document.getElementById("vota"+i);
        pre = document.getElementById("pres"+i);
        
        // Suma los asistentes, votos y presentes.
        v1 += (asi.disabled === false && asi.checked === true) ? 1 : 0;
        v2 += (vot.disabled === false && vot.checked === true) ? 1 : 0;
        v4 += (pre.disabled === false && pre.checked === true) ? 1 : 0;
    }
    // Sin voto.
    v3 = v1 - v2;
    
    // Pone los valores.
    $("#sumasis").val(v1);
    $("#sumvota").val(v2);
    $("#sumnovoto").val(v3);
    $("#sumpres").val(v4);
    
    // Recalcula los datos.
    js_sumarVotos();
}

/**
 * Recalcula los datos de los votos y coeficientes, después los pone en la web.
 * 
 * @global array coeficienteJS
 */
function js_sumarVotos() {
    var v1=0, v2=0, v3=0, v4=0;
    var c, c1f=0, c2f=0, c3f=0, c4f=0;
    var svot, scof;
    var s, opc, cof;
    
    // Recorre los apartamentos.
    for(var i=1; i<=gNumApar; i++) {
        // Obtiene las opciones disponibles de cada apartamento.
        opc = document.getElementsByName("opciones["+i+"]");
        
        // Recorre las opciones del apartamento.
        for(var j=0; j<opc.length; j++) { 
            // Comprueba que la opción esté activada y chequeada.
            if(opc[j].disabled === false && opc[j].checked === true) {
                // La opción está chequeada.
                s = opc[j].value;       // Número de opción: 0, 1, 2, 3.
                c = coeficienteJS[i];   // Coeficientes del apartamento.
                cof = c[0];             // Coeficiente fase.
                switch(j) {
                    case 0 : v1++; c1f += cof; break;
                    case 1 : v2++; c2f += cof; break;
                    case 2 : v3++; c3f += cof; break;
                    case 3 : v4++; c4f += cof; break;
                }
            }
        }
    }
    svot = v1+v2+v3+v4;
    scof = c1f+c2f+c3f+c4f;
    
    $("#votos1").val(v1);
    $("#votos2").val(v2);
    $("#votos3").val(v3);
    $("#votos4").val(v4);
    $("#sumvotos").val(svot);
    
    // Coeficiente fase.
    $("#cof1").val(c1f.toFixed(4));
    $("#cof2").val(c2f.toFixed(4));
    $("#cof3").val(c3f.toFixed(4));
    $("#cof4").val(c4f.toFixed(4));
    $("#sumcof").val(scof.toFixed(4));
}

/**
 * Oculta o muestra las filas de los no asistentes.
 * 
 * @param {Boolean} oc Indica si se oculta o se muestra.
 * @returns {Boolean} false.
 */
function js_ocultarFilas(oc) {
    var fila, opci;

    for(var i=1; i<=gNumApar; i++) {
        fila = $("#fila"+i);
        opci = $("#asis"+i).prop('checked');
        if(opci === false && oc === true) {
            fila.hide();
        } else {
            fila.show();
        } 
    } 
    js_ocultarTitulos(oc);
    return false;
}

/**
 * Oculta o muestra los titulos de la cabecera.
 * 
 * @param {Boolean} oc Indica si se oculta o se muestra.
 */
function js_ocultarTitulos(oc) {
    var fila, filas, numfi, id, por='';
    var v=0, o=0;
    filas = document.getElementById("tablavot").rows;
    numfi = filas.length;
    
    for(var i=0; i<numfi; i++) {
        fila = filas[i];
        if (oc === true) {
            if ( $(fila).is(":visible") ) {
                id = $(fila).prop("id");
                if(id.substr(0,6) === "portal") {
                    if(por !== "") {
                        $('#'+por).hide();
                    }
                    por = id;
                } else {
                    por = "";
                }
            } 
        } else {
            $(fila).show();
        }
    }
}

/**
 * Oculta o muestra las filas de los no deudores.
 * 
 * @param {Boolean} oc Indica si se oculta o se muestra.
 * @returns {Boolean} false.
 */
function js_ocultarNoDeudores(oc) {
    var fila, titu, suma, foco, aps, nom1, nom2, deu, pdeu;
    
    // Guarda el foco actual.
    foco = $('#ultimofoco').val();
    
    for(var p=1; p<=gNumPort; p++) {
        aps = js_apartPortal(p);    // Apartamento inicial y final del portal.
        pdeu = false;               // Numero de deudores en el portal.
        titu = $("#titp"+p);        // Nombre de la fila del titulo.
        suma = $("#sump"+p);        // Nombre de la fila de sumas.
        
        // Recorre los apartamentos del portal.
        for(var i=aps[0]; i<=aps[1]; i++) {
            fila = $("#fila"+i);    // Nombre de la fila.
            nom1 = "or" + i;        // Nombre deuda ordinaria.
            nom2 = "ex" + i;        // Nombre deuda extraordinaria.
            deu  = (js_aFloat($('#'+nom1).val()) > 0 || js_aFloat($('#'+nom2).val()) > 0) ? true : false;    // Hay deuda o no.
            pdeu = (deu === true) ? true : pdeu;    // Hay deudas en el portal o no.
            
            if(oc === true) {
                // Estamos ocultando filas.
                if(deu === true) {
                    fila.show();    // Tiene deudas, muestra la fila.
                } else {
                    fila.hide();    // No tiene deudas, oculta la fila.
                }
            } else {
                // Estamos mostrando filas. Las muestra todas.
                fila.show();
            }
        }
        
        // Si estamos ocultando, oculta la cabecera y las sumas si hay deudas.
        if(oc === true) {
            // Estamos ocultando filas.
            if(pdeu === false) {
                // Si el portal no tiene deudas oculta los titulos y sumas.
                titu.hide();
                suma.hide();
            }
        } else {
            // Estamos mostrando filas, muestra los titulos y sumas.
            titu.show();
            suma.show();
        }
        
    }
    
    // Vuelve a poner el foco.
    if(foco !== "") { 
        $('#'+foco).focus();
    }
    return false;
}

/**
 * Pide confirmacion antes de cambiar la fecha de votacion.
 * 
 * @param {date} nueva Fecha nueva.
 * @param {date} vieja Fecha vieja.
 */
function js_cambiarFechaVotacion(nueva, vieja) {
    if (nueva !== vieja && nueva) {
        if (confirm("Si cambias la fecha se perderán todos los datos que no hayan sigo grabados.\n¿Seguro que quieres cambiar la fecha " + vieja + " por la nueva fecha " + nueva + "?")) {
            xajax_setVotacionDatosForm(nueva, 1);
            $('#fechainicial').val(nueva);
        } else {
            $('.calendario').datepicker('destroy');
            $('#fecha').val($('#fechainicial').val());
            xajax_setCalendario(false, true);
        }
    }
}

/**
 * Pide confirmacion antes de cambiar el numero de votacion.
 * 
 * @param {int} nuevo Numero nuevo.
 * @param {int} viejo Numero viejo.
 */
function js_cambiarNumeroVotacion(nuevo, viejo) {
    if (nuevo !== viejo) {
        if (confirm("Si cambias el número de votación se perderán todos los datos que no hayan sigo grabados.\n¿Seguro que quieres cambiar la votación " + viejo + " por la nueva votación " + nuevo + "?")) {
            xajax_setVotacionDatosForm($('#fecha').val(), nuevo);
        } else {
            $('#votacion').val($('#votacioninicial').val());
        }
    }
}

/**
 * Pide confirmacion antes de deshacer los cambios de un acta.
 * 
 * @param {date} fecha Fecha del acta.
 */
function js_deshacerActa(fecha) {
    if (confirm("Si deshaces todos los cambios no guardados se perderán.\n¿Seguro que quieres continuar?")) {
        xajax_getActaDatosForm(fecha);
    }
}

/**
 * Pide confirmacion antes de eliminar un acta.
 * 
 * @param {date} fecha Fecha del acta.
 */
function js_eliminarActa(fecha) {
    if (confirm("Se va a eliminar el acta actual, los datos no se podrán recuperar.\n¿Seguro que quieres continuar?")) {
        xajax_eliminarActa(fecha);
    }
}

/**
 * Pide confirmacion antes de eliminar los datos de una Junta.
 * 
 * @param {date} fecha Fecha de la Junta.
 */
function js_eliminarJunta(fecha) {
    if (confirm("Se van a eliminar todos los datos de la Junta actual, los datos no se podrán recuperar.\n¿Seguro que quieres continuar?")) {
        xajax_eliminarJunta(fecha);
    }
}

/**
 * Pide confirmacion antes de eliminar los datos de los asistentes a una Junta.
 * 
 * @param {date} fecha Fecha de la Junta.
 */
function js_eliminarAsistentes(fecha) {
    if (confirm("¿Quieres eliminar también los datos de los asistentes a la Junta?\nLos datos no se podrán recuperar.")) {
        xajax_eliminarAsistentes(fecha);
    }
}

//--- LISTADOS ---------------------------------------------------------------//

/**
 * Activa o desactiva el boton de imprimir.
 */
function js_cuotasMensuales() {
    if($('#cantidad').val() > 0 && $('#meses').val() > 0) { 
        $('#imprimir').prop('disabled',false);
        $('#imprimirpdf').prop('disabled',false);
        xajax_getCalculos(xajax.getFormValues('frmdatos'));
    } else { 
        $('#imprimir').prop('disabled',true);
        $('#imprimirpdf').prop('disabled',true);
        $('#divbusqueda').html('');
    }
}

/**
 * Activa o desactiva el boton de imprimir.
 */
function js_cantidadTotal() {
    if($('#cuota').val() > 0) { 
        $('#imprimir').prop('disabled',false);
        $('#imprimirpdf').prop('disabled',false);
        xajax_getCantidadTotal(xajax.getFormValues('frmdatos'));
    } else { 
        $('#imprimir').prop('disabled',true);
        $('#imprimirpdf').prop('disabled',true);
        $('#divbusqueda').html('');
    }
}

/**
 * Controla que el portal inicial no sea mayor que el final.
 */
function js_rangoPortales() {
    var v1 = parseInt($('#portal1').val());
    var v2 = parseInt($('#portal2').val());
    if (v1 > v2) {
        $('#portal2').val(v1);
    }
}

/**
 * Activa o desactiva las opciones de las fechas segun lo elegido.
 */
function js_controlFechas() {
    // Activa y desactiva.
    $('#completo').prop('disabled', $('#actuales').prop('checked'));
    $('#puntuales').prop('disabled', $('#actuales').prop('checked'));
    $('#fechaini').prop('disabled', $('#actuales').prop('checked'));
    $('#fechafin').prop('disabled', $('#actuales').prop('checked'));
    
    if ($('#actuales').prop('checked') === true || $('#puntuales').prop('disabled') === true || $('#completo').prop('checked') === true) {
        $('#distintos').prop('disabled', true);
    } else {
        $('#distintos').prop('disabled', false);
    }
}
