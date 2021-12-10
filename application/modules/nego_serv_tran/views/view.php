
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
                <div class="card card-table">
                    <a class="card-header" href="#" data-target="#card1" data-toggle="collapse" aria-expanded="true" role="button">
                        <h6 class="card-title float-left mb-0">Servicio de Transporte</h6>
                        <span class="float-right"><i class="fa fa-angle-down"></i></span>   
                    </a>
                    <a class="card-header" href="#" data-target="#card2" data-toggle="collapse" aria-expanded="false" role="button">
                        <h6 class="card-title float-left mb-0">Card Title</h6>
                        <span class="float-right"><i class="fa fa-angle-down"></i></span>   
                    </a>
                    <a class="card-header" href="#" data-target="#card3" data-toggle="collapse" aria-expanded="false" role="button">
                        <h6 class="card-title float-left mb-0">Card Title</h6>
                        <span class="float-right"><i class="fa fa-angle-down"></i></span>   
                    </a>
                    <a class="card-header" href="#" data-target="#card4" data-toggle="collapse" aria-expanded="false" role="button">
                        <h6 class="card-title float-left mb-0">Card Title</h6>
                        <span class="float-right"><i class="fa fa-angle-down"></i></span>   
                    </a>
                    <a class="card-header" href="#" data-target="#card5" data-toggle="collapse" aria-expanded="false" role="button">
                        <h6 class="card-title float-left mb-0">Card Title</h6>
                        <span class="float-right"><i class="fa fa-angle-down"></i></span>   
                    </a>
                    <div class="card-body p-2 collapse show" id="card1">
                        <ul class="nav nav-tabs mb-2" id="myTab" role="tablist">
                          <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Usuarios</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Modificar</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="profile-tab2" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Modificar</a>
                          </li>

                        </ul>
                        <div class="tab-content " id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="input-group mb-1 justify-content-end">
                                    <button type="button" class="btn btn-outline-primary ml-1 mb-1">Registrar</button>
                                    <button type="button" class="btn btn-outline-warning ml-1 mb-1">Modificar</button>
                                    <button type="button" class="btn btn-outline-dark ml-1 mb-1">Eliminar</button>
                                </div>
                                <div class="input-group mb-2">
                                  <input type="text" class="form-control" placeholder="Usuario..." aria-label="Buscar">
                                  <div class="input-group-append">
                                    <button class="btn btn-primary" type="button"><i class="fa fa-search"></i></button>
                                  </div>
                                </div>
                                <div class="input-group mb-1 justify-content-end">
                                    <button id="expXLS" type="button" class="btn btn-outline-success ml-1 mb-1">CSV</button>
                                    <button id="btn_reload" type="button" class="btn btn-outline-info  ml-1 mb-1"><i class="fa fa-sync-alt"></i></button>
                                </div>
                                <div class="table-responsive mb-2">
                                    <table class="table table-hover table-sm" id="exportTable">
                                      <caption>Tabla de usuarios</caption>
                                      <thead class="thead-dark" >
                                        <tr>
                                          <th scope="col">#</th>
                                          <th scope="col">Name</th>
                                          <th scope="col">Technology</th>
                                          <th scope="col">Resumes</th>
                                          <th scope="col">Title</th>
                                          <th scope="col">Score</th>
                                          <th scope="col">Average</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr>
                                          <th scope="row">1</th>
                                          <td>ABCDED</td>
                                          <td>Developer</td>
                                          <td>17</td>
                                          <td>5 years </td>
                                          <td>30</td>
                                          <td>30</td>
                                        </tr><tr>
                                          <th scope="row">2</th>
                                          <td>ABCDED</td>
                                          <td>Software</td>
                                          <td>17</td>
                                          <td>5 years </td>
                                          <td>30</td>
                                          <td>30</td>
                                        </tr><tr>
                                          <th scope="row">3</th>
                                          <td>ABCDED</td>
                                          <td>Software Developer</td>
                                          <td>17</td>
                                          <td>5 years experience</td>
                                          <td>30</td>
                                          <td>30</td>
                                        </tr><tr>
                                          <th scope="row">4</th>
                                          <td>ABCDED</td>
                                          <td>Software Developer</td>
                                          <td>17</td>
                                          <td>5 years experience</td>
                                          <td>30</td>
                                          <td>30</td>
                                        </tr><tr>
                                          <th scope="row">5</th>
                                          <td>ABCDED</td>
                                          <td>Software Developer</td>
                                          <td>17</td>
                                          <td>5 years experience</td>
                                          <td>30</td>
                                          <td>30</td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div>

                            </div>

                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                              
                            </div>
                          
                        </div>
                    </div>
                    <div class="card-body p-2 collapse" id="card2">              
                    </div>
                    <div class="card-body p-2 collapse" id="card3">              
                    </div>
                    <div class="card-body p-2 collapse" id="card4">              
                    </div>
                    <div class="card-body p-2 collapse" id="card5">              
                    </div>
                </div>
            </div>

 


        </div>        
    </div>
</div>

