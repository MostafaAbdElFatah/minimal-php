@auth
    {{-- Logged in --}}
    <div class="dropdown dropdown-end" data-menu="account">
        <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
            <div class="w-10 rounded-full">
                <img
                    src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp"
                    alt="{{ auth()->user()->name }}"
                />
            </div>
        </div>

        <ul
            tabindex="-1"
            class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow"
        >
            <li>
                <a href="/profile" class="justify-between">
                    Profile
                    <span class="badge">New</span>
                </a>
            </li>

            <li>
                <a href="/settings">
                    Settings
                </a>
            </li>

            <li>
                <form method="POST" action="/logout">
                    @csrf

                    <button type="submit" class="w-full text-left">
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
@else
    {{-- Not logged in --}}
    <div class="flex-none">
        <ul class="menu menu-horizontal px-1">
            <li>
                <details data-menu="account">
                    <summary>Account</summary>

                    <ul class="bg-base-100 rounded-t-none p-2">
                        <li>
                            <a href="/register">
                                Register
                            </a>
                        </li>

                        <li>
                            <a href="/login">
                                Login
                            </a>
                        </li>
                    </ul>
                </details>
            </li>
        </ul>
    </div>
@endauth
