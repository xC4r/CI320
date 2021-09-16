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
                    <label for="selPeriodo">Periodo</label>
                    <div class="d-flex">
                      <div class="mr-auto">
                        <select class="form-control" id="selPeriodo" name="selPeriodo">
                          <option value="202109">202109</option>
                          <option value="202108">202108</option>
                          <option value="202107">202107</option>
                          <option value="202107">-------</option>
                        </select>
                      </div>
                      <div class="ml-1">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalNota">Nuevo</button>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#othermod">otropl</button>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 mb-2">
                    <label for="txtBuscar">Buscar</label>
                    <div class="input-group">
                      <input type="text" class="form-control rounded" placeholder="Buscar" aria-label="Buscar" id="txtBuscar">
                      <button type="button" class="btn btn-secondary ml-1"><i class="fa fa-search" id="btnBuscar"></i></button>
                      <button type="button" class="btn btn-success ml-1" id="btnExport">CSV</button>
                      <button type="button" class="btn btn-info ml-1" id="btnReload"><i class="fa fa-refresh"></i></button>
                    </div>
                  </div>

                </div>

                <div class="datatable" id="tabNotaPedido">
                  <div class="table-responsive">
                    <table class="table mb-0">
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
                  <form id="formNotaPedido">
                    <div class="modal-body">
                        <div class="form-row">
                              <div class="col-md-3 mb-3">
                                <label for="txtDocumento">Serie - Numero</label>
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <select class="form-control" id="selSerieCP" name="selSerieCP">
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
                                  <input type="date"  class="form-control" id="txtFecha" name="txtFecha" required>
                                  <div class="input-group-append">
                                    <div class="input-group-text">
                                      <input type="checkbox">
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-md-6 mb-3">
                                <label for="txtNombre">Nombre</label>
                                <input type="text" class="form-control" id="txtNombre" name="txtNombre" required>
                              </div>
                        </div>
                        <div class="form-row">
                          <div class="col-md-6 mb-3">
                              <label for="txtDireccion">Direccion</label>
                              <input type="text" class="form-control" id="txtDireccion" name="txtDireccion" required>                                     
                          </div>                                          
                          <div class="col-md-3 mb-3">
                              <label for="txtDocumento">DNI/RUC</label>
                              <input type="text" class="form-control" id="txtDocumento" name="txtDocumento" required>                                     
                          </div> 
                          <div class="col-md-3 mb-3">
                              <label for="txtReferencia">Observ - Ref</label>
                              <input type="text" class="form-control" id="txtObservacion" name="txtReferencia" required>                                     
                          </div> 
                        </div>
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalProducto">Agregar</button> 
                            </div>
                            <div class="col-md-2 mb-3"></div>
                            <div class="col-md-4 mb-3">
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text" id="txtTotal">Total</span>
                                </div>
                                <input type="text" class="form-control text-right" id="txtTotal" name="txtTotal" value="15000.00" disabled required>
                                <div class="input-group-append">
                                  <div class="input-group-text">
                                    <input type="checkbox">
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                              <div class="invalid-feedback d-block">
                                  Message invalid feedback.
                              </div>
                            </div>
                        </div>
                        <div class="datatable" id="tabItems">
                          <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                  <tr>
                                    <th scope="col">Opción</th>
                                    <th scope="col">Código</th>
                                    <th scope="col">Descripción</th>
                                    <th scope="col">Cant</th>
                                    <th scope="col">P.Unit</th>
                                    <th scope="col">Importe</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <!-- 
                                  <tr>
                                    <td><div class="btn-group" role="group"><button type="button" class="btn btn-danger btn-sm del mb-1"><i class="fa fa-trash-o"></i></button></div></td>
                                    <td>P001</td>
                                    <td>Fierro 1/2 AA</td>
                                    <td>10.30</td>
                                    <td>28.00</td>
                                    <td>280.00</td>
                                  </tr>
                                  <tr>
                                    <td><div class="btn-group" role="group"><button type="button" class="btn btn-danger btn-sm del mb-1"><i class="fa fa-trash-o"></i></button></div></td>
                                    <td>P002</td>
                                    <td>Carretilla Simple Contruccion</td>
                                    <td>10.50</td>
                                    <td>16.00</td>
                                    <td>160.00</td>
                                  </tr>
                                  <tr>
                                    <td><div class="btn-group" role="group"><button type="button" class="btn btn-danger btn-sm del mb-1"><i class="fa fa-trash-o"></i></button></div></td>
                                    <td>P003</td>
                                    <td>Calamina 022</td>
                                    <td>10.00</td>
                                    <td>22.00</td>
                                    <td>220.00</td>
                                  </tr>
                                  <tr>
                                    <td><div class="btn-group" role="group"><button type="button" class="btn btn-danger btn-sm del mb-1"><i class="fa fa-trash-o"></i></button></div></td> 
                                    <td>P004</td>
                                    <td>Calamina Galv 022 x 360</td>
                                    <td>10.20</td>
                                    <td>22.00</td>
                                    <td>220.00</td>
                                  </tr>
                                  <tr>
                                    <td><div class="btn-group" role="group"><button type="button" class="btn btn-danger btn-sm del mb-1"><i class="fa fa-trash-o"></i></button></div></td>
                                    <td>P005</td>
                                    <td>Calamina 022</td>
                                    <td>10.20</td>
                                    <td>22.00</td>
                                    <td>220.00</td>
                                  </tr> -->
                                </tbody>
                            </table>
                          </div>
                          <nav>
                            <ul class="pagination justify-content-center mb-0">
                                <li class="page-item disabled"><a class="page-link prev-link active" href="#"><i class="fa fa-arrow-left"></i></a></li>
                                <li class="page-item active"><a class="page-link num-link" href="#">1</a></li>
                                <li class="page-item disabled"><a class="page-link next-link" href="#"><i class="fa fa-arrow-right"></i></a></li>
                            </ul>
                          </nav>
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
                  <form id="formAddProducto">
                    <div class="modal-body">
                      <div class="form-row">
                        <div class="col-md-7 mb-3">
                          <label for="txtProducto">Producto</label>
                          <div class="input-group">
                            <input type="text" class="form-control w-25" id="txtCodigo" name="txtCodigo">
                            <input type="text" class="form-control w-75" id="txtProducto" name="txtProducto" required>
                          </div>
                        </div>
                        <div class="col-md-2 mb-3">
                          <label for="txtCantidad">Cantidad</label>
                          <input type="text" class="form-control" id="txtCantidad" name="txtCantidad" value="1" required>
                        </div>
                        <div class="col-md-3 mb-3">
                          <label for="txtPrecio">Precio</label>
                          <div class="input-group">
                            <input type="text" class="form-control" id="txtPrecio" name="txtPrecio" required>
                            <div class="input-group-append">
                              <div class="input-group-text" style="padding-right:10px; padding-left:10px;">
                                <input type="checkbox">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="col-md-12">
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


