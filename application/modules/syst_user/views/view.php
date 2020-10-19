
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
        <div class="card" id="card001">
          <a class="card-header" href="#" data-target="#coll_01" data-toggle="collapse" aria-expanded="false" role="button" hidden>
            <h6 class="card-title float-left m-0">Card Title</h6>
            <span class="float-right"><i class="fa fa-angle-down"></i></span> 
          </a>
          <div class="card-body collapse show p-3" id="coll_01">
            <h5 class="card-title mb-3">Usuarios</h5>
            <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Usuarios</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Modificar</a>
              </li>
            </ul>
            <div class="tab-content mb-3" id="myTabContent">
              <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="form-row">
                  <div class="col-md-6 mb-2"> 
                    <button id="add" type="button" class="btn btn-primary mb-1" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus"></i></button>
                  </div>
                  <div class="col-md-6 mb-2">
                    <div class="input-group">
                      <input type="text" class="form-control rounded" placeholder="Buscar" aria-label="Buscar" id="txt_buscar">
                      <button type="button" class="btn btn-secondary ml-1"><i class="fa fa-search" id="btn_buscar"></i></button>
                      <button type="button" class="btn btn-success ml-1" id="btn_export">CSV</button>
                      <button type="button" class="btn btn-info ml-1" id="btn_reload"><i class="fa fa-sync-alt"></i></button>
                    </div>
                  </div>
                </div>
                <div class="datatable" id="tabla1">
                </div>
                <div class="datatable" id="tabla2">                 
                  <div class="table-responsive">
                    <table class="table table-sm">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>correo usuario talbe</th>
                          <th>cvlee </th>
                          <th>codigo usuaroot</th>
                          <th>nombre documento identif</th>
                          <th>estado</th>
                          <th>tell</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>tttt</td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                          <td>Table cell</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>  
                  <nav>
                    <ul class="pagination justify-content-center"></ul>
                  </nav>
                </div>
              </div>
              <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
              </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" id="exampleModal">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Registrar Usuario</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form id="tab1_regi_usua_form" autocomplete="off">
                    <div class="modal-body">
                      <div class="form-row">
                        <div class="col-md-6 mb-3">
                          <label for="txt_nomb">Nombres</label>
                          <input type="text" class="form-control" id="tab1_regi_usua_txt_nomb" placeholder="Juan Carlos Marcesa Rivas" required>
                        </div>
                        <div class="col-md-2 mb-3">
                          <label for="txt_iden">DNI / CE</label>
                          <input type="text" class="form-control" id="tab1_regi_usua_txt_docu" placeholder="11111111" required>
                        </div>
                        <div class="col-md-4 mb-3">
                          <label for="txt_corr">Correo</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <span class="input-group-text" id="icon_corr">@</span>
                            </div>
                            <input type="text" class="form-control" id="tab1_regi_usua_txt_corr" placeholder="jtorres@gmail.com" aria-describedby="icon_corr" required>
                          </div>
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="col-md-6 mb-3">
                          <label for="txt_empr">Empresa</label>
                          <select class="form-control" id="tab1_regi_usua_txt_empr" required>
                            <option value="0">Sin Empresa</option>
                            <option value="1">Empresa1</option>
                            <option value="2">Empresa2</option>
                            <option value="3">Empresa3</option>
                          </select>                                       
                        </div>                                          
                        <div class="col-md-3 mb-3">
                          <label for="txt_usua">Usuario</label>
                          <input type="text" class="form-control" codigo="" placeholder="juan_torres" autocomplete="off" required>
                        </div>
                        <div class="col-md-3 mb-3">
                          <label for="txt_clav">Password</label>
                          <input type="password" class="form-control" id="tab1_regi_usua_txt_clav" aria-describedby="mens_pass" autocomplete="new-password" required>
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="col-md-6 mb-3">
                          <label for="txt_role">Rol</label>
                          <select class="form-control" id="tab1_regi_usua_txt_role" required>
                            <option value="otro_role">Sin Rol</option>
                            <option value="role_maes">Rol Maestro</option>
                            <option value="role_test">Rol Pruebas</option>
                            <option value="cont_fina">Contabilidad</option>
                          </select>                                        
                        </div>                                          
                        <div class="col-md-3 mb-3">
                          <label for="txt_esta">Estado</label>
                          <select class="form-control" id="tab1_regi_usua_txt_esta" required>
                            <option value="ACT">Activo</option>
                            <option value="INA">Inactivo</option>
                            <option value="SUS">Suspendido</option>
                            <option value="DEL">Eliminado</option>
                          </select>
                        </div>
                        <div class="col-md-3 mb-3">
                          <small id="mens_pass" class="form-text text-muted">
                            Su contraseña debe tener entre 6 y 20 caracteres, minusculas, mayusculas y numeros, sin espacios.
                          </small>
                        </div>
                      </div>
                       <div class="form-row">
                        <div class="form-group">
                          <div class="invalid-feedback">
                            Message invalid feedback.
                          </div>
                        </div>
                      </div>                                         
                      <div class="form-row">
                        <div class="form-check">                                             
                          <input class="form-check-input" type="checkbox" id="tab1_regi_usua_chk_vali" required>
                          <label class="form-check-label">
                            Aceptar los terminos y condiciones.
                          </label>
                        </div>
                      </div>
                    </div>

                    <div class="modal-footer">
                      <div class="input-group justify-content-end">
                        <button type="submit" class="btn btn-outline-primary ml-2" id="tab1_regi_usua_btn_regi_usua">Registrar</button>
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

