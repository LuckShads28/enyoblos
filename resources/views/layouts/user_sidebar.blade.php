<ul class="navbar-nav bg-orange sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
        <div class="sidebar-brand-text mx-3">E-NYOBLOS <sup>V2</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-fw fa-home"></i>
            <span>Home</span></a>
    </li> 


    <!-- Nav Item - Pendaftaran Kandidat -->
    @if ($periode->registration_page == 'Active')
        <hr class="sidebar-divider">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('daftar_kandidat_form') }}">
                <i class="fas fa-fw fa-pen"></i>
                <span>Daftar Menjadi Kandidat</span></a>
        </li>
    @endif 

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
