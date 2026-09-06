<div class="navbar relative z-50 bg-base-100 shadow-sm">

    {{-- Left --}}
    <div class="navbar-start">
        <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                <svg
                    aria-label="Menu"
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h8m-8 6h16"
                    />
                </svg>
            </div>

            <ul class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
                <li><a href="/">Home</a></li>
                 @can('view-admin')
                    <li><a href="/admin">Admin</a></li>
                @endcan
                <li><a href="/about">About</a></li>
                <li><a href="/contact">Contact</a></li>
                <li>
                    <a>Ideas</a>
                    <ul class="p-2">
                        <li><a href="/ideas/create">+ New Idea</a></li>
                    </ul>
                </li>


            </ul>
        </div>

        <a href="/" class="btn btn-ghost text-xl">IdeaHub</a>
    </div>


    {{-- Center --}}
    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1">
           <li><a href="/">Home</a></li>
           <li>
                <details>
                    <summary>Ideas</summary>

                    <ul class="p-2 bg-base-100 w-40 z-1">
                        <li><a href="/ideas/create">+ New Idea</a></li>
                    </ul>
                </details>
            </li>
            @can('view-admin')
                <li><a href="/admin">Admin</a></li>
            @endcan
            <li><a href="/about">About</a></li>
            <li><a href="/contact">Contact</a></li>
        </ul>
    </div>


    {{-- Right --}}
    <div class="navbar-end gap-2">
        <x-theme />
        <x-user-menu />
    </div>

</div>
