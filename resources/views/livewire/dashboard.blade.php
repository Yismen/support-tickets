<div>
    <div class="row">
        @if ($user->isSuperAdmin())
        <h3>Super Admin Dashboard</h3>
        <p>Load the corresponding livewire componenet residing under the dashboard namespace</p>
        @elseif($user->isDepartmentAdmin())
        <h3>Department Admin Dashboard</h3>
        <p>Load the corresponding livewire componenet residing under the dashboard namespace</p>
        @elseif($user->isDepartmentAgent())
        <h3>Department Agent Dashboard</h3>
        <p>Load the corresponding livewire componenet residing under the dashboard namespace</p>
        @else
        <h3>Regular User Dashboard</h3>
        <p>Load the corresponding livewire componenet residing under the dashboard namespace</p>
        @endif
    </div>
</div>