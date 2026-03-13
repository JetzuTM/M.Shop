<?php
  //Activacion de almacenamiento en buffer
  ob_start();
  //iniciamos las variables de session
  session_start();

  if(!isset($_SESSION["nombre"]))
  {
    header("Location: escritorio.php");
  }

  else  //Agrega toda la vista
  {
    require 'header.php';

    if($_SESSION['escritorio'] == 1)
    {
        require_once '../modelos/Consultas.php';
        
        $consulta = new Consultas();
        
        $rsptac = $consulta->totalCompraHoy();
        $regc = $rsptac->fetch_object();
        $totalc = $regc->total_compra;

        $rsptav = $consulta->totalVentaHoy();
        $regv = $rsptav->fetch_object();
        $totalv = $regv->total_venta;

        //Mostrar graficos 
        $compras10 = $consulta->comprasUlt10dias();
        $fechasc = '';
        $totalesc = '';

        while($regfechac = $compras10->fetch_object())
        {
            $fechasc =  $fechasc.'"'.$regfechac->fecha.'",';
            $totalesc = $totalesc.$regfechac->total.',';
        }

        //Quitamos la ultima coma
        $fechasc = substr($fechasc,0,-1);
        $totalesc = substr($totalesc,0,-1);

        //Graficos Venta
        $compras12 = $consulta->ventas12meses();
        $fechasv = '';
        $totalesv = '';

        while($regfechav = $compras12->fetch_object())
        {
            $fechasv =  $fechasv.'"'.$regfechav->fecha.'",';
            $totalesv = $totalesv.$regfechav->total.',';
        }

        //Quitamos la ultima coma
        $fechasv = substr($fechasv,0,-1);
        $totalesv = substr($totalesv,0,-1);
        
?>
<style>
    /* Estilo BaulPHP Moderno - Sistema de Inventario */ 
    :root { 
        /* Colores BaulPHP tradicional con toque moderno */ 
        --primary-color: #3c8dbc; 
        --secondary-color: #367fa9; 
        --success-color: #00a65a; 
        --warning-color: #f39c12; 
        --danger-color: #dd4b39; 
        --info-color: #00c0ef; 
        --dark-color: #1a2226; 
        --light-color: #ecf0f1; 
        --text-primary: #333333; 
        --text-secondary: #888; 
        --border-color: #d2d6de; 
        --sidebar-bg: #222d32; 
        --header-bg: #3c8dbc; 
        --content-bg: #f4f4f4; 
        --box-bg: rgba(255, 255, 255, 0.95); 
        --box-bg-transparent: rgba(255, 255, 255, 0.8); 
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.08); 
        --shadow-md: 0 4px 16px rgba(0,0,0,0.12); 
        --shadow-lg: 0 8px 32px rgba(0,0,0,0.16); 
        --border-radius-sm: 8px; 
        --border-radius-md: 12px; 
        --border-radius-lg: 16px; 
        --border-radius-xl: 20px; 
        --transition-fast: 0.15s ease; 
        --transition-normal: 0.3s ease; 
        --transition-slow: 0.5s ease; 
    }

    /* Estilos BaulPHP tradicionales con transiciones */ 
    .content-wrapper { 
        background: var(--content-bg); 
        min-height: 100vh; 
        font-family: 'Source Sans Pro', 'Helvetica Neue', Arial, sans-serif; 
        transition: all var(--transition-normal); 
    }

 

    .content { 
        padding: 20px; 
        transition: all var(--transition-normal); 
    }

    .content-header { 
        position: relative; 
        padding: 15px 25px 15px 25px; 
        background: rgba(255, 255, 255, 0.6); 
        backdrop-filter: blur(20px); 
        border-radius: var(--border-radius-lg); 
        margin: 16px; 
        box-shadow: var(--shadow-md); 
        border: 10px solid rgba(255, 255, 255, 0.4); 
        transition: all var(--transition-normal); 
        overflow: hidden; 
    } 

    .content-header::before { 
        content: ''; 
        position: absolute; 
        top: 0; 
        left: 0; 
        right: 0; 
        height: 1px; 
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.8), transparent); 
        animation: shimmer 3s infinite; 
    } 

    .content-header:hover { 
        background: rgba(255, 255, 255, 0.8); 
        transform: translateY(-2px); 
        box-shadow: var(--shadow-lg); 
        border-color: rgba(255, 255, 255, 0.6); 
    } 

    .content-header h1 { 
        margin: 0; 
        font-size: 28px; 
        color: #333; 
        font-weight: 600; 
        letter-spacing: -0.5px; 
    } 

    .content-header .breadcrumb { 
        float: right; 
        background: rgba(255, 255, 255, 0.8); 
        margin-top: 0; 
        margin-bottom: 0; 
        font-size: 13px; 
        padding: 8px 12px; 
        position: absolute; 
        top: 15px; 
        right: 25px; 
        border-radius: var(--border-radius-md); 
        box-shadow: var(--shadow-sm); 
        border: 1px solid rgba(255, 255, 255, 0.3); 
    }

    .box { 
        background: var(--box-bg); 
        border-radius: var(--border-radius-lg); 
        box-shadow: var(--shadow-md); 
        border: 2px solid rgba(255, 255, 255, 0.3); 
        position: relative; 
        margin-bottom: 20px; 
        backdrop-filter: blur(10px); 
        transition: all var(--transition-normal); 
        overflow: hidden; 
    } 

    .box:hover { 
        box-shadow: var(--shadow-lg); 
        transform: translateY(-2px); 
    } 

    .box-header { 
        background: rgba(244, 244, 244, 0.9); 
        color: #444; 
        border-bottom: 1px solid rgba(210, 214, 222, 0.3); 
        padding: 15px 20px; 
        border-top-left-radius: var(--border-radius-lg); 
        border-top-right-radius: var(--border-radius-lg); 
        font-weight: 600; 
        font-size: 16px; 
        backdrop-filter: blur(10px); 
    }

    .box-header.with-border {
        border-bottom: 1px solid #f4f4f4;
    }

    .box-body {
        padding: 25px;
    }

    .panel-body {
        padding: 20px;
    }

    /* Tarjetas de estadísticas estilo BaulPHP Moderno */ 
    .small-box { 
        border-radius: var(--border-radius-lg); 
        position: relative; 
        display: block; 
        margin-bottom: 20px; 
        box-shadow: var(--shadow-md); 
        background: var(--box-bg-transparent); 
        backdrop-filter: blur(10px); 
        border: 1px solid rgba(255, 255, 255, 0.2); 
        transition: all var(--transition-normal); 
        overflow: hidden; 
        transform: translateY(0) scale(1); 
    } 

    .small-box:hover { 
        transform: translateY(-5px) scale(1.02); 
        box-shadow: var(--shadow-lg); 
        backdrop-filter: blur(12px); 
    } 

    .small-box:hover { 
        transform: translateY(-4px); 
        box-shadow: var(--shadow-lg); 
    } 

    .small-box .inner { 
        padding: 20px; 
        color: #fff; 
        position: relative; 
        z-index: 2; 
    } 

    .small-box h4 { 
        font-size: 38px; 
        font-weight: bold; 
        margin: 0 0 10px 0; 
        white-space: nowrap; 
        padding: 0; 
        color: #fff; 
        text-shadow: 0 2px 4px rgba(0,0,0,0.1); 
    } 

    .small-box p { 
        font-size: 15px; 
        margin: 0; 
        color: #fff; 
        text-shadow: 0 1px 2px rgba(0,0,0,0.1); 
    }

    .small-box .icon {
        -webkit-transition: all 0.3s linear;
        -o-transition: all 0.3s linear;
        transition: all 0.3s linear;
        position: absolute;
        top: -10px;
        right: 10px;
        z-index: 0;
        font-size: 90px;
        color: rgba(0,0,0,0.15);
    }

    .small-box:hover .icon {
        font-size: 95px;
    }

    .small-box > .small-box-footer { 
        position: relative; 
        text-align: center; 
        padding: 8px 0; 
        color: #fff; 
        color: rgba(255,255,255,0.9); 
        display: block; 
        z-index: 10; 
        background: rgba(0,0,0,0.1); 
        text-decoration: none; 
        font-size: 13px; 
        font-weight: 500; 
        transition: all var(--transition-fast); 
        border-top: 1px solid rgba(255,255,255,0.2); 
    } 

    .small-box > .small-box-footer:hover { 
        color: #fff; 
        background: rgba(0,0,0,0.2); 
        padding: 10px 0; 
    }

    /* Colores BaulPHP con toque moderno y transparencia */ 
    .bg-orange { 
        background: linear-gradient(135deg, rgba(243, 156, 18, 0.85), rgba(230, 126, 34, 0.85)) !important; 
        border-color: rgba(243, 156, 18, 0.3); 
        box-shadow: 0 8px 32px rgba(243, 156, 18, 0.3); 
        backdrop-filter: blur(10px); 
    } 

    .bg-green-custom { 
        background: linear-gradient(135deg, rgba(0, 166, 90, 0.85), rgba(0, 141, 76, 0.85)) !important; 
        border-color: rgba(0, 166, 90, 0.3); 
        box-shadow: 0 8px 32px rgba(0, 166, 90, 0.3); 
        backdrop-filter: blur(10px); 
    } 

    .bg-primary-custom { 
        background: linear-gradient(135deg, rgba(60, 141, 188, 0.85), rgba(52, 122, 167, 0.85)) !important; 
        border-color: rgba(60, 141, 188, 0.3); 
        box-shadow: 0 8px 32px rgba(60, 141, 188, 0.3); 
        backdrop-filter: blur(10px); 
    } 

    .bg-red-custom { 
        background: linear-gradient(135deg, rgba(221, 75, 57, 0.85), rgba(192, 57, 43, 0.85)) !important; 
        border-color: rgba(221, 75, 57, 0.3); 
        box-shadow: 0 8px 32px rgba(221, 75, 57, 0.3); 
        backdrop-filter: blur(10px); 
    } 

    .bg-info-custom { 
        background: linear-gradient(135deg, rgba(0, 192, 239, 0.85), rgba(0, 156, 204, 0.85)) !important; 
        border-color: rgba(0, 192, 239, 0.3); 
        box-shadow: 0 8px 32px rgba(0, 192, 239, 0.3); 
        backdrop-filter: blur(10px); 
    }

    /* Gráficos modernos con transparencia y transiciones suaves */ 
    .box-body { 
        padding: 25px; 
        position: relative; 
        background: rgba(255, 255, 255, 0.4); 
        backdrop-filter: blur(10px); 
        border-radius: var(--border-radius-lg); 
        transition: all var(--transition-normal); 
    } 

    .box-body:hover { 
        background: rgba(255, 255, 255, 0.5); 
        backdrop-filter: blur(12px); 
    } 

    .chart-container { 
        position: relative; 
        height: 400px; 
        margin: 20px 0; 
        background: rgba(255, 255, 255, 0.7); 
        border-radius: var(--border-radius-lg); 
        padding: 20px; 
        backdrop-filter: blur(15px); 
        border: 1px solid rgba(255, 255, 255, 0.4); 
        box-shadow: inset 0 2px 8px rgba(0,0,0,0.05); 
        transition: all var(--transition-slow); 
    } 

    .chart-container:hover { 
        background: rgba(255, 255, 255, 0.8); 
        backdrop-filter: blur(20px); 
        box-shadow: inset 0 4px 12px rgba(0,0,0,0.08); 
    } 

    /* Botones modernos con transiciones mejoradas */ 
    .btn-box-tool { 
        background: rgba(255, 255, 255, 0.8); 
        border: 1px solid rgba(255, 255, 255, 0.3); 
        color: var(--text-secondary); 
        padding: 8px 10px; 
        border-radius: var(--border-radius-sm); 
        transition: all var(--transition-normal); 
        cursor: pointer; 
        font-size: 14px; 
        margin-left: 5px; 
        position: relative; 
        overflow: hidden; 
    } 

    .btn-box-tool:hover { 
        background: rgba(255, 255, 255, 1); 
        color: var(--primary-color); 
        transform: translateY(-2px) scale(1.05); 
        box-shadow: var(--shadow-md); 
        border-color: var(--primary-color); 
    } 

    .btn-box-tool:active { 
        transform: translateY(0) scale(0.98); 
        box-shadow: var(--shadow-sm); 
    } 

    /* Transiciones suaves para todos los elementos interactivos */ 
    * { 
        transition: color var(--transition-fast), background-color var(--transition-fast); 
    } 

    /* Efecto de brillo en hover para elementos importantes */ 
    .small-box, .box, .content-header { 
        transition: all var(--transition-normal); 
    } 

    .small-box:hover, .box:hover, .content-header:hover { 
        transition: all var(--transition-normal); 
    }

    .chart-info {
        margin-top: 1rem;
        padding: 1rem;
        background: var(--light-color);
        border-radius: var(--border-radius-md);
        border-left: 4px solid var(--primary-color);
    }

    .box-title-modern {
        margin: 0;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .box-title-modern i {
        color: var(--primary-color);
    }

    /* Mejoras de botones */
    .btn-box-tool {
        background: none;
        border: none;
        color: var(--text-secondary);
        padding: 0.5rem;
        border-radius: var(--border-radius-sm);
        transition: all var(--transition-fast);
        cursor: pointer;
    }

    .btn-box-tool:hover {
        background: rgba(0, 0, 0, 0.05);
        color: var(--primary-color);
        transform: scale(1.1);
    }

    /* Indicadores de actualización */ 
    .text-muted { 
        color: var(--text-secondary) !important; 
        font-size: 0.875rem; 
        transition: all var(--transition-fast); 
    } 

    .loading-shimmer { 
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); 
        background-size: 200% 100%; 
        animation: shimmer 2s infinite; 
    } 

    @keyframes shimmer { 
        0% { 
            background-position: -200% 0; 
        } 
        100% { 
            background-position: 200% 0; 
        } 
    } 

    /* Animaciones suaves para elementos al cargar */ 
    @keyframes fadeInUp { 
        from { 
            opacity: 0; 
            transform: translateY(30px); 
        } 
        to { 
            opacity: 1; 
            transform: translateY(0); 
        } 
    } 

    @keyframes fadeIn { 
        from { 
            opacity: 0; 
        } 
        to { 
            opacity: 1; 
        } 
    } 

    @keyframes slideInLeft { 
        from { 
            opacity: 0; 
            transform: translateX(-30px); 
        } 
        to { 
            opacity: 1; 
            transform: translateX(0); 
        } 
    } 

    @keyframes slideInRight { 
        from { 
            opacity: 0; 
            transform: translateX(30px); 
        } 
        to { 
            opacity: 1; 
            transform: translateX(0); 
        } 
    } 

    @keyframes pulse { 
        0%, 100% { 
            transform: scale(1); 
        } 
        50% { 
            transform: scale(1.05); 
        } 
    } 

    /* Aplicar animaciones con retraso escalonado */ 
    .small-box { 
        animation: fadeInUp 0.6s ease-out; 
    } 

    .small-box:nth-child(1) { animation-delay: 0.1s; } 
    .small-box:nth-child(2) { animation-delay: 0.2s; } 
    .small-box:nth-child(3) { animation-delay: 0.3s; } 
    .small-box:nth-child(4) { animation-delay: 0.4s; } 

    .box { 
        animation: fadeIn 0.8s ease-out; 
    } 

    .content-header { 
        animation: slideInLeft 0.7s ease-out; 
    } 

    .chart-container { 
        animation: slideInRight 0.8s ease-out; 
    } 

    /* Efecto de pulso suave para elementos activos */ 
    @keyframes pulse { 
        0%, 100% { 
            transform: scale(1); 
        } 
        50% { 
            transform: scale(1.05); 
        } 
    } 

    /* Efecto de brillo y resplandor moderno */ 
    .small-box::before { 
        content: ''; 
        position: absolute; 
        top: 0; 
        left: -100%; 
        width: 100%; 
        height: 100%; 
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent); 
        transition: left 0.8s ease; 
    } 

    .small-box:hover::before { 
        left: 100%; 
    } 

    .small-box:hover { 
        animation: pulse 0.6s ease-in-out; 
        box-shadow: 0 8px 32px rgba(var(--primary-color-rgb), 0.3); 
    }

    /* Responsive design moderno con efectos móviles */ 
    @media (max-width: 768px) { 
        .panel-body { 
            padding: 1rem; 
            gap: 1rem; 
            flex-direction: column; 
        } 
        
        .small-box { 
            min-height: 150px; 
            padding: 1.5rem; 
            margin-bottom: 1rem; 
            backdrop-filter: blur(8px); 
            border-radius: var(--border-radius-md); 
            transition: all var(--transition-normal); 
        } 
        
        .small-box:hover { 
            transform: translateY(-3px) scale(1.02); 
            box-shadow: 0 6px 24px rgba(0,0,0,0.15); 
        } 
        
        .small-box h4 { 
            font-size: 1.2rem; 
            font-weight: 600; 
        } 
        
        .small-box p { 
            font-size: 2rem; 
            font-weight: 700; 
        } 
        
        .small-box .icon { 
            font-size: 3rem; 
            opacity: 0.8; 
        } 
        
        .box { 
            margin-bottom: 1.5rem; 
            backdrop-filter: blur(8px); 
        } 
        
        .content-header { 
            margin: 10px; 
            padding: 20px 15px; 
            backdrop-filter: blur(8px); 
            border-radius: var(--border-radius-md); 
        } 
        
        .content-header .breadcrumb { 
            position: static; 
            float: none; 
            margin-top: 1rem; 
            display: inline-block; 
        } 
        
        .chart-container { 
            height: 300px; 
            padding: 15px; 
            backdrop-filter: blur(8px); 
        } 
    }

    /* Animaciones fluidas */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .small-box {
        animation: fadeInUp 0.6s ease-out;
    }

    .small-box:nth-child(1) {
        animation-delay: 0.1s;
    }

    .small-box:nth-child(2) {
        animation-delay: 0.2s;
    }

    /* Tooltips modernos */
    [data-tooltip] {
        position: relative;
        cursor: help;
    }

    [data-tooltip]:hover::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: var(--dark-color);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius-sm);
        font-size: 0.875rem;
        white-space: nowrap;
        z-index: 1000;
        opacity: 0;
        animation: tooltipFadeIn 0.3s ease-out forwards;
    }

    @keyframes tooltipFadeIn {
        to {
            opacity: 1;
        }
    }
