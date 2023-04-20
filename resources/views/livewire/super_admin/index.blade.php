<div>
    <div class="d-flex justify-content-between">
        <div class="" style="flex: 1;">
            <div class="card ">
                <div class="card-body text-black" :key="time()">
                    <h5>Manage Super Admin Users</h5>

                    <div class="row">
                        @foreach ($users->split(4) as $split)
                        <div class="col-sm-6 col-lg-3">
                            <div class='info-box bg-gradient-navy'>
                                <div class="info-box-content">
                                    @foreach ($split as $user)
                                    @if (auth()->user()->id !== $user->id)
                                    <x-support::inputs.switch field="super_admins" :value='(int)$user->id'>
                                        <span
                                            class="{{  in_array($user->id, $super_admins) ? 'text-success text-uppercase' : 'text-light' }}">{{
                                            $user->name }}</span>
                                    </x-support::inputs.switch>
                                    @endif
                                    @endforeach
                                </div>

                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>