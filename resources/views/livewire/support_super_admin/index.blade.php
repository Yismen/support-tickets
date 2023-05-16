<div>
    <div class="d-flex justify-content-between">
        <div class="" style="flex: 1;">
            <div class="card ">
                <div class="card-body text-black" :key="time()">
                    <h5>Manage Support Super Admin Users</h5>

                    <div class="row">
                        @foreach ($users->split(4) as $split)
                        <div class="col-sm-6 col-lg-3">
                            @foreach ($split as $user)
                            @if (auth()->user()->id !== $user->id)
                            <div class='info-box bg-gradient-navy'>
                                <div class="info-box-content">
                                    <x-support::inputs.switch field="support_super_admins" :value='(int)$user->id'>
                                        <span
                                            class="{{  in_array($user->id, $support_super_admins) ? 'text-success text-uppercase' : '' }}">{{
                                            $user->name }}</span>
                                    </x-support::inputs.switch>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>