<div class="sidebar-inner slimscroll">
    <div id="sidebar-menu" class="sidebar-menu">
        @php
            $userId = Auth::id();
            $get_user_data = Helper::get_user_data($userId);
            //echo"<pre>";print_r($get_user_data); echo"</pre>";exit;
            $get_permission_data = Helper::get_permission_data($get_user_data->role_id);
            $permission1 = [];
            if (is_object($get_permission_data) && property_exists($get_permission_data, 'permission') && $get_permission_data->permission !== '') {
                $permission1 = $get_permission_data->permission;
                $permission1 = explode(',', $permission1);
            } else {
                echo '';
                // Handle the case where $get_permission_data is not an object or 'permission' property is empty.
            }
            // echo"<pre>";print_r($permission1);echo"</pre>";
        @endphp
        <ul>
            <li class="{{ request()->segment(1) == 'admin' && request()->segment(2) == '' ? 'active' : '' }}">
                <a href="{{ url('/') }}"><i data-feather="home"></i> <span>Dashboard</span></a>
            </li>
            @if (
                in_array('3', $permission1) ||
                in_array('4', $permission1) ||
                in_array('5', $permission1) ||
                in_array('6', $permission1) ||
                in_array('14', $permission1) ||
                in_array('8', $permission1) ||
                in_array('9', $permission1) ||
                in_array('14', $permission1) ||
                in_array('11', $permission1) ||
                in_array('12', $permission1) ||
                in_array('17', $permission1) ||
                in_array('18', $permission1) ||
                in_array('19', $permission1) ||
                in_array('20', $permission1) ||
                in_array('21', $permission1)
            )
            <li class="submenu">
                <a href="#"
                    class="{{ request()->segment(1) == 'country' ||
                            request()->segment(1) == 'service' ? 'active' : '' }}">
                    <i data-feather="pie-chart"></i> <span>Master</span> <span class="menu-arrow"></span></a>
                <ul>
                    @if (in_array('3', $permission1))
                        <li class="{{ request()->segment(1) == 'country' ? 'active' : '' }}">
                            <a href="{{ route('country.index') }}"
                                class="{{ request()->segment(1) == 'country' ? 'active' : '' }}">Country</a>
                        </li>
                    @endif
                    @if (in_array('4', $permission1))
                        <li class="{{ request()->segment(1) == 'title_rank' ? 'active' : '' }}">
                            <a href="{{ route('title_rank.index') }}"
                                class="{{ request()->segment(1) == 'title_rank' ? 'active' : '' }}">Title/Rank</a>
                        </li>
                    @endif
                    @if (in_array('5', $permission1))
                    <li class="{{ request()->segment(1) == 'customer_type' ? 'active' : '' }}">
                        <a href="{{ route('customer_type.index') }}"
                            class="{{ request()->segment(1) == 'customer_type' ? 'active' : '' }}">Customer Type</a>
                    </li>
                    @endif
                    @if (in_array('6', $permission1))
                        <li class="{{ request()->segment(1) == 'service' ? 'active' : '' }}">
                            <a href="{{ route('service.index') }}"
                                class="{{ request()->segment(1) == 'service' ? 'active' : '' }}">Service</a>
                        </li>
                    @endif
                    @if (in_array('7', $permission1))
                        <li class="{{ request()->segment(1) == 'branch' ? 'active' : '' }}">
                            <a href="{{ route('branch.index') }}"
                                class="{{ request()->segment(1) == 'branch' ? 'active' : '' }}">Branch</a>
                        </li>
                    @endif
                    @if (in_array('8', $permission1))
                    <li class="{{ request()->segment(1) == 'surveyor_type' ? 'active' : '' }}">
                        <a href="{{ route('surveyor_type.index') }}"
                            class="{{ request()->segment(1) == 'surveyor_type' ? 'active' : '' }}">Surveyor Type</a>
                    </li>
                     @endif
                    @if (in_array('9', $permission1))
                        <li class="{{ request()->segment(1) == 'surveyor_time_zone' ? 'active' : '' }}">
                            <a href="{{ route('surveyor_time_zone.index') }}"
                                class="{{ request()->segment(1) == 'surveyor_time_zone' ? 'active' : '' }}">Surveyor Time Zone</a>
                        </li>
                    @endif
                    @if (in_array('10', $permission1))
                        <li class="{{ request()->segment(1) == 'storage_type' ? 'active' : '' }}">
                            <a href="{{ route('storage_type.index') }}"
                                class="{{ request()->segment(1) == 'storage_type' ? 'active' : '' }}">Storage Type</a>
                        </li>
                    @endif
                    @if (in_array('11', $permission1))
                        <li class="{{ request()->segment(1) == 'storage_mode' ? 'active' : '' }}">
                            <a href="{{ route('storage_mode.index') }}"
                                class="{{ request()->segment(1) == 'storage_mode' ? 'active' : '' }}">Storage Mode</a>
                        </li>
                    @endif
                    @if (in_array('12', $permission1))
                        <li  class="{{ request()->segment(1) == 'enquiry_mode' ? 'active' : '' }}">
                            <a href="{{ route('enquiry_mode.index') }}"
                                class="{{ request()->segment(1) == 'enquiry_mode' ? 'active' : '' }}">Enquiry Mode</a>
                        </li>
                    @endif
                    @if (in_array('17', $permission1))
                        <li class="{{ request()->segment(1) == 'industry-type' ? 'active' : '' }}">
                            <a href="{{ route('industry-type.index') }}"
                                class="{{ request()->segment(1) == 'industry-type' ? 'active' : '' }}">Industry Type</a>
                        </li>
                    @endif
                    @if (in_array('18', $permission1))
                        <li class="{{ request()->segment(1) == 'reference' ? 'active' : '' }}">
                            <a href="{{ route('reference.index') }}"
                                class="{{ request()->segment(1) == 'reference' ? 'active' : '' }}">Reference</a>
                        </li>
                    @endif
                    @if (in_array('19', $permission1))
                        <li class="{{ request()->segment(1) == 'approved-agents' ? 'active' : '' }}">
                            <a href="{{ route('approved-agents.index') }}"
                                class="{{ request()->segment(1) == 'approved-agents' ? 'active' : '' }}">Approved Agents</a>
                        </li>
                    @endif
                    @if (in_array('20', $permission1))
                        <li class="{{ request()->segment(1) == 'frequencies' ? 'active' : '' }}">
                            <a href="{{ route('frequencies.index') }}"
                                class="{{ request()->segment(1) == 'frequencies' ? 'active' : '' }}">Frequency</a>
                        </li>
                    @endif
                    @if (in_array('21', $permission1))
                        <li class="{{ request()->segment(1) == 'durations' ? 'active' : '' }}">
                            <a href="{{ route('durations.index') }}"
                                class="{{ request()->segment(1) == 'durations' ? 'active' : '' }}">Duration</a>
                        </li>
                    @endif
                </ul>
            </li>
            @endif
             @if (in_array('13', $permission1))
                <li class="{{ request()->segment(2) == 'surveyor' ? 'active' : '' }}">
                    <a href="{{ route('surveyor.index') }}"
                        class="{{ request()->segment(1) == 'surveyor' ? 'active' : '' }}"> <i class="fa fa-users"></i><span>Surveyor</span></a>
                </li>
             @endif
             @if(in_array('14', $permission1) ||
                in_array('15', $permission1) ||
                in_array('16', $permission1)
             )
                <li class="submenu">
                    <a href="#"
                        class="{{ request()->segment(1) == 'agent' || request()->segment(1) == 'followup' ? 'active' : '' }}">
                        <i data-feather="pie-chart"></i> <span> Sales</span> <span class="menu-arrow"></span></a>
                    <ul>
                        @if (in_array('14', $permission1))
                            <li class="{{ request()->segment(1) == 'agent' ? 'active' : '' }}">
                                <a href="{{ route('agent.index') }}"
                                    class="{{ request()->segment(1) == 'agent' ? 'active' : '' }}">Organization</a>
                            </li>
                        @endif
                        @if (in_array('15', $permission1))
                            <li class="{{ request()->segment(1) == 'followup' || request()->segment(1) == 'survey_info' || request()->segment(1) == 'costing' || request()->segment(1) == 'surveyor_form' ? 'active' : '' }}">
                                <a href="{{ route('followup.index') }}"
                                    class="{{ request()->segment(1) == 'followup' || request()->segment(1) == 'survey_info' || request()->segment(1) == 'costing' || request()->segment(1) == 'surveyor_form' ? 'active' : '' }}">Enquiry</a>
                            </li>
                        @endif
                        @if (in_array('16', $permission1))
                            <li class="{{ request()->segment(1) == 'survey_assign' ? 'active' : '' }}">
                                <a href="{{ route('survey_assign.index') }}"
                                    class="{{ request()->segment(1) == 'survey_assign' ? 'active' : '' }}">Surveys</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if (in_array('1', $permission1) || in_array('2', $permission1))
                <li class="submenu">
                    <a href="#"
                        class="{{ request()->segment(2) == 'userpermission' || request()->segment(2) == 'adminuser' ? 'active' : '' }}">
                        <i data-feather="user"></i> <span> User Management</span> <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        @if (in_array('1', $permission1))
                            <li class="{{ request()->segment(1) == 'userpermission' ? 'active' : '' }}">
                                <a href="{{ route('userpermission.index') }}"
                                    class="{{ request()->segment(1) == 'userpermission' ? 'active' : '' }}">
                                    <i class="fa fa-hand-o-up"></i> User Permission
                                </a>
                            </li>
                        @endif
                        @if (in_array('2', $permission1))
                            <li class="{{ request()->segment(1) == 'adminuser' ? 'active' : '' }}">
                                <a href="{{ route('adminuser.index') }}"
                                    class="{{ request()->segment(1) == 'adminuser' ? 'active' : '' }}"><i
                                        data-feather="lock"></i> Admin User </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        </ul>
    </div>
</div>
