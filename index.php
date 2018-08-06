<?php

session_start();

/*
 * Limit access to logged in users
 */
 
if(isset($_SESSION['SIGNED'])) {

    if($_SESSION['SIGNED']==0) {

        header("location: sitesimple_login.php");

    }
} else {

    header("location: sitesimple_login.php");
}

require_once('server/classes/answer.php');
require_once('server/classes/connectiondatainterface.php');
require_once('server/classes/cdis/snmcdi.php');
require_once('server/classes/dbservices.php');

require_once('server/classes/snm.php');



$snm = new SNM();
$categorias = $snm->listarCategorias();




include "listing.php";

// Create a new object Listing()
$l = new Listing();
// Connect with database
$c = $l->_connect("localhost","root","","snm");
// Get listing
$r = $l->_listing($c, '1');

if(!$r) {
    // Não foi possivel carregar a listagem
}

?>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<title>UFSC - Painel de Controle</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
	<link href="assets/plugins/jquery-ui-1.10.4/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap-3.2.0/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/plugins/font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet" />
	<link href="assets/css/animate.min.css" rel="stylesheet" />
	<link href="assets/css/style.min.css" rel="stylesheet" />
	<link href="assets/css/style-responsive.min.css" rel="stylesheet" />
	<link href="assets/css/theme/default.css" rel="stylesheet" id="theme" />
	<!-- ================== END BASE CSS STYLE ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
	<link href="assets/plugins/jquery-jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" />
	<link href="assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" />
    <!--<link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" /> -->
	<!-- ================== END PAGE LEVEL STYLE ================== -->
</head>
<body>

	<!-- begin #page-loader -->
	<div id="page-loader" class="fade in"><span class="spinner"></span></div>
	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
		<!-- begin #header -->
		<div id="header" class="header navbar navbar-default navbar-fixed-top">
			<!-- begin container-fluid -->
			<div class="container-fluid">
				<!-- begin mobile sidebar expand / collapse button -->
				<div class="navbar-header">
					<a href="index.php"><img src="assets/img/logo-site.png" height="60px" /></a>
					<button type="button" class="navbar-toggle" data-click="sidebar-toggled">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<!-- end mobile sidebar expand / collapse button -->
				
				<!-- begin header navigation right -->
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown navbar-user">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
							<img src="assets/img/user-13.jpg" alt="" /> 
							<span class="hidden-xs">Administrador</span> <b class="caret"></b>
						</a>
						<ul class="dropdown-menu animated fadeInLeft">
							<li class="arrow"></li>
							<li class="divider"></li>
							<li><a href="sitesimple_logout.php">Desconectar</a></li>
						</ul>
					</li>
				</ul>
				<!-- end header navigation right -->
			</div>
			<!-- end container-fluid -->
		</div>
		<!-- end #header -->
		
		<!-- begin #sidebar -->
		<div id="sidebar" class="sidebar">
			<!-- begin sidebar scrollbar -->
			<div data-scrollbar="true" data-height="100%">

				<ul class="nav">
					<li class="nav-header">Sistema UFSC</li>
					<li class="active">
						<a href="index.php">
						<a href="index.php">
						    <i class="fa fa-laptop"></i>
						    <span>Painel de Controle</span>
					    </a>
					</li>

                    <li class="has-sub">
						<a href="javascript:;">
						    <b class="caret pull-right"></b>
						    <i class="fa fa-user"></i>
						    <span>Cadastro</span>
						</a>
						<ul class="sub-menu">
                            <li><a href="index.php">Cadastrar Usuário</a></li>
						    <li><a href="listagem_usuarios.php">Listagem de Usuários</a></li>
						</ul>
					</li>
                    <li>
                        <a href="relatorio.php">
                            <i class="fa fa-pie-chart"></i>
                            <span>Relatórios</span>
                        </a>
                    </li>
				</ul>
				<!-- end sidebar nav -->
			</div>
			<!-- end sidebar scrollbar -->
		</div>
		<div class="sidebar-bg"></div>
		<!-- end #sidebar -->
		
		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<ol class="breadcrumb pull-right">
				<li><a href="javascript:;">Home</a></li>
				<li class="active">Painel de Controle</li>
			</ol>
			<!-- end breadcrumb -->

            <div class="portlet-header">
                <h3>
                    <i class="fa fa-tasks"></i>
                    Cadastro de Usuário <span class="mensagem_cadastro"></span>
                </h3>
            </div> <!-- /.portlet-header -->
