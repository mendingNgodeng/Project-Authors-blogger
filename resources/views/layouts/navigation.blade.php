<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 w-full">
    <!-- Primary Navigation Menu -->
    <div class=" px-3">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex gap-3 justify-between items-center">
            
                <div class="flex gap-3">
          
            <!-- <h1 class="text-xl font-bold text-blue-600">Project Blogger</h1> -->
             </div>    
                <h1 class="text-xl font-bold text-blue-600 absolute left-16 mt-2">Project Blogger</h1>    
                </div>

                <!-- Navigation Links -->
                <!-- <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Kembali') }}
                    </x-nav-link>
                </div> -->
            </div>

        <!-- Settings Dropdown -->

            <div class="hidden sm:flex sm:items-center sm:ms-6">
@if(Auth::check() && isset(Auth::user()->_id))
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
            <div class="">
               
            <div class="flex flex-col items-center w-15">
                <img src="{{ Auth::user()->pic}}" class="rounded-full h-12  object-scale-down" alt="User Logo pict">
                <span class="text-sm">{{ Auth::user()->username}} </span>
            </div>
          
          
           
        </div>
        

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                        
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        @php $back; @endphp
                      
                        <x-dropdown-link :href="route('dashboard')">
                            {{ __('Beranda') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('story.create')">
                            {{ __('Make Story') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('story.manage')">
                            {{ __('Your Stories') }}
                        </x-dropdown-link>
                     
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
@else
<div class="mt-5">
     <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"
                        >
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>
</div>
@endif

            </div>


            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <!-- <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div> -->
    
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ isset(Auth::user()->name) ? Auth::user()->name : 'Guest'}}</div>
                <div class="font-medium text-sm text-gray-500">{{ isset(Auth::user()->email) ? Auth::user()->email : 'None as this is guest' }}</div>
                <div class="font-medium text-sm text-gray-500">{{ isset(Auth::user()->role) ? Auth::user()->role : 'Guest'  }}</div>
            </div>

            <div class="mt-3 space-y-1">
                @if(Auth::check() && isset(Auth::user()->_id))
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('posts.beranda')">
                    {{ __('Beranda') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('story.index')">
                    {{ __('Stories') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('story.create')">
                    {{ __('Make Your Stories') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('story.manage')">
                    {{ __('Your Stories') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                    @else
                     <x-responsive-nav-link :href="route('login')">
                    {{ __('login') }}
                </x-responsive-nav-link>
                     <x-responsive-nav-link :href="route('register')">
                    {{ __('register') }}
                </x-responsive-nav-link>
                    @endif
                </form>

            </div>
            <!--  -->
        </div>
    </div>
</nav>