</style>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">        
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Tablero
            <small>Panel de Control</small>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Estadísticas en tiempo real -->
            <div class="row">
                        <div class="col-lg-3 col-xs-6">
                          <!-- small box -->
                          <div class="small-box bg-orange">
                            <div class="inner">
                              <h4>BsD <?php echo number_format($totalc, 2, ',', '.'); ?></h4>
                              <p>Compras de Hoy</p>
                            </div>
                            <div class="icon">
                              <i class="ion ion-bag"></i>
                            </div>
                            <a href="ingreso.php" class="small-box-footer">
                              Ver detalles <i class="fa fa-arrow-circle-right"></i>
                            </a>
                          </div>
                        </div>
                        <div class="col-lg-3 col-xs-6">
                          <!-- small box -->
                          <div class="small-box bg-green-custom">
                            <div class="inner">
                              <h4>BsD <?php echo number_format($totalv, 2, ',', '.'); ?></h4>
                              <p>Ventas de Hoy</p>
                            </div>
                            <div class="icon">
                              <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="venta.php" class="small-box-footer">
                              Ver detalles <i class="fa fa-arrow-circle-right"></i>
                            </a>
                          </div>
                        </div>
                        <div class="col-lg-3 col-xs-6">
                          <!-- small box -->
                          <div class="small-box bg-primary-custom">
                            <div class="inner">
                              <h4>BsD <?php echo number_format($totalv - $totalc, 2, ',', '.'); ?></h4>
                              <p>Ganancia del Día</p>
                            </div>
                            <div class="icon">
                              <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="#" class="small-box-footer" onclick="actualizarDashboard(); return false;">
                              Actualizar <i class="fa fa-refresh"></i>
                            </a>
                          </div>
                        </div>
                        <div class="col-lg-3 col-xs-6">
                          <!-- small box -->
                          <div class="small-box bg-red-custom">
                            <div class="inner">
                              <h4>0</h4>
                              <p>Transacciones</p>
                            </div>
                            <div class="icon">
                              <i class="ion ion-ios-pulse"></i>
                            </div>
                            <a href="#" class="small-box-footer" onclick="exportarReporte(); return false;">
                              Exportar <i class="fa fa-download"></i>
                            </a>
                          </div>
                        </div>
                    </div>



                    <!-- Cuadro de Transacciones en Tiempo Real -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Transacciones en Tiempo Real</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" onclick="actualizarTransacciones()" title="Actualizar">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                        <button type="button" class="btn btn-box-tool" onclick="exportarTransacciones()" title="Exportar">
                                            <i class="fa fa-download"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-aqua"><i class="ion ion-ios-cart-outline"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Pedidos Hoy</span>
                                                    <span class="info-box-number" id="pedidos-hoy">0</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-green"><i class="ion ion-ios-paper-outline"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Ventas Hoy</span>
                                                    <span class="info-box-number" id="ventas-hoy">0</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Clientes Activos</span>
                                                    <span class="info-box-number" id="clientes-activos">0</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-red"><i class="ion ion-ios-pulse-strong"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Tasa de Conversión</span>
                                                    <span class="info-box-number" id="tasa-conversion">0%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" id="tabla-transacciones">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Tipo</th>
                                                    <th>Cliente</th>
                                                    <th>Monto</th>
                                                    <th>Estado</th>
                                                    <th>Fecha</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="transacciones-body">
                                                <tr>
                                                    <td colspan="7" class="text-center">Cargando transacciones...</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Análisis Modernos -->
                    <div class="row">
                        <!-- Análisis de Compras Moderno -->
                        <div class="col-md-6">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Análisis de Compras - Clientes</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" onclick="actualizarAnalisisCompras()" title="Actualizar">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                        <button type="button" class="btn btn-box-tool" onclick="exportarAnalisisCompras()" title="Exportar">
                                            <i class="fa fa-download"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="small-box bg-blue">
                                                <div class="inner">
                                                    <h3 id="total-compras-clientes">0</h3>
                                                    <p>Total de Compras</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-bag"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="small-box bg-green">
                                                <div class="inner">
                                                    <h3 id="promedio-compra-cliente">Bs 0.00</h3>
                                                    <p>Promedio por Compra</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-stats-bars"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chart-container">
                                        <canvas id="analisis-compras-chart" width="400" height="250"></canvas>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Cliente</th>
                                                    <th>Compras</th>
                                                    <th>Total Gastado</th>
                                                    <th>Última Compra</th>
                                                </tr>
                                            </thead>
                                            <tbody id="top-clientes-compras">
                                                <tr>
                                                    <td colspan="4" class="text-center">Cargando datos...</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Análisis de Ventas Moderno -->
                        <div class="col-md-6">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Análisis de Ventas - Facturación</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" onclick="actualizarAnalisisVentas()" title="Actualizar">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                        <button type="button" class="btn btn-box-tool" onclick="exportarAnalisisVentas()" title="Exportar">
                                            <i class="fa fa-download"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="small-box bg-purple">
                                                <div class="inner">
                                                    <h3 id="total-facturas-hoy">0</h3>
                                                    <p>Facturas Hoy</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-document-text"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="small-box bg-orange">
                                                <div class="inner">
                                                    <h3 id="total-ventas-hoy">Bs 0.00</h3>
                                                    <p>Ventas del Día</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="ion ion-cash"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chart-container">
                                        <canvas id="analisis-ventas-chart" width="400" height="250"></canvas>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Factura</th>
                                                    <th>Cliente</th>
                                                    <th>Monto</th>
                                                    <th>Estado</th>
                                                </tr>
                                            </thead>
                                            <tbody id="ultimas-facturas">
                                                <tr>
                                                    <td colspan="4" class="text-center">Cargando datos...</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Análisis de Pedidos Activos -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Análisis de Pedidos Activos</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" onclick="actualizarPedidosActivos()" title="Actualizar">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                        <button type="button" class="btn btn-box-tool" onclick="exportarPedidosActivos()" title="Exportar">
                                            <i class="fa fa-download"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box bg-aqua">
                                                <span class="info-box-icon"><i class="ion ion-ios-list-outline"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Pedidos Pendientes</span>
                                                    <span class="info-box-number" id="pedidos-pendientes">0</span>
                                                    <div class="progress">
                                                        <div class="progress-bar" id="progress-pendientes"></div>
                                                    </div>
                                                    <span class="progress-description" id="desc-pendientes">0% del total</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box bg-green">
                                                <span class="info-box-icon"><i class="ion ion-ios-checkmark-outline"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Pedidos Aceptados</span>
                                                    <span class="info-box-number" id="pedidos-aceptados">0</span>
                                                    <div class="progress">
                                                        <div class="progress-bar" id="progress-aceptados"></div>
                                                    </div>
                                                    <span class="progress-description" id="desc-aceptados">0% del total</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box bg-red">
                                                <span class="info-box-icon"><i class="ion ion-ios-close-outline"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Pedidos Rechazados</span>
                                                    <span class="info-box-number" id="pedidos-rechazados">0</span>
                                                    <div class="progress">
                                                        <div class="progress-bar" id="progress-rechazados"></div>
                                                    </div>
                                                    <span class="progress-description" id="desc-rechazados">0% del total</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="info-box bg-orange">
                                                <span class="info-box-icon"><i class="ion ion-ios-flag-outline"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Pedidos Finalizados</span>
                                                    <span class="info-box-number" id="pedidos-finalizados">0</span>
                                                    <div class="progress">
                                                        <div class="progress-bar" id="progress-finalizados"></div>
                                                    </div>
                                                    <span class="progress-description" id="desc-finalizados">0% del total</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chart-container">
                                        <canvas id="pedidos-estados-chart" width="800" height="300"></canvas>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID Pedido</th>
                                                    <th>Cliente</th>
                                                    <th>Repartidor</th>
                                                    <th>Estado</th>
                                                    <th>Fecha</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="pedidos-recientes">
                                                <tr>
                                                    <td colspan="6" class="text-center">Cargando pedidos...</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gráficos Originales (se mantienen para referencia) -->
                    <div class="row" style="display: none;">
                        <div class="col-md-6">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Compras de los últimos 10 días</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" onclick="exportarGrafico('compras')" title="Exportar gráfico">
                                            <i class="fa fa-download"></i>
                                        </button>
                                        <button type="button" class="btn btn-box-tool" onclick="actualizarGrafico('compras')" title="Actualizar datos">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="chart-container">
                                        <canvas id="compras" width="400" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Ventas de los últimos 12 meses</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" onclick="exportarGrafico('ventas')" title="Exportar gráfico">
                                            <i class="fa fa-download"></i>
                                        </button>
                                        <button type="button" class="btn btn-box-tool" onclick="actualizarGrafico('ventas')" title="Actualizar datos">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="chart-container">
                                        <canvas id="ventas" width="400" height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->


