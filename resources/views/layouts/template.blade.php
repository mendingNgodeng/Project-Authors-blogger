<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('fontawesome/fontawesome/css/all.min.css') }}">

       
    
    <style>
/* Tailwind styling overrides for DataTables */
    [x-cloak] { display: none !important; }
</style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
   <script src="{{ asset('js/jquery.js') }}"></script>

</head>
<body class="bg-gray-100">
    <button id="sidebarToggle" class="fixed top-4 left-4 z-50 bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 transition-all">
    ☰
</button>
   @include('layouts.navigation')
 
    <div class="flex min-h-screen ">
   
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-white h-screen shadow-md p-4 transition-transform duration-300 fixed -translate-x-full  z-40">
            <h2 class="font-semibold text-lg text-gray-700 mb-4">Menu</h2>
            <nav class="flex flex-col gap-2">
                <a href="{{ route('posts.beranda') }}" class="px-4 py-2 rounded hover:bg-blue-100 text-gray-800">Beranda</a>
                <a href="{{ route('story.index') }}" class="px-4 py-2 rounded hover:bg-blue-100 text-gray-800">Stories</a>
                @if(isset(Auth::user()->role))
              
                <a href="{{ route('posts.index') }}" class="px-4 py-2 rounded hover:bg-blue-100 text-gray-800">Posts</a>
                <!-- <a href="{{ route('profile.edit') }}" class="px-4 py-2 rounded hover:bg-blue-100 text-gray-800">Profile</a> -->
                @if(Auth::user()->role == 'admin')
                  <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded hover:bg-blue-100 text-gray-800">Dashboard</a>
                <a href="{{ route('users.index') }}" class="px-4 py-2 rounded hover:bg-blue-100 text-gray-800">Users</a>
                <a href="{{ route('comments.index') }}" class="px-4 py-2 rounded hover:bg-blue-100 text-gray-800">Comments</a>
                
                @endif
                @endif

                <!-- <a href="{{ route('logout') }}" class="px-4 py-2 rounded hover:bg-red-100 text-red-600">Logout</a> -->
            </nav>
        </aside>

        <!-- Main Content -->
        <main id="mainContent" class="p-6 transition-all duration-300 ease-in-out ml-0">

    @yield('content')

        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-white p-4 text-center text-gray-500 border-t">
        &copy; Blog by Muhammad Team 1 lead by Muhammad "Helm Otaku" Helmi
    </footer>

    <!-- JS: Toggle sidebar on small screens -->
 <button id="backToTopBtn" class="hidden fixed bottom-6 right-6 bg-blue-600 text-white px-4 py-2 rounded shadow-lg hover:bg-blue-700 transition-all">
    ↑ Top
</button>
@stack('scripts')

</body>
</html>
<script>
    window.userId = @json(Auth::id());
</script>
   <script>

    const mainContent = document.getElementById('mainContent');
       const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');

let sidebarOpen = window.innerWidth >= 1024; // Default: open on desktop

function openSidebar() {
    sidebar.classList.remove('-translate-x-full');
    if (window.innerWidth >= 1024) {
        mainContent.classList.remove('ml-0');
        mainContent.classList.add('ml-64');
    }
    
}

function closeSidebar() {
    sidebar.classList.add('-translate-x-full');
    if (window.innerWidth >= 1024) {
        mainContent.classList.remove('ml-64');
        mainContent.classList.add('ml-0');
    }
   
}

function isSidebarOpen() {
    return !sidebar.classList.contains('-translate-x-full');
}


toggleBtn.addEventListener('click', () => {
    isSidebarOpen() ? closeSidebar() : openSidebar();
});


// Close sidebar when clicking outside (mobile only)
document.addEventListener('click', function (event) {
    const isClickOutside = !sidebar.contains(event.target) && !toggleBtn.contains(event.target);
    const isMobile = window.innerWidth < 1024;

    if (isClickOutside && isMobile) {
        sidebar.classList.add('-translate-x-full');
    }
});
    </script>
  

   <script  src="{{ asset('js/alpine.js') }}" defer></script>
   <script  src="{{ asset('js/sweetalert.js') }}" defer></script>
   <script src="{{ asset('datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('datatable/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(document).ready(function() {
    $('#dataTables').DataTable({
        "paging": true,
        "searching": true,
        "info": true,
        "ordering": true,
        "lengthMenu": [ [10, 5, 15, 20], [10, 5, 15, 20] ],
        "pageLength": 10 // Default number of rows
    });
});
</script>

<!-- back to top -->
<script>
    const backToTopBtn = document.getElementById("backToTopBtn");

    window.addEventListener("scroll", () => {
        if (window.scrollY > 300) {
            backToTopBtn.classList.remove("hidden");
        } else {
            backToTopBtn.classList.add("hidden");
        }
    });

    backToTopBtn.addEventListener("click", () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });


 
</script>
