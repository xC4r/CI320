<div class="content-header">
  <div class="container-fluid">
    <div class="row" >
      <div class="col-xl-12">
        <h4 class="page-header">Notas de Pedido</h4>
      </div>
    </div>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="#">Contabilidad</a>
      </li>
      <li class="breadcrumb-item active">
        <a href="#">Comprobantes de Pago</a>
      </li>
    </ol>      
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-xl-12">
        <div class="card">
          <div class="card-body collapse show p-3"> 
            <h4 class="card-title mb-3">Lista Notas</h4>
            
            <div class="tab-content mb-3">
              <div class="tab-pane fade show active" role="tabpanel" aria-labelledby="home-tab">
                <div class="form-row">
                  <div class="col-md-6 mb-2">
                    <div class="input-group">
                      <input type="text" class="form-control rounded" placeholder="Buscar" aria-label="Buscar" id="txtBuscar">
                      <button type="button" class="btn btn-secondary ml-1"><i class="fa fa-search" id="btnBuscar"></i></button>
                      <button type="button" class="btn btn-success ml-1" id="btnExport">CSV</button>
                      <button type="button" class="btn btn-info ml-1" id="btnReload"><i class="fa fa-refresh"></i></button>
                    </div>
                  </div>
                  <div class="col-md-6 mb-2"> 
                    <button type="button" class="btn btn-primary mb-1" data-toggle="modal" data-target="#modalNota">Nuevo</button>
                  </div>
                </div>

                <div class="datatable" id="tabNotaPedido">
                  <div class="table-responsive">
                    <table class="table">
                      <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Opciones</th>
                        <th scope="col">Serie</th>
                        <th scope="col">Número</th>
                        <th scope="col">Fecha Emisión</th>
                        <th scope="col">Documento</th>
                        <th scope="col">Nombre / Razón Social</th>
                        <th scope="col">Total</th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr style="">
                        <th scope="row">1</th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr style="">
                        <th scope="row">2</th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr style="">
                        <th scope="row">3</th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr style="">
                        <th scope="row">4</th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr style="">
                        <th scope="row">5</th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      </tbody>
                    </table>
                  </div>	
                </div>

              </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalNotaLabel" aria-hidden="true" id="modalNota">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Nota de Pedido</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form id="formNotaPedido" autocomplete="off" >
                    <div class="modal-body">
                        <div class="form-row">
                              <div class="col-md-3 mb-3">
                                <label for="txtDocumento">Serie - Numero</label>
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <select class="custom-select" id="selSerieCP" name="selSerieCP">
                                      <option value="N001">N001</option>
                                      <option value="N002">N002</option>
                                      <option value="N003">N003</option>
                                    </select> 
                                  </div>
                                  <input type="text" class="form-control" id="txtNumeroCP" name="txtNumeroCP" required>
                                </div>
                              </div>
                              <div class="col-md-3 mb-3">
                                <label for="txtFecha">Fecha</label>
                                <div class="input-group">
                                  <input type="text" class="form-control" id="txtFecha" name="txtFecha" required>
                                  <div class="input-group-append">
                                    <div class="input-group-text">
                                      <input type="checkbox">
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6 mb-3">
                                <label for="txtNombre">Nombre</label>
                                <input type="text" class="form-control" id="txtNombre" name="txtNombre" autocomplete="off" required>
                              </div>
                        </div>
                        <div class="form-row">
                          <div class="col-md-6 mb-3">
                              <label for="txtDireccion">Direccion</label>
                              <input type="text" class="form-control" id="txtDireccion" name="txtDireccion" autocomplete="off" required>                                     
                          </div>                                          
                          <div class="col-md-3 mb-3">
                              <label for="txtDocumento">DNI/RUC</label>
                              <input type="text" class="form-control" id="txtDocumento" name="txtDocumento" autocomplete="off" required>                                     
                          </div> 
                          <div class="col-md-3 mb-3">
                              <label for="txtReferencia">Observ - Ref</label>
                              <input type="text" class="form-control" id="txtObservacion" name="txtReferencia" autocomplete="off" required>                                     
                          </div> 
                        </div>
                        <div class="form-row">
                          <div class="input-group justify-content-end">
                            <button type="button" class="btn btn-primary mb-1" data-toggle="modal" data-target="#modalProducto">+Producto</button> 
                          </div>
                        </div> 
                        <div class="form-row">
                          <div class="form-group">
                            <div class="invalid-feedback">
                              Message invalid feedback.
                            </div>
                          </div>
                        </div>
                        <div class="datatable">
                          <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="thead-dark">
                                  <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Options</th>
                                    <th scope="col">Descripción</th>
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">P.Unit</th>
                                    <th scope="col">Importe</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <th scope="row">1</th>
                                    <td>
                                      <div class="btn-group" role="group" aria-label="Basic example">
                                      <button type="button" class="btn btn-primary btn-sm edit mb-1">
                                        <i class="fa fa-pencil"> </i>
                                      </button>
                                      <button type="button" class="btn btn-danger btn-sm edit mb-1">
                                        <i class="fa fa-trash-o"> </i>
                                      </button>
                                      <button type="button" class="btn btn-danger btn-sm edit mb-1">
                                        <i class="fa fa-trash-o"> </i>
                                      </button>
                                      </div>
                                    </td> 
                                    <td>Fierro 1/2 AA</td>
                                    <td>10</td>
                                    <td>28.00</td>
                                    <td>280.00</td>
                                  </tr>
                                  <tr>
                                    <th scope="row">2</th>
                                    <td>
                                      <button type="button" class="btn btn-primary btn-sm edit mb-1">
                                        <i class="fa fa-pencil"></i>
                                      </button>
                                      <button type="button" class="btn btn-danger btn-sm edit mb-1">
                                        <i class="fa fa-trash-o"> </i>
                                      </button>
                                    </td> 
                                    <td>Fierro 3/8 Acerl</td>
                                    <td>10</td>
                                    <td>16.00</td>
                                    <td>160.00</td>
                                  </tr>
                                  <tr>
                                    <th scope="row">3</th>
                                    <td>
                                      <button type="button" class="btn btn-primary btn-sm edit mb-1">
                                        <i class="fa fa-pencil"></i>
                                      </button>
                                      <button type="button" class="btn btn-danger btn-sm edit mb-1">
                                        <i class="fa fa-trash-o"> </i>
                                      </button>
                                    </td> 
                                    <td>Calamina 022</td>
                                    <td>10</td>
                                    <td>22.00</td>
                                    <td>220.00</td>
                                  </tr>
                                  <tr>
                                    <th scope="row">4</th>
                                    <td>
                                      <button type="button" class="btn btn-primary btn-sm edit mb-1">
                                        <i class="fa fa-pencil"></i>
                                      </button>
                                      <button type="button" class="btn btn-danger btn-sm edit mb-1">
                                        <i class="fa fa-trash-o"> </i>
                                      </button>
                                    </td> 
                                    <td>Calamina 022</td>
                                    <td>10</td>
                                    <td>22.00</td>
                                    <td>220.00</td>
                                  </tr>
                                  <tr>
                                    <th scope="row">5</th>
                                    <td>
                                      <button type="button" class="btn btn-primary btn-sm edit mb-1">
                                        <i class="fa fa-pencil"></i>
                                      </button>
                                      <button type="button" class="btn btn-danger btn-sm edit mb-1">
                                        <i class="fa fa-trash-o"> </i>
                                      </button>
                                    </td> 
                                    <td>Calamina 022</td>
                                    <td>10</td>
                                    <td>22.00</td>
                                    <td>220.00</td>
                                  </tr>
                                </tbody>
                            </table>
                          </div> 
                        </div> 
                    </div>
                    <div class="modal-footer">
                      <div class="input-group justify-content-end">
                        <button type="submit" class="btn btn-outline-primary ml-2" data-toggle="modal" id="btnRegistrar">Registrar</button>
                        <button type="button" class="btn btn-outline-secondary ml-2" data-dismiss="modal">Cancelar</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalProductoLabel" aria-hidden="true" id="modalProducto">
              <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Agregar Producto</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form id="formAddProducto" autocomplete="off" >
                    <div class="modal-body">
                        <div class="form-row">
                          <div class="col-md-6 mb-3">
                            <label for="txtProducto">Producto</label>
                            <input type="text" class="form-control" id="txtProducto" name="txtProducto" autocomplete="off" required>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label for="txtCantidad">Cantidad</label>
                            <input type="text" class="form-control" id="txtCantidad" name="txtCantidad" value="01" required>
                          </div>
                          <div class="col-md-3 mb-3">
                            <label for="txtPrecio">Precio</label>
                            <div class="input-group">
                              <input type="text" class="form-control" id="txtPrecio" name="txtPrecio" required>
                              <div class="input-group-append">
                                <div class="input-group-text">
                                  <input type="checkbox">
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group">
                            <div class="invalid-feedback">
                              Message invalid feedback.
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                      <div class="input-group justify-content-end">
                        <button type="submit" class="btn btn-outline-primary ml-2" data-toggle="modal" id="btnAgregar">Agregar</button>
                        <button type="button" class="btn btn-outline-secondary ml-2" data-dismiss="modal">Cancelar</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>

          </div>                 
        </div>
      </div>
    </div>        
  </div>
</div>


