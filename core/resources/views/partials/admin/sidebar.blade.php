<div class="col-md-2">
    <div class="list-group">
        <a href="{{ route('admin-dashboard') }}" class="list-group-item list-group-item-action @if(isset($activeMenu) && $activeMenu == 'dashboard') active @endif">{{ lang('lang.dashboard') }}</a>
        <a href="{{ route('admin-transactions') }}" class="list-group-item list-group-item-action @if(isset($activeMenu) && $activeMenu == 'transactions') active @endif">{{ lang('lang.transactions') }}</a>
        <a href="{{ route('admin-forms') }}" class="list-group-item list-group-item-action @if(isset($activeMenu) && $activeMenu == 'forms') active @endif">{{ lang('lang.forms') }}</a>
        <a href="{{ route('admin-factors') }}" class="list-group-item list-group-item-action @if(isset($activeMenu) && $activeMenu == 'factors') active @endif">{{ lang('lang.factors') }}</a>
        <a href="{{ route('admin-files') }}" class="list-group-item list-group-item-action @if(isset($activeMenu) && $activeMenu == 'files') active @endif">{{ lang('lang.sell_file') }}</a>
        <a href="{{ route('admin-configs') }}" class="list-group-item list-group-item-action @if(isset($activeMenu) && $activeMenu == 'configs') active @endif">{{ lang('lang.configs') }}</a>
        <a href="{{ route('admin-security-settings') }}" class="list-group-item list-group-item-action @if(isset($activeMenu) && $activeMenu == 'security-settings') active @endif">{{ lang('lang.security_settings') }}</a>
        <a href="{{ route('admin-themes') }}" class="list-group-item list-group-item-action @if(isset($activeMenu) && $activeMenu == 'themes') active @endif">{{ lang('lang.themes') }}</a>
    </div>
    <br>
    <div class="text-center">
        <a href="https://sadadpsp.ir" target="_blank">پرداخت الکترونیک سداد</a>
    </div>
</div>
