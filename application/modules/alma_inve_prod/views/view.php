<div class="content-header">
 <div class="container-fluid">
  <div class="row">
   <div class="col-xl-12">
    <h4 class="page-header">Control de Productos</h4>
   </div>
  </div>
  <ol class="breadcrumb">
   <li class="breadcrumb-item">
    <a href="#">Almacen</a>
   </li>
   <li class="breadcrumb-item active">
    <a href="#">Inventario</a>
   </li>
  </ol>
 </div>
</div>

<div class="content">
 <div class="container-fluid">
  <div class="row">
   <div class="col-xl-12">
    <div class="card">
     <div class="card-body collapse show p-0">
      <ul class="nav nav-tabs mb-2" id="myTab" role="tablist">
       <li class="nav-item">
        <a class="nav-link p-2 active" id="home-tab" data-toggle="tab" href="#inventario" role="tab" aria-controls="inventario" aria-selected="true">Inventario</a>
       </li>
      </ul>
      <!-- Tab Content -->
      <div class="tab-content p-3">
       <div class="tab-pane fade show active" id="inventario" role="tabpanel" aria-labelledby="home-tab">
        <h4 class="card-title mb-3">Inventarios Mercancia</h4>
        <div class="form-row">
          <div class="col-md-12 mb-2">
            <label for="selInventario">Inventario</label>
            <div class="d-flex">
              <div class="mr-auto">
                <select class="form-control" id="selInventario" name="selInventario">
                  <option value="1">General</option>
                  <option value="2">Secundario</option>
                  <option value="3">Anual</option>             
                </select>
              </div>
              <div class="col-lg-6 pl-1 pr-0">
                <div class="input-group">
                  <input type="text" class="form-control rounded" placeholder="Buscar" aria-label="Buscar" id="txtBuscar">
                  <button type="button" class="btn btn-success ml-1" id="btnExport"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
                  <button type="button" class="btn btn-info ml-1" id="btnReload"><i class="fa fa-refresh"></i></button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="col-md-6 mb-2">
            <div class="input-group">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalInventario">Agregar Producto</button>
            </div>
          </div>  
          <div class="col-md-6 mb-2">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">Total Productos</span>
              </div>
              <input type="text" class="form-control text-right" id="txtCantProductos" name="txtCantProductos" value ='0' readonly>
            </div>
          </div>
        </div> 
        <div class="datatable" id="tabInventario">
          <div class="table-responsive">
            <table class="table mb-0">
              <caption>Datatable</caption>
              <thead>
              <tr>
                <th scope="col">Opciones</th>
                <th scope="col">Código</th>
                <th scope="col">Descripción de Producto</th>
                <th scope="col">Stock</th>
                <th scope="col">P. Compra</th>
                <th scope="col">P. Venta</th>
              </tr>
              </thead>
              <tbody>
              <tr>
                <td></td>
                <td>-</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td></td>                
                <td>-</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td></td>
                <td>-</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td></td>
                <td>-</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td></td>
                <td>-</td>
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

       
       <div class="tab-pane fade" id="producto" role="tabpanel" aria-labelledby="profile-tab">
          <h4 class="card-title mb-3">Productos</h4>
       </div>
       <div class="tab-pane fade" id="listaproducto" role="tabpanel" aria-labelledby="profile-tab">
          <h4 class="card-title mb-3">Listas Productos</h4>
       </div>
       
      </div>
     
      <!-- Modal Agregar Producto Inventario -->
      <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalInventarioLabel" aria-hidden="true" id="modalInventario">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Producto - Almacen</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="formInventario">
              <div class="modal-body">
                <div class="form-row">
                  <div class="col-md-3 mb-3">
                    <label for="txtRegCodigo">Código</label>
                    <input type="text" class="form-control" id="txtRegCodigo" name="txtRegCodigo" autocomplete="off" required>  
                  </div>
                  <div class="col-md-6 mb-0">
                    <label for="txtRegDescProd">Descripcion</label>
                    <input type="text" class="form-control" id="txtRegDescProd" name="txtRegDescProd" autocomplete="off" required>
                  </div>
                  <div class="col-md-3 mb-0">
                    <label for="selRegUnidad">Unidad</label>
                    <select class="form-control" id="selRegUnidad" name="selRegUnidad" required>
                      <option value="NIU">UND-Unidades</option>
                      <option value="BX">CJA-Cajas</option> 
                      <option value="BG">BLS-Bolsas</option> 
                      <option value="KGM">KG-Kilogramos</option>
                      <option value="TNE">TM-Toneladas</option>
                      <option value="LTR">LT-Litros</option>  
                      <option value="MIL">ML-Millares</option> 
                      <option value="ZZ">SRV-Servicio</option>         
                    </select>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-3 mb-3">
                    <label for="txtRegStock">Cant Stock</label>
                    <input type="number" class="form-control" id="txtRegStock" name="txtRegStock" value="1" required>
                  </div>
                  <div class="col-md-3 mb-3">
                    <label for="txtRegPcompra">Precio Compra</label>
                    <input type="text" class="form-control" id="txtRegPcompra" name="txtRegPcompra" onchange="(function(el){el.value=parseFloat(el.value).toFixed(2);})(this)" min="0" max="10000" step="0.01" value="0.00" required>
                  </div>
                  <div class="col-md-3 mb-3">
                    <label for="txtRegPventa">Precio Venta</label>
                    <input type="text" class="form-control" id="txtRegPventa" name="txtRegPventa" onchange="(function(el){el.value=parseFloat(el.value).toFixed(2);})(this)" min="0" max="10000" step="0.01" value="0.00" required>
                  </div>
                  <div class="col-md-3 mb-3">
                    <label for="selRegEstado">Estado</label>
                    <select class="form-control" id="selRegEstado" name="selRegEstado" required>
                      <option value="01">Disponible</option>
                      <option value="02">No Disponible</option>           
                    </select>
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
                  <button type="submit" class="btn btn-outline-primary ml-2" data-toggle="modal" id="btnRegistrar">Registrar</button>
                  <button type="button" class="btn btn-outline-secondary ml-2" data-dismiss="modal">Cancelar</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalMovInventarioLabel" aria-hidden="true" id="modalMovInventario">
        <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Movimientos Almacen</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="formMovInventario">
              <div class="modal-body">
                <div class="form-row">
                  <div class="col-md-2 mb-3">
                    <label for="txtMovCodigo">Código</label>
                    <input type="text" class="form-control" id="txtMovCodigo" name="txtMovCodigo" autocomplete="off" required>  
                  </div>
                  <div class="col-md-10 mb-0">
                    <label for="txtMovProducto">Producto</label>
                    <div class="input-group">
                      <input type="text" class="form-control" id="txtMovProducto" name="txtMovProducto" required> 
                      <button type="button" class="btn btn-success ml-1" id="btnExpReporteMov"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>  
                    </div>
                  </div>
                </div>
                <div class="datatable" id="tabMovInventario">
                  <div class="table-responsive">
                    <table class="table mb-0">
                      <caption>Datatable</caption>
                      <thead>
                      <tr>
                        <th scope="col">Número</th>
                        <th scope="col">Fecha Mov.</th>
                        <th scope="col">Cant Mov.</th>
                        <th scope="col">Stock Prev</th>
                        <th scope="col">Stock Post</th>
                        <th scope="col">Motivo</th>
                        <th scope="col">Tipo Operación</th>
                        <th scope="col">Comprobante</th> 
                      </tr>
                      </thead>
                      <tbody>
                      <tr>
                        <td></td>
                        <td>-</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td></td>                
                        <td>-</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td></td>
                        <td>-</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td></td>
                        <td>-</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td></td>
                        <td>-</td>
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
              <div class="modal-footer">
                <div class="input-group justify-content-end">
                  <button type="button" class="btn btn-outline-secondary ml-2" data-dismiss="modal">Cerrar</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalRegMovInventarioLabel" aria-hidden="true" id="modalRegMovInventario">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Movimiento de Inventario</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="formRegMovInventario">
              <div class="modal-body">
                <div class="form-row">
                  <div class="col-md-4 mb-3">
                    <label for="txtRegMovCodigo">Código</label>
                    <input type="text" class="form-control" id="txtRegMovCodigo" name="txtRegMovCodigo" readonly required>  
                  </div>
                  <div class="col-md-8 mb-0">
                    <label for="txtRegMovProducto">Producto</label>
                      <input type="text" class="form-control" id="txtRegMovProducto" name="txtRegMovProducto" readonly required>
                  </div>
                </div>
                <div class="form-row">
                  <div class="col-md-4 mb-3">
                    <label id="lblRegMovStock" for="txtRegMovStock">Agregar</label>
                    <input type="number" class="form-control" id="txtRegMovStock" name="txtRegMovStock" value="1" required>
                  </div>
                  <div class="col-md-8 mb-3">
                    <label for="txtRegMovMotivo">Motivo</label>
                    <input type="text" class="form-control" id="txtRegMovMotivo" name="txtRegMovMotivo" required>
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
                  <button type="submit" class="btn btn-outline-primary ml-2" data-toggle="modal" id="btnRegMovInventario">Aumentar</button>
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
