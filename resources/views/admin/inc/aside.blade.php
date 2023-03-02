<aside class="main-sidebar elevation-4 sidebar-light-navy">

    <a href="{{ route('home') }}" class="brand-link navbar-navy">
        <img src="{{ asset('default/img/favicon.svg') }}" alt="" class="brand-image elevation-3">
        <span class="brand-text font-weight-bold text-light">{{ config('app.name') }}</span>
    </a>

    <div class="sidebar">

        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <a href="{{ route('admin.profile.edit', Auth::id()) }}">
                    @if(Auth::user()->getMedia('avatar')->count())
                        <img src="{{ Auth::user()->getFirstMedia('avatar')->getUrl('thumb') }}" class="img-circle elevation-2" alt="">
                    @else
                        <img src="{{ asset('default/img/no-user.png') }}" class="img-circle elevation-2" alt="">
                    @endif
                </a>
            </div>
            <div class="info">
                <a href="{{ route('admin.profile.edit', Auth::id()) }}" class="d-block">
                    {{ Auth::user()->email }}
                </a>
            </div>
        </div>

        @if($admin_nav = config('app_data.admin_nav'))
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item ">
                        <a href="{{ route('admin.profile.edit', Auth::id()) }}" class="nav-link @routeActive('admin.profile*')">
                            <i class="nav-icon fas fa-user-circle"></i>
                            <p>Профиль</p>
                        </a>
                    </li>
                    @foreach($admin_nav as $item)
                        @if(!isset($item['can']) || \Gate::any($item['can']))
                            @isset($item['heading'])
                                @if(isset($item['can']) ? \Gate::any($item['can']) : true)
                                    <li class="nav-header text-uppercase">@lang($item['heading'])</li>
                                @endif
                            @else
                                <li class="nav-item @isset($item['child']) has-treeview @endisset">
                                    <a href="{{ $item['href'] != '#' ? route($item['href'], $item['params'] ?? '') : '#' }}" class="nav-link @routeActive($item['href'])">
                                        <i class="nav-icon {{ $item['icon'] }}"></i>
                                        <p>
                                            {{ $item['title'] }}
                                            @isset($item['child'])
                                                <i class="right fas fa-angle-left"></i>
                                            @endisset
                                        </p>
                                    </a>
                                    @isset($item['child'])
                                        <ul class="nav nav-treeview">
                                            @foreach($item['child'] as $child)
                                                @if(!isset($child['can']) || \Gate::check($child['can']))
                                                    <li class="nav-item">
                                                        <a href="{{ $child['href'] != '#' ? route($child['href'], $child['params'] ?? '') : '#' }}" class="nav-link @routeActive($child['href'])">
                                                            <i class="{{ $child['icon'] }} nav-icon"></i>
                                                            <p>{{ $child['title'] }}</p>
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endisset
                                </li>
                            @endisset
                        @endif
                    @endforeach
                </ul>
            </nav>
        @endif

    </div>
</aside>
