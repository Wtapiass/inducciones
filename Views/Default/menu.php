<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo URL;?>/App/app">
    
    <div class="sidebar-brand-icon"><img src="<?php echo URL.VIEWS.DTF; ?>/img/logo-intranet-menu.png" style="width:100%"></div>
</a>

<!-- Divider -->
<hr class="sidebar-divider my-0">


<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAdmin"
        aria-expanded="true" aria-controls="collapseAdmin">
        <i class="fas fa-fw fa-cog"></i>
        <span>Aplicaciones</span>
    </a>
    <div id="collapseAdmin" class="collapse" aria-labelledby="headingAdmin" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="https://sahm.hogarymoda.com.co" target='_blank'>SAHM</a>
            <a class="collapse-item" href="https://actasweb-hogarymoda.odoo.com/web/login" target='_blank'>ODOO</a>
            <a class="collapse-item" href="#" target='_blank'>CHAT</a>
            <a class="collapse-item" href="https://calisof.com:605/" target='_blank'>MARI</a>
            <a class="collapse-item" href="https://radio.hogarymoda.com.co">EMISORA</a>
            
        </div>
    </div>
</li> 

<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item">
    <a href="<?php echo URL; ?>ExtensionesPos/list" class="nav-link collapsed" href="#" aria-expanded="true" >
        <i class="fas fa-fw fas fa-building"></i>
        <span> Extensiones</span>
       
    </a>
</li>

<?php 
            
                $valoresPermitidos = ['jobedoya', 'daocampo', 'jcabrera', 'mmontoya', 'jhtorres', 'cordinadorinfra'];

                // Validar si la variable está en la lista de valores permitidos
                if (in_array($_SESSION['unique_id'], $valoresPermitidos)) {
                    echo '
                    <li class="nav-item">
                        <a href="'.URL.'Users/users" class="nav-link collapsed" href="#" aria-expanded="true" >
                            <i class="fas fa-fw fas fa-building"></i>
                            <span> Chat</span>
                        
                        </a>
                    </li>';
                }
            ?>
<li class="nav-item">
    <a href="<?php echo URL; ?>Registro/registro" class="nav-link collapsed" href="#" aria-expanded="true" >
        <i class="fas fa-fw fas fa-building"></i>
        <span> Registro Biométrico</span>
       
    </a>
</li>

<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

<!-- Sidebar Toggler (Sidebar) -->
<div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>

</ul>