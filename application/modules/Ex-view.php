
<div class="content-header">
  <div class="container-fluid">
    <div class="row" >
        <div class="col-xl-12">
            <h4 class="page-header">Administracion de Usuarios</h4>
        </div>
    </div>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="#">Soporte</a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">Usuarios</a>
        </li>
    </ol>

  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-xl-12">
        <div class="card card-table">
          <a class="card-header" href="#" data-target="#card_body" data-toggle="collapse" aria-expanded="false" role="button">
              <h6 class="card-title float-left mb-0">Usuarios</h6>
              <span class="float-right"><em class="fa fa-angle-down"></em></span>   
          </a>
          <div class="card-body p-3 collapse show" id="card_body">
            <ul class="nav nav-tabs mb-2" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#tab1">Usuarios</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#tab2">Roles</a>
              </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="tab1" role="tabpanel">
                    <div class="input-group mb-1 justify-content-end">
                        <button type="button" class="btn btn-outline-primary ml-1 mb-1" id="tab1_btn_regi_usua">Registrar</button>
                        <button type="button" class="btn btn-outline-secondary ml-1 mb-1"    id="tab1_btn_modi_usua">Modificar</button>
                        <button type="button" class="btn btn-outline-danger ml-1 mb-1"  id="tab1_btn_elim_usua">Eliminar</button>
                    </div>
                    <div class="input-group mb-1 justify-content-end">
                        <input  type="text"   class="form-control" placeholder="Buscar" id="tab1_txt_busc_usua">
                        <button type="button" class="btn btn-outline-success ml-1 mb-1" id="tab1_btn_expo_csv">CSV</button>
                        <button type="button" class="btn btn-outline-info ml-1 mb-1"    id="tab1_btn_carg_list"><em class="fa fa-sync-alt"></em></button>
                    </div>
                    <div class="table-responsive" id="tab1_tabl_user"></div>
                    <div class="modal fade" tabindex="-1" role="dialog" id="tab1_regi_usua" aria-hidden="true">
                      <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h4 class="modal-title">Registrar Usuario</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <form id="tab1_regi_usua_form">
                            <div class="modal-body">
                              <div class="form-row">
                                <div class="col-md-3 mb-3">
                                  <label for="txt_nomb">Nombres</label>
                                  <input type="text" class="form-control" id="tab1_regi_usua_txt_nomb" placeholder="Ejm: Juan Carlos" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                  <label for="txt_apel">Apellidos</label>
                                  <input type="text" class="form-control" id="tab1_regi_usua_txt_apel" placeholder="Ejm: Torres Rivero" required>
                                </div>
                                <div class="col-md-2 mb-3">
                                  <label for="txt_iden">DNI / CE</label>
                                  <input type="text" class="form-control" id="tab1_regi_usua_txt_docu" placeholder="Ej: 25264488" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                  <label for="txt_corr">Correo</label>
                                  <div class="input-group">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text" id="icon_corr">@</span>
                                    </div>
                                    <input type="text" class="form-control" id="tab1_regi_usua_txt_corr" placeholder="Ejm: jtorres@gmail.com" aria-describedby="icon_corr" required>
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
                                  <label for="txt_usua">Id - Codigo Usuario</label>
                                  <div class="input-group">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text" id="tab1_regi_usua_id_usua"></span>
                                    </div>
                                    <input type="text" class="form-control" id="tab1_regi_usua_txt_usua" placeholder="Ej: juan_torres" autocomplete="nope" required>
                                  </div>
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
                                <div class="col-md-12">
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
                <div class="tab-pane fade" id="tab2" role="tabpanel">
                    <div class="input-group mb-1 justify-content-end">
                        <button type="button" class="btn btn-outline-primary ml-1 mb-1" id="tab2_btn_regi_role">Registrar</button>
                        <button type="button" class="btn btn-outline-dark ml-1 mb-1"    id="tab2_btn_modi_role">Modificar</button>
                        <button type="button" class="btn btn-outline-danger ml-1 mb-1"  id="tab2_btn_elim_role">Eliminar</button>
                    </div>
                    <div class="input-group mb-1 justify-content-end">
                        <input  type="text"   class="form-control" placeholder="Buscar" id="tab2_txt_busc_role">
                        <button type="button" class="btn btn-outline-success ml-1 mb-1" id="tab2_btn_expo_csv">CSV</button>
                        <button type="button" class="btn btn-outline-info ml-1 mb-1"    id="tab2_btn_carg_list"><em class="fa fa-sync-alt"></em></button>
                    </div>
                    <div class="table-responsive" id="tab2_tabl_role"></div>
                    <div class="modal fade" tabindex="-1" role="dialog" id="tab2_regi_role" aria-hidden="true">
                      <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h4 class="modal-title">Registrar Rol</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <form id="tab2_regi_role_form">
                            <div class="modal-body">
                              <div class="form-row">
                                <div class="col-md-3 mb-3">
                                  <label for="txt_nomb">Codigo</label>
                                  <input type="text" class="form-control" id="tab2_regi_role_txt_codi" placeholder="Ejm: admi_sist" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                  <label for="txt_apel">Descripcion</label>
                                  <input type="text" class="form-control" id="tab2_regi_role_txt_desc" placeholder="Ejm: Administrador Sistema" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                  <label for="txt_esta">Estado</label>
                                  <select class="form-control" id="tab2_regi_role_txt_esta" required>
                                    <option value="ACT">Activo</option>
                                    <option value="INA">Inactivo</option>
                                    <option value="SUS">Suspendido</option>
                                    <option value="DEL">Eliminado</option>
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
                              <div class="form-row">
                                <div class="form-check">                                          
                                  <input class="form-check-input" type="checkbox" id="tab2_regi_role_chk_vali" required>
                                  <label class="form-check-label">
                                    Aceptar los terminos y condiciones.
                                  </label>
                                </div>
                              </div>
                            </div>

                            <div class="modal-footer">
                              <div class="input-group justify-content-end">
                                <button type="submit" class="btn btn-outline-primary ml-2" id="tab2_regi_role_btn_regi_role">Registrar</button>
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
  </div>
</div>
