<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            {{-- <li class="nav-item">
                <a href="{{ route('support.admin.dashboard.index') }}" class="nav-link">
                    <i class="nav-icon fas fa-th"></i>
                    <p>
                        {{ __('support::messages.dashboard') }}
                    </p>
                </a>
            </li> --}}

            <li class="nav-item">
                <a href="{{ route('support.about') }}" class="nav-link">
                    <i class="nav-icon far fa-address-card"></i>
                    <p>
                        {{ str(__('support::messages.about'))->headline() }}
                    </p>
                </a>
            </li>
            {{--
            <li class="nav-item">
                <a href="{{ route('support.admin.dashboard') }}" class="nav-link">
                    <i class="nav-icon far fa-address-card"></i>
                    <p>
                        {{ __('support::messages.dashboard') }}
                    </p>
                </a>
            </li> --}}
            @can('viewAny', \Dainsys\Support\Models\Department::class)
            <li class="nav-item">
                <a href="{{ route('support.admin.departments.index') }}" target="_top" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>{{ str(__('support::messages.departments'))->headline() }}</p>
                </a>
            </li>
            @endcan
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->