<div>

    @if ($user->isSupportSuperAdmin())
    <h3>Support Super Admin Dashboard</h3>

    <ul>
        <li>Ability to filter dashboard by department</li>
        <li>Service level (tickets completed ontime / tickets completed)</li>
        <li>completion level level</li>
        <li>Weekly Service Level</li>
        <li>ticket open</li>
        <li>ticket completed</li>
        <li>ticket outstanding (pending expired or assigned expired)</li>
    </ul>
    @elseif($user->isDepartmentAdmin($user->departmentRole->department)||$user->isDepartmentAgent($user->departmentRole->department))
    <livewire:support::ticket.department.index :department='auth()->user()->department' />
    @endif
</div>