<form id="cadastro" action="categorias_produtos.php" data-validate="parsley" class="form parsley-form">
              <!-- begin row -->
            <div class="row">
                <!-- begin col-6 -->
                <div class="col-md-6">
                    <!-- begin panel -->
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            
                            <h4 class="panel-title">Cadastro &nbsp;&nbsp;<span id="cadastro_spinner"></span></h4>
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal">

                                <div class="form-group col-md-12">
                                    <label class="col-md-3 control-label">Nome da Pessoa</label>
                                    <div class="col-md-9">
                                        <input type="text" id="categoria" name="categoria" class="form-control" data-required="true">
                                        <p></p>
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    <label class="col-md-3 control-label">Matrícula</label>
                                    <div class="col-md-9">
                                        <input type="text" id="desc" name="desc" class="form-control" data-required="true">
                                        <p></p>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                    <!-- end panel -->
                    
                        <div class="row">
                                <hr>
                                <div class="col-sm-5">
                                </div>

                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <button type="submit" id="btn_cadastrar" class="btn btn-primary">Cadastrar</button>
                                    </div>
                                </div>
                        </div> <!-- /.row -->
                
                
                </div>   <!-- end row -->
            
                <!-- end col-6 -->
                
                <div class="col-md-6">
			        <!-- begin panel -->
                    <div class="panel panel-inverse">
                        <div class="panel-heading">
                            <div class="panel-heading-btn">
                            </div>
                            <h4 class="panel-title">Listagem de Usuários &nbsp;&nbsp;<span id="listagem_spinner"></span></h4>
                        </div>
                            <div class="panel-body">
                                <table class="table" id="tabela_listagem">
                                    <thead>
                                        <tr>                                 
                                            <th>Usuário</th>
                                            <th>Matrícula</th>
                                            <th>Excluir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="cursor: pointer">
                                            <td></td>
                                            <td></td>
                                        </tr>	
									</tbody>
                                </table>
                            </div>
                    </div>
                    <!-- end panel -->
                </div>
                <!-- end col-md-6-->
                
            </div> <!-- end row -->              
</form>	
        </div> <!-- end #content -->
		
        
		
		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="assets/plugins/jquery-1.8.2/jquery-1.8.2.min.js"></script>
	<script src="assets/plugins/jquery-ui-1.10.4/ui/minified/jquery-ui.min.js"></script>
	<script src="assets/plugins/bootstrap-3.2.0/js/bootstrap.min.js"></script>
	<!--[if lt IE 9]>
		<script src="assets/crossbrowserjs/html5shiv.js"></script>
		<script src="assets/crossbrowserjs/respond.min.js"></script>
		<script src="assets/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="assets/plugins/jquery-cookie/jquery.cookie.js"></script>
	<!-- ================== END BASE JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<!--<script src="assets/plugins/gritter/js/jquery.gritter.js"></script> -->
	<script src="assets/plugins/flot/jquery.flot.min.js"></script>
	<script src="assets/plugins/flot/jquery.flot.time.min.js"></script>
	<script src="assets/plugins/flot/jquery.flot.resize.min.js"></script>
	<script src="assets/plugins/flot/jquery.flot.pie.min.js"></script>
	<script src="assets/plugins/sparkline/jquery.sparkline.js"></script>
	<script src="assets/plugins/jquery-jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
	<script src="assets/plugins/jquery-jvectormap/jquery-jvectormap-world-mill-en.js"></script>
	<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="assets/js/dashboard.js"></script>
	