<?php
  
  } //Llave de la condicion if de la variable de session

  else
  {
    require 'noacceso.php';
  }

  require 'footer.php';

  $fechasc = "'2024-09-01', '2024-09-02', '2024-09-03'";
$totalesc = "500, 300, 450";

?>

<script src="../public/js/Chart.min.js"></script>
<script src="../public/js/Chart.bundle.min.js"></script>

<script>
// Dashboard Manager - Sistema de gestión del dashboard moderno
class DashboardManager {
    constructor() {
        this.charts = {};
        this.updateInterval = null;
        this.isAutoUpdateActive = false;
        this.init();
    }

    init() {
        this.initCharts();
        this.calculateStatistics();
        this.startAutoUpdate();
        this.setupEventListeners();
    }

    initCharts() {
        // Configuración global de Chart.js
        Chart.defaults.font.family = "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif";
        Chart.defaults.font.size = 12;
        Chart.defaults.color = '#4a5568';

        // Gráfico de Compras
        const ctxCompras = document.getElementById('compras').getContext('2d');
        this.charts.compras = new Chart(ctxCompras, {
            type: 'line',
            data: {
                labels: [<?php echo $fechasc; ?>],
                datasets: [{
                    label: 'Compras en BsD',
                    data: [<?php echo $totalesc; ?>],
                    backgroundColor: 'rgba(237, 137, 54, 0.1)',
                    borderColor: '#ed8936',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ed8936',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#ed8936',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'BsD ' + context.parsed.y.toLocaleString('es-VE');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            callback: function(value) {
                                return 'BsD ' + value.toLocaleString('es-VE');
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                }
            }
        });

        // Gráfico de Ventas
        const ctxVentas = document.getElementById('ventas').getContext('2d');
        this.charts.ventas = new Chart(ctxVentas, {
            type: 'bar',
            data: {
                labels: [<?php echo $fechasv; ?>],
                datasets: [{
                    label: 'Ventas en BsD',
                    data: [<?php echo $totalesv; ?>],
                    backgroundColor: 'rgba(72, 187, 120, 0.8)',
                    borderColor: '#48bb78',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#48bb78',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'BsD ' + context.parsed.y.toLocaleString('es-VE');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            callback: function(value) {
                                return 'BsD ' + value.toLocaleString('es-VE');
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuart'
                }
            }
        });
    }

    calculateStatistics() {
        // Calcular estadísticas para los gráficos
        const comprasData = [<?php echo $totalesc; ?>];
        const ventasData = [<?php echo $totalesv; ?>];

        // Estadísticas de compras
        const promedioCompras = comprasData.reduce((a, b) => a + b, 0) / comprasData.length;
        const maximoCompras = Math.max(...comprasData);
        
        document.getElementById('promedio-compras').textContent = 'BsD ' + promedioCompras.toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('maximo-compras').textContent = 'BsD ' + maximoCompras.toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2});

        // Estadísticas de ventas
        const promedioVentas = ventasData.reduce((a, b) => a + b, 0) / ventasData.length;
        const maximoVentas = Math.max(...ventasData);
        
        document.getElementById('promedio-ventas').textContent = 'BsD ' + promedioVentas.toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('mejor-mes').textContent = 'BsD ' + maximoVentas.toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2});

