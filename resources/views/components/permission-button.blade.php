@if(Auth::check() && (Auth::user()->isSuperAdmin() || Auth::user()->hasPermission($permission)))
<button class="{{ $class }}" onclick="{{ $onclick }}">
    {{ $slot }}
</button>
@endif