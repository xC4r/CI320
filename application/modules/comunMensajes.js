const guardar = '¿Esta seguro de guardar los datos?';
const modificar = '¿Esta seguro de modificar los datos?';
const eliminar = '¿Esta seguro de eliminar el registro?';

const guardado = 'Se registro correctamente';
const modificado = 'Se modifico el registro correctamente';
const eliminado = 'Se elimino el registro';

class msgConfirmacion {
  
    static get guardar() {
        return guardar;
    }

    static get modificar() {
        return modificar;
    }

    static get eliminar() {
        return eliminar;
    }

}

class msgRespuesta {
  
    static get guardado() {
        return guardado;
    }

    static get modificado() {
        return modificado;
    }

    static get eliminado() {
        return eliminado;
    }

}