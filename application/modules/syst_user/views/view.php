
<div class="content-header">
  <div class="container-fluid">
    <div class="row" >
      <div class="col-xl-12">
        <h4 class="page-header">Administracion de Dashboad</h4>
      </div>
    </div>
    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="#">Home</a>
      </li>
      <li class="breadcrumb-item active">
        <a href="#">subtitulo</a>
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
            <h4 class="card-title mb-3">Usuarios</h4>

            <div class="tab-content mb-3">
              <div class="tab-pane fade show active" role="tabpanel" aria-labelledby="home-tab">
                <div class="form-row">
                  <div class="col-md-6 mb-2"> 
                    <button type="button" class="btn btn-primary mb-1" data-toggle="modal" data-target="#addModal"><i class="fa fa-plus"></i></button>
                  </div>
                  <div class="col-md-6 mb-2">
                    <div class="input-group">
                      <input type="text" class="form-control rounded" placeholder="Buscar" aria-label="Buscar" id="txtBuscar">
                      <button type="button" class="btn btn-secondary ml-1"><i class="fa fa-search" id="btnBuscar"></i></button>
                      <button type="button" class="btn btn-success ml-1" id="btnExport">CSV</button>
                      <button type="button" class="btn btn-info ml-1" id="btnReload"><i class="fa fa-refresh"></i></button>
                      <button type="button" class="btn btn-danger ml-1" id="btnPDF">PDF</button>
                    </div>
                  </div>
                </div>

                <div class="datatable" id="tabUsuario">
					<div class="table-responsive">
					   <table class="table">
						  <thead>
							 <tr>
								<th scope="col">#</th>
								<th scope="col">Opciones</th>
								<th scope="col">Nombres y apellidos</th>
								<th scope="col">Correo Electronico</th>
								<th scope="col">Doc.Ident</th>
								<th scope="col">Empresa</th>
								<th scope="col">Estado</th>
								<th scope="col">Rol</th>
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
            <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true" id="addModal">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Registrar Usuario</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form id="formRegistro" autocomplete="off" >
                    <div class="modal-body">
                      <div class="form-row">
                        <div class="col-md-6 mb-3">
                          <label for="txtNombres">Nombres</label>
                          <input type="text" class="form-control" id="txtNombres" name='txtNombres' required>
                        </div>
                        <div class="col-md-2 mb-3">
                          <label for="txtDocumento">DNI / CE</label>
                          <input type="text" class="form-control" id="txtDocumento" name='txtDocumento' required>
                        </div>
                        <div class="col-md-4 mb-3">
                          <label for="txtCorreo">Correo</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text">@</span>
                            </div>
                            <input type="text" class="form-control" id="txtCorreo" name="txtCorreo" required>
                          </div>
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="col-md-6 mb-3">
                          <label for="txtEmpresa">Empresa</label>
                          <select class="form-control" id="txtEmpresa" name="txtEmpresa" required>
                          </select>                                       
                        </div>                                          
                        <div class="col-md-3 mb-3">
                          <label for="txtUsuario">Usuario</label>
                          <input type="text" class="form-control" id="txtUsuario" name="txtUsuario" autocomplete="off" required>
                        </div>
                        <div class="col-md-3 mb-3">
                          <label for="txtPassword">Password</label>
                          <div class="input-group">
                            <input type="text" class="form-control password" id="txtPassword" name="txtPassword" aria-describedby="password-error" autocomplete="new-password" required>
                            <div class="input-group-append"><span class="input-group-text"><i class="fa fa-eye-slash"></i></span></div>
                          </div>
                        </div>

                        <div class="input-group" id="show_hide_password">

                      </div>
                      <div class="form-row">
                        <div class="col-md-6 mb-3">
                          <label for="txtRol">Rol</label>
                          <select class="form-control" id="txtRol" name="txtRol" required>
                          </select>                                        
                        </div>                                          
                        <div class="col-md-3 mb-3">
                          <label for="txtEstado">Estado</label>
                          <select class="form-control" id="txtEstado" name="txtEstado" required>
                          </select>
                        </div>
                        <div class="col-md-3 mb-3">
                          <small class="form-text text-muted">
                            Su contraseña entre 6 y 20 caracteres, con una combinación de letras, números y símbolos.
                          </small>
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

          </div>                 
        </div>
      </div>
    </div>        
  </div>
</div>


