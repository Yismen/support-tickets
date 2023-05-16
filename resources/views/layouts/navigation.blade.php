<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            @can('view-dashboards')
            <li class="nav-item">
                <a href="{{ route('support.dashboard') }}"
                    class="nav-link {{ (request()->is('support/dashboard*') || request()->is('support/admin') || request()->is('support')) ? 'active' : '' }}">
                    <i class="nav-icon fa fa-dashboard"></i>
                    <p>
                        {{ str(__('support::messages.dashboard'))->headline() }}
                    </p>
                </a>
            </li>
            @endcan

            @can('viewAny', \Dainsys\Support\Models\Department::class)
            <li class="nav-item">
                <a href="{{ route('support.admin.departments.index') }}" target="_top"
                    class="nav-link {{ (request()->is('support/departments*')) ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>{{ str(__('support::messages.departments'))->headline() }}</p>
                </a>
            </li>
            @endcan

            @can('viewAny', \Dainsys\Support\Models\Reason::class)
            <li class="nav-item">
                <a href="{{ route('support.admin.reasons.index') }}" target="_top"
                    class="nav-link {{ (request()->is('support/reasons*')) ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>{{ str(__('support::messages.reasons'))->headline() }}</p>
                </a>
            </li>
            @endcan

            @can('viewAny', \Dainsys\Support\Models\DepartmentRole::class)
            <li class="nav-item">
                <a href="{{ route('support.admin.department_roles.index') }}" target="_top"
                    class="nav-link {{ (request()->is('support/department_roles*')) ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>{{ str(__('support::messages.department_roles'))->headline() }}</p>
                </a>
            </li>
            @endcan

            @can('viewAny', \Dainsys\Support\Models\SupportSuperAdmin::class)
            <li class="nav-item">
                <a href="{{ route('support.admin.support_super_admins.index') }}" target="_top"
                    class="nav-link {{ (request()->is('support/support_super_admins*')) ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>{{ str(__('support::messages.support_super_admins'))->headline() }}</p>
                </a>
            </li>
            @endcan

            <li class="nav-item">
                <a href="{{ route('support.my_tickets') }}"
                    class="nav-link {{ (request()->is('support/my_tickets*')) ? 'active' : '' }}">
                    <i class="nav-icon fa fa-ticket"></i>
                    <p>
                        {{ str(__('support::messages.my_tickets'))->headline() }}
                    </p>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->