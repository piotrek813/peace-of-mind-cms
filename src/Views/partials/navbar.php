<div class="navbar bg-base-200">
    <div class="navbar-start">
        <a href="dashboard" class="btn btn-ghost text-xl text-primary">Dashboard</a>
    </div>
    <div class="navbar-end">
        <div class="dropdown dropdown-end">
            <label tabindex="0" class="btn btn-ghost btn-circle avatar placeholder">
                <div class="bg-primary text-primary-content rounded-full w-10">
                    <span><?= substr($username, 0, 1) ?></span>
                </div>
            </label>
            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-200 rounded-box w-52">
                <li><a>Profile</a></li>
                <li><a>Settings</a></li>
                <li><a href="logout">Logout</a></li>
            </ul>
        </div>
    </div>
</div>
