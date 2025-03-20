@php
    use App\Models\Menus;
    use Illuminate\Support\Collection;

    $user = auth()->user();
    $menus = Menus::getMenus($user->department);
@endphp


<nav class="pcoded-navbar">
    <div class="pcoded-inner-navbar main-menu">
        <div class="pcoded-navigatio-lavel">Menu</div>
        <ul class="pcoded-item pcoded-left-item">

            {{-- Penomoran Kontrak --}}
            @if (Menus::isMenuExist($menus, 'Portal Legal - Penomoran Kontrak'))
                <li class="{{ (request()->is('kontrak/*')) ? 'active pcoded-trigger' : '' }}">
                    <a href="{{ url('kontrak/listPenomoranKontrak') }}">
                        <span><i class="fa-solid fa-chart-line pe-2"></i> Penomoran Kontrak</span>
                    </a>
                </li>
            @endif

            {{-- Approval --}}
            @if (Menus::isMenuExist($menus, 'Portal Legal - Approval'))
                <li class="{{ (request()->is('approver/listApproval')) ? 'active' : '' }}">
                    <a href="{{ url('approver/listApproval') }}">
                        <span><i class="fa-solid fa-chart-line pe-2"></i> Approval</span>
                    </a>
                </li>
            @endif

            {{-- Penomoran Final Kontrak --}}
            @if (Menus::isMenuExist($menus, 'Portal Legal - Penomoran Final Kontrak'))
                <li class="{{ (request()->is('finalkontrak/listPenomoranRegisterFinalKontrak')) ? 'active' : '' }}">
                    <a href="{{ url('finalkontrak/listPenomoranRegisterFinalKontrak') }}">
                        <span><i class="fa-solid fa-chart-line pe-2"></i> Penomoran Final Kontrak</span>
                    </a>
                </li>
            @endif

            <li class="pcoded-hasmenu {{ (request()->is('log/*')) ? 'active pcoded-trigger' : '' }}">
                <a href="javascript:void(0);">
                    <span class="pcoded-micon"><i class="fa-solid fa-history"></i></span>
                    <span class="pcoded-mtext">Log</span>
                </a>
                <ul class="pcoded-submenu">
                    @if (Menus::isMenuExist($menus, 'Portal Legal - Log Activity'))
                        <li class="{{ (request()->is('log/logActivity')) ? 'active' : '' }}">
                            <a href="{{ url('log/logActivity') }}">
                                <span><i class="fa-solid fa-chart-line pe-2"></i> Log Activity</span>
                            </a>
                        </li>
                    @endif

                    @if (Menus::isMenuExist($menus, 'Portal Legal - Log Approval'))
                        <li class="{{ (request()->is('log/logApproval')) ? 'active' : '' }}">
                            <a href="{{ url('log/logApproval') }}">
                                <span><i class="fa-solid fa-chart-line pe-2"></i> Log Approval</span>
                            </a>
                        </li>
                    @endif

                </ul>
            </li>
        </ul>
    </div>
</nav>
