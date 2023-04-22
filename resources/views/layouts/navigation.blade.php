<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            <li class="nav-item">
                <a href="{{ route('support.dashboard') }}" class="nav-link">
                    <i class="nav-icon far fa-address-card"></i>
                    <p>
                        {{ str(__('support::messages.dashboard'))->headline() }}
                    </p>
                </a>
            </li>

            @can('viewAny', \Dainsys\Support\Models\SuperAdmin::class)
            <li class="nav-item">
                <a href="{{ route('support.admin.super_admins.index') }}" target="_top" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>{{ str(__('support::messages.super_admins'))->headline() }}</p>
                </a>
            </li>
            @endcan
            @can('viewAny', \Dainsys\Support\Models\DepartmentRole::class)
            <li class="nav-item">
                <a href="{{ route('support.admin.department_roles.index') }}" target="_top" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>{{ str(__('support::messages.department_roles'))->headline() }}</p>
                </a>
            </li>
            @endcan

            @can('viewAny', \Dainsys\Support\Models\Department::class)
            <li class="nav-item">
                <a href="{{ route('support.admin.departments.index') }}" target="_top" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>{{ str(__('support::messages.departments'))->headline() }}</p>
                </a>
            </li>
            @endcan

            @can('viewAny', \Dainsys\Support\Models\Reason::class)
            <li class="nav-item">
                <a href="{{ route('support.admin.reasons.index') }}" target="_top" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>{{ str(__('support::messages.reasons'))->headline() }}</p>
                </a>
            </li>
            @endcan
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->