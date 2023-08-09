<nav x-data="{ open: true }" class="bg-alternative-500 border-b border-alternative-400 min-h-screen flex">
    <!-- Primary Navigation Menu -->
    <div class="bg-alternative-300 w-52 h-full flex flex-col justify-between" :class="{'block': !open, 'hidden': open}" class="hidden">
        <div class="px-2 py-2" style="max-height: calc(80% - 16px);">
            <div class="flex items-center justify-between">
                <div class="text-contrast-alternative-500 text-2xl font-medium">
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('profile.show') }}">
                            <img class="h-20 w-20 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                        </a>
                    </div>
                </div>
            </div>
            <div class="menu-container scrollbar-none">
                <div class="mt-6">
                    <!-- Navigation Links -->
                    <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')" icon="fas fa-home">
                        {{ __('Home') }}
                    </x-nav-link>
                    @forelse (Auth::user()->menu as $m)
                        @if ($m->route)
                        <x-nav-link href="{{ route($m->route.'.index') }}" :active="request()->routeIs($m->active.'.*')" icon="{{ $m->icon }}">
                            {{ $m->text }}
                        </x-nav-link>
                        @else
                        <h3 class="block px-0 py-2 text-contrast-primary-500 text-lg">{{ $m->text }}</h3>
                        @endif                    
                    @endforeach
                </div>
            </div>
        </div>
        <div class="px-4 py-0" style="max-height: calc(15% - 16px);">
            <div class="menu-container scrollbar-none">
                <div class="mt-2">
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <x-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();" icon="fas fa-sign-out-alt">
                            {{ __('Log Out') }}
                        </x-nav-link>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Hamburger -->
    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-contrast-alternative-400 hover:text-contrast-alternative-500 hover:bg-alternative-300 focus:outline-none focus:bg-alternative-500 focus:text-contrast-alternative-500 transition">
        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path :class="{'hidden': ! open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            <path :class="{'hidden': open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</nav>