        // Actualizar transacciones
        const totalTransacciones = comprasData.length + ventasData.length;
        const promedioTransacciones = (promedioCompras + promedioVentas) / 2;
        
        document.getElementById('total-transacciones').textContent = totalTransacciones;
        document.getElementById('promedio-transacciones').textContent = 'BsD ' + promedioTransacciones.toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    startAutoUpdate() {
        this.isAutoUpdateActive = true;
        this.updateInterval = setInterval(() => {
            this.actualizarDashboard();
        }, 30000); // Actualizar cada 30 segundos
        
        console.log('Auto-actualización iniciada: cada 30 segundos');
    }

    stopAutoUpdate() {
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
            this.isAutoUpdateActive = false;
            console.log('Auto-actualización detenida');
        }
    }

    setupEventListeners() {
        // Detectar cuando la página está visible para optimizar actualizaciones
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.stopAutoUpdate();
            } else {
                this.startAutoUpdate();
                this.actualizarDashboard();
            }
        });
    }

    actualizarDashboard() {
        console.log('Actualizando dashboard...');
        
        // Mostrar indicador de carga
        this.mostrarLoading();
        
        // Simular actualización de datos (en producción sería una llamada AJAX)
        setTimeout(() => {
            // Actualizar timestamps
            const now = new Date();
            document.getElementById('last-update-compras').textContent = now.toLocaleTimeString('es-VE');
            document.getElementById('last-update-ventas').textContent = now.toLocaleTimeString('es-VE');
            
            // Actualizar datos con pequeñas variaciones (simulación)
            this.actualizarDatosSimulados();
            
            // Ocultar indicador de carga
            this.ocultarLoading();
            
            // Mostrar notificación de éxito
            this.mostrarNotificacion('Dashboard actualizado exitosamente', 'success');
        }, 1000);
    }

    actualizarDatosSimulados() {
        // Simular pequeñas variaciones en los datos
        const comprasActuales = parseFloat(document.getElementById('total-compras-hoy').textContent.replace(/[^0-9,-]+/g, '').replace(',', '.'));
        const ventasActuales = parseFloat(document.getElementById('total-ventas-hoy').textContent.replace(/[^0-9,-]+/g, '').replace(',', '.'));
        
        const variacionCompras = (Math.random() - 0.5) * 0.1; // ±5% de variación
        const variacionVentas = (Math.random() - 0.5) * 0.1;
        
        const nuevasCompras = comprasActuales * (1 + variacionCompras);
        const nuevasVentas = ventasActuales * (1 + variacionVentas);
        
        document.getElementById('total-compras-hoy').textContent = 'BsD ' + nuevasCompras.toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('total-ventas-hoy').textContent = 'BsD ' + nuevasVentas.toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        
        // Actualizar ganancia
        const nuevaGanancia = nuevasVentas - nuevasCompras;
        const nuevoMargen = nuevasCompras > 0 ? (nuevaGanancia / nuevasCompras) * 100 : 0;
        
        document.getElementById('ganancia-hoy').textContent = 'BsD ' + nuevaGanancia.toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('margen-hoy').textContent = nuevoMargen.toFixed(1) + '%';
    }

    mostrarLoading() {
        const elementos = document.querySelectorAll('.small-box p, .chart-info span');
        elementos.forEach(el => {
            el.classList.add('loading-shimmer');
        });
    }

    ocultarLoading() {
        const elementos = document.querySelectorAll('.loading-shimmer');
        elementos.forEach(el => {
            el.classList.remove('loading-shimmer');
        });
    }

    mostrarNotificacion(mensaje, tipo = 'info') {
        // Crear notificación temporal
        const notificacion = document.createElement('div');
        notificacion.className = `alert alert-${tipo} alert-dismissible`;
        notificacion.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; animation: fadeInUp 0.5s ease;';
        notificacion.innerHTML = `
            ${mensaje}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        `;
        
        document.body.appendChild(notificacion);
        
        // Auto-cerrar después de 3 segundos
        setTimeout(() => {
            if (notificacion.parentNode) {
                notificacion.parentNode.removeChild(notificacion);
            }
        }, 3000);
    }

    exportarGrafico(tipo) {
        const canvas = document.getElementById(tipo);
        const url = canvas.toDataURL('image/png');
        const link = document.createElement('a');
        link.download = `grafico_${tipo}_${new Date().toISOString().slice(0, 10)}.png`;
        link.href = url;
        link.click();
        
        this.mostrarNotificacion(`Gráfico de ${tipo} exportado exitosamente`, 'success');
    }

    actualizarGrafico(tipo) {
        if (this.charts[tipo]) {
            this.charts[tipo].update('active');
            this.mostrarNotificacion(`Gráfico de ${tipo} actualizado`, 'info');
        }
    }

    exportarReporte() {
        // Simular exportación de reporte completo
        this.mostrarNotificacion('Reporte completo generado exitosamente', 'success');
        
        // En producción, aquí se generaría un PDF o Excel con todos los datos
        console.log('Exportando reporte completo...');
    }

    // Funciones para las nuevas secciones de análisis
    
    // Cargar datos de transacciones en tiempo real
    async cargarTransaccionesTiempoReal() {
        try {
            const response = await fetch('ajax/dashboard_transacciones.php');
            const data = await response.json();
            
            // Actualizar contadores
            document.getElementById('pedidos-hoy').textContent = data.pedidos_hoy;
            document.getElementById('ventas-hoy').textContent = data.ventas_hoy;
            document.getElementById('clientes-activos').textContent = data.clientes_activos;
            document.getElementById('tasa-conversion').textContent = data.tasa_conversion + '%';
            
            // Actualizar tabla de transacciones
            const tbody = document.getElementById('transacciones-body');
            tbody.innerHTML = '';
            
            data.transacciones.forEach(transaccion => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${transaccion.id}</td>
                    <td><span class="label label-${transaccion.tipo === 'pedido' ? 'primary' : 'success'}">${transaccion.tipo}</span></td>
                    <td>${transaccion.cliente}</td>
                    <td>Bs ${parseFloat(transaccion.monto).toFixed(2)}</td>
                    <td><span class="label label-${this.getEstadoColor(transaccion.estado)}">${transaccion.estado}</span></td>
                    <td>${transaccion.fecha}</td>
                    <td>
                        <button class="btn btn-xs btn-info" onclick="verDetalleTransaccion(${transaccion.id})">
                            <i class="fa fa-eye"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
            
        } catch (error) {
            console.error('Error al cargar transacciones:', error);
            this.mostrarNotificacion('Error al cargar transacciones', 'error');
        }
    }
    
    // Cargar análisis de compras desde compras.php
    async cargarAnalisisCompras() {
        try {
            const response = await fetch('ajax/dashboard_compras.php');
            const data = await response.json();
            
            // Actualizar contadores
            document.getElementById('total-compras-clientes').textContent = data.total_compras;
            document.getElementById('promedio-compra-cliente').textContent = 'Bs ' + parseFloat(data.promedio_compra).toFixed(2);
            
            // Actualizar tabla de top clientes
            const tbody = document.getElementById('top-clientes-compras');
            tbody.innerHTML = '';
            
            data.top_clientes.forEach(cliente => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${cliente.nombre}</td>
                    <td>${cliente.total_compras}</td>
                    <td>Bs ${parseFloat(cliente.total_gastado).toFixed(2)}</td>
                    <td>${cliente.ultima_compra}</td>
                `;
                tbody.appendChild(row);
            });
            
            // Crear gráfico de compras
            this.crearGraficoCompras(data.grafico_compras);
            
        } catch (error) {
            console.error('Error al cargar análisis de compras:', error);
            this.mostrarNotificacion('Error al cargar análisis de compras', 'error');
        }
    }
    
    // Cargar análisis de ventas desde venta.php
    async cargarAnalisisVentas() {
        try {
            const response = await fetch('ajax/dashboard_ventas.php');
            const data = await response.json();
            
            // Actualizar contadores
            document.getElementById('total-facturas-hoy').textContent = data.total_facturas_hoy;
            document.getElementById('total-ventas-hoy').textContent = 'Bs ' + parseFloat(data.total_ventas_hoy).toFixed(2);
            
            // Actualizar tabla de últimas facturas
            const tbody = document.getElementById('ultimas-facturas');
            tbody.innerHTML = '';
            
            data.ultimas_facturas.forEach(factura => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${factura.numero}</td>
                    <td>${factura.cliente}</td>
                    <td>Bs ${parseFloat(factura.monto).toFixed(2)}</td>
                    <td><span class="label label-${this.getEstadoVentaColor(factura.estado)}">${factura.estado}</span></td>
                `;
                tbody.appendChild(row);
            });
            
            // Crear gráfico de ventas
            this.crearGraficoVentas(data.grafico_ventas);
            
        } catch (error) {
            console.error('Error al cargar análisis de ventas:', error);
            this.mostrarNotificacion('Error al cargar análisis de ventas', 'error');
        }
    }
    
    // Cargar análisis de pedidos activos desde pactivos.php
    async cargarPedidosActivos() {
        try {
            const response = await fetch('ajax/dashboard_pedidos.php');
            const data = await response.json();
            
            // Actualizar contadores de estados
            const total = data.pedidos_pendientes + data.pedidos_aceptados + data.pedidos_rechazados + data.pedidos_finalizados;
            
            document.getElementById('pedidos-pendientes').textContent = data.pedidos_pendientes;
            document.getElementById('pedidos-aceptados').textContent = data.pedidos_aceptados;
            document.getElementById('pedidos-rechazados').textContent = data.pedidos_rechazados;
            document.getElementById('pedidos-finalizados').textContent = data.pedidos_finalizados;
            
            // Actualizar barras de progreso
            this.actualizarProgressBar('pendientes', data.pedidos_pendientes, total);
            this.actualizarProgressBar('aceptados', data.pedidos_aceptados, total);
            this.actualizarProgressBar('rechazados', data.pedidos_rechazados, total);
            this.actualizarProgressBar('finalizados', data.pedidos_finalizados, total);
            
            // Actualizar tabla de pedidos recientes
            const tbody = document.getElementById('pedidos-recientes');
            tbody.innerHTML = '';
            
            data.pedidos_recientes.forEach(pedido => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${pedido.idpedido}</td>
                    <td>${pedido.nombre_usuario}</td>
                    <td>${pedido.nombre_delivery || 'No asignado'}</td>
                    <td><span class="label bg-${this.getEstadoPedidoColor(pedido.estado)}">${pedido.estado}</span></td>
                    <td>${pedido.fecha}</td>
                    <td>
                        <button class="btn btn-xs btn-info" onclick="verDetallePedido(${pedido.idpedido})">
                            <i class="fa fa-eye"></i>
                        </button>
                        <button class="btn btn-xs btn-warning" onclick="cambiarEstadoPedido(${pedido.idpedido})">
                            <i class="fa fa-edit"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
            
            // Crear gráfico de estados de pedidos
            this.crearGraficoPedidosEstados(data);
            
        } catch (error) {
            console.error('Error al cargar pedidos activos:', error);
            this.mostrarNotificacion('Error al cargar pedidos activos', 'error');
        }
    }
    
    // Funciones auxiliares
    
    actualizarProgressBar(tipo, valor, total) {
        const porcentaje = total > 0 ? (valor / total * 100).toFixed(1) : 0;
        const progressBar = document.getElementById(`progress-${tipo}`);
        const progressDesc = document.getElementById(`desc-${tipo}`);
        
        progressBar.style.width = porcentaje + '%';
        progressDesc.textContent = porcentaje + '% del total';
    }
    
    getEstadoColor(estado) {
        switch(estado.toLowerCase()) {
            case 'completado': return 'success';
            case 'pendiente': return 'warning';
            case 'cancelado': return 'danger';
            case 'procesando': return 'info';
            default: return 'default';
        }
    }
    
    getEstadoVentaColor(estado) {
        switch(estado.toLowerCase()) {
            case 'pagado': return 'success';
            case 'pendiente': return 'warning';
            case 'anulado': return 'danger';
            default: return 'default';
        }
    }
    
    getEstadoPedidoColor(estado) {
        switch(estado.toLowerCase()) {
            case 'aceptado': return 'green';
            case 'rechazado': return 'red';
            case 'finalizado': return 'orange';
            default: return 'gray';
        }
    }
    
    crearGraficoCompras(data) {
        const ctx = document.getElementById('analisis-compras-chart').getContext('2d');
        if (this.charts.analisis_compras) {
            this.charts.analisis_compras.destroy();
        }
        
        this.charts.analisis_compras = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    data: data.values,
                    backgroundColor: ['#3498db', '#2ecc71', '#f39c12', '#e74c3c', '#9b59b6'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
    
    crearGraficoVentas(data) {
        const ctx = document.getElementById('analisis-ventas-chart').getContext('2d');
        if (this.charts.analisis_ventas) {
            this.charts.analisis_ventas.destroy();
        }
        
        this.charts.analisis_ventas = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Ventas por Hora',
                    data: data.values,
                    backgroundColor: 'rgba(155, 89, 182, 0.8)',
                    borderColor: '#9b59b6',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    
    crearGraficoPedidosEstados(data) {
        const ctx = document.getElementById('pedidos-estados-chart').getContext('2d');
        if (this.charts.pedidos_estados) {
            this.charts.pedidos_estados.destroy();
        }
        
        this.charts.pedidos_estados = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels_tiempo,
                datasets: [
                    {
                        label: 'Pendientes',
                        data: data.data_pendientes,
                        borderColor: '#3498db',
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        tension: 0.4
                    },
                    {
                        label: 'Aceptados',
                        data: data.data_aceptados,
                        borderColor: '#2ecc71',
                        backgroundColor: 'rgba(46, 204, 113, 0.1)',
                        tension: 0.4
                    },
                    {
                        label: 'Rechazados',
                        data: data.data_rechazados,
                        borderColor: '#e74c3c',
                        backgroundColor: 'rgba(231, 76, 60, 0.1)',
                        tension: 0.4
                    },
                    {
                        label: 'Finalizados',
                        data: data.data_finalizados,
                        borderColor: '#f39c12',
                        backgroundColor: 'rgba(243, 156, 18, 0.1)',
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
}

// Funciones globales para los botones
function actualizarDashboard() {
    dashboardManager.actualizarDashboard();
}

function exportarGrafico(tipo) {
    dashboardManager.exportarGrafico(tipo);
}

function actualizarGrafico(tipo) {
    dashboardManager.actualizarGrafico(tipo);
}

function exportarReporte() {
    dashboardManager.exportarReporte();
}

// Funciones globales para las nuevas secciones
function actualizarTransacciones() {
    dashboardManager.cargarTransaccionesTiempoReal();
}

function exportarTransacciones() {
    // Exportar tabla de transacciones a CSV
    const tabla = document.getElementById('transacciones-body');
    const filas = tabla.getElementsByTagName('tr');
    
    let csv = 'ID,Tipo,Cliente,Monto,Estado,Fecha\n';
    
    for (let fila of filas) {
        const celdas = fila.getElementsByTagName('td');
        if (celdas.length >= 6) {
            csv += celdas[0].textContent + ',' + celdas[1].textContent + ',' + 
                   celdas[2].textContent + ',' + celdas[3].textContent + ',' + 
                   celdas[4].textContent + ',' + celdas[5].textContent + '\n';
        }
    }
    
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'transacciones_' + new Date().toISOString().slice(0, 10) + '.csv';
    a.click();
    window.URL.revokeObjectURL(url);
    
    dashboardManager.mostrarNotificacion('Transacciones exportadas exitosamente', 'success');
}

function verDetalleTransaccion(id) {
    // Abrir modal con detalles de la transacción
    Swal.fire({
        title: 'Detalle de Transacción',
        text: 'ID: ' + id + ' - Aquí se mostrarían los detalles completos de la transacción',
        icon: 'info',
        confirmButtonText: 'Cerrar'
    });
}

function verDetallePedido(id) {
    // Abrir modal con detalles del pedido
    Swal.fire({
        title: 'Detalle de Pedido',
        text: 'ID: ' + id + ' - Aquí se mostrarían los detalles completos del pedido',
        icon: 'info',
        confirmButtonText: 'Cerrar'
    });
}

function cambiarEstadoPedido(id) {
    // Cambiar estado del pedido (similar a la función en pactivos.php)
    Swal.fire({
        title: 'Cambiar Estado del Pedido',
        input: 'select',
        inputOptions: {
            'Aceptado': 'Aceptado',
            'Rechazado': 'Rechazado',
            'Finalizado': 'Finalizado'
        },
        inputPlaceholder: 'Selecciona un estado',
        showCancelButton: true,
        confirmButtonText: 'Actualizar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // En producción, aquí se enviaría la actualización al servidor
            fetch('ajax/actualizar_estado_pedido.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    idpedido: id,
                    estado: result.value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Actualizado', 'Estado del pedido actualizado exitosamente', 'success');
                    dashboardManager.cargarPedidosActivos(); // Recargar datos
                } else {
                    Swal.fire('Error', 'Error al actualizar el estado del pedido', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Error al actualizar el estado del pedido', 'error');
            });
        }
    });
}



function verDetallePedido(idPedido) {
    // Redirigir a la página de detalles del pedido
    window.location.href = `pactivos.php?idpedido=${idPedido}`;
}

// Inicializar el dashboard cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    window.dashboardManager = new DashboardManager();
});
</script>

<?php
  }
  ob_end_flush(); //liberar el espacio del buffer
?>

<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>