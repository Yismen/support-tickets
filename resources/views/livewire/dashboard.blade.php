<div>

    @if ($user->isSuperAdmin())
    <h3>Super Admin Dashboard</h3>

    <ul>
        <li>Ability to filter dashboard by department</li>
        <li>Service level (tickets completed ontime / tickets completed)</li>
        <li>completion level level</li>
        <li>Weekly Service Level</li>
        <li>ticket open</li>
        <li>ticket completed</li>
        <li>ticket outstanding (pending expired or assigned expired)</li>
    </ul>
    @elseif($user->isDepartmentAdmin())
    <h3>Department Admin Dashboard</h3>


    <ul>
        <li>Department Name</li>
        <li>Department Service Level</li>
        <li>Department Completion Level (ticket completed / total tickets)</li>
        <li>Weekly service level</li>
        <li>Department Tickets Open (Not Completed)</li>
        <li>Department Tickets Completed</li>
        <li>Department Tickets Outstanding</li>
        <li>Department Tickets Pending or assignable (Not Assigned)</li>
        <li>Table Tickets Assigned to me(id, reason, priority, status, owner, actions(view))</li>
        if it assigned to me
        <li>view ticket: leave a reply, close with comment (notify everybody on both actions)</li>
        else
        <li>view ticket: grab ticket</li>
    </ul>
    @elseif($user->isDepartmentAgent())
    <h3>Department Agent Dashboard</h3>

    <ul>
        <li>Department Name</li>
        <li>Department Service Level</li>
        <li>Department Tickets Open (Not Completed)</li>
        <li>Department Tickets Completed</li>
        <li>Department Tickets Outstanding(completed not on time)</li>
        <li>Department Tickets Pending or assignable (Not Assigned)</li>
        <li>Table Tickets Assigned to me(id, reason, priority, status, owner, actions(view))</li>
        if it assigned to me
        <li>view ticket: leave a reply, close with comment (notify everybody on both actions)</li>
        else
        <li>view ticket: grab ticket</li>
    </ul>
    @endif
</div>