<!--	<script src="assets/plugins/parsley/dist/parsley.js"></script>-->
	<script src="assets/plugins/parsley/parsley.new.js"></script>
	
	<script src="assets/js/apps.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->
	<script>
		$(document).ready(function() {
			App.init();
			Dashboard.init();
            
            spinner = addSpinner($('#listagem_spinner'), "Carregando listagem...");
            pegarCategorias(function(){
                removeSpinner(spinner);    
            });
          
            
		});
        
        
        var lock = 0, spinner, cadSpinner;
            
		
        
        
        function pegarCategorias(fn){
            
           $.ajax({
                url : 'server/soap.php',
                type: 'get',
                dataType: 'json',
                data : { q : "listar_categorias" },
                success : function(resp){
                    console.log('Received response from server: ' + JSON.stringify(resp) );
                    if(typeof resp == 'object'){
                        if( resp.status == 1){
                    
                            
                            var idcategorias, categoria, desc;
                            var tabela = $(document.getElementById('tabela_listagem')).find('tbody');
                            tabela.html('');
                            
                            for( var i=0; resp.data.length > i; i++){
                                idcategorias = resp.data[i].idcategorias;
                                categoria = resp.data[i].categoria;
                                desc = resp.data[i].desc;
                                
                                tabela.append('<tr><td>' + categoria + '</td><td>' + desc + '</td><td><li data-id="' + idcategorias + '" <li class="fa fa-trash-o action_fn" data-action="delete" data-id="' + idcategorias + '"></li></td><tr>');
                            }
                            
                        } else {

                            alert(resp.message);
                            
                        }
                        
                        
                        if(typeof fn == 'function') fn();
                        
                        return;
                        
                        
                    }
                    alert('O servidor não conseguiu realizar a solicitação enviada.');
                },
                async : 'true'
            });
        }
        
        
        $(document.getElementById('btn_cadastrar')).on('click', function(e){
		
            e.preventDefault();
            
            
            $('#btn_cadastrar').attr('disabled', 'disabled').html('Processando...');

            var pageForm = $(document.getElementById('cadastro'));

            if(pageForm.parsley('validate') !== false){
                 
                var div = $('#cadastro_spinner');
                cadSpinner = addSpinner(div);
                
                console.log('Form is ok to submit');
                var serialized = pageForm.serialize();
                
                $.ajax({
                    url : 'server/soap.php',
                    type: 'post',
                    dataType: 'json',
                    data : serialized + '&q=cadastrar_categoria',
                    success : function(data){
                        console.log('Received response from server: ' + JSON.stringify(data) );
                        if(typeof data == 'object'){
                            if(data.status == 1){
                                
                                pegarCategorias(function(){
                                    pageForm.get(0).reset();
                                    $('#btn_cadastrar').removeAttr('disabled').html('Cadastrar');
                                    removeSpinner(cadSpinner);
                                });
                                
                            } else {
                                
                                alert(data.message);
                                removeSpinner(cadSpinner);
                                
                            }
                            return;
                        }
                        alert('O servidor não conseguiu realizar a solicitação enviada.');
                    },
                    async : 'true'
                });
            }

        });
        
        
        $('.action_fn').live('click', function(e){
		
            
            var action = $(e.target).attr('data-action');
            var _id = $(e.target).attr('data-id');
             
            if(lock == 1 ){
                alert('Você tem uma operação em andamento, aguarde sua finalização.');
                return;
            }
            
            if(action == 'delete'){
                
                if(confirm('Você tem certeza que deseja deletar essa categoria?')){
                    
                    var div = $('#listagem_spinner');
                    spinner = addSpinner(div);
                    
                    lock = 1;
                    
                    $.ajax({
                        url : 'server/soap.php',
                        type: 'post',
                        dataType: 'json',
                        data : '&q=deletar_categoria&id=' + _id,
                        success : function(data){
                            console.log('Received response from server: ' + JSON.stringify(data) );
                            if(typeof data == 'object'){
                                if(data.status == 1){
                                   
                                    pegarCategorias(function(){
                                        removeSpinner(spinner);
                                        lock = 0;
                                    });

                                } else {
                                    alert(data.message);
                                    
                                    removeSpinner(spinner);
                                    lock = 0;
                                }
                                
                                
                                
                                return;
                            }
                            alert('O servidor não conseguiu realizar a solicitação enviada.');
                        },
                        async : 'true'
                    });   
                }
                return;
                
            } else { // edit
                
                
            }

        });
        
        
        
       
	</script>
	
</body>
</html>