<?php
// Hint:
// Something like the following might be reasonable to fetch the authenticated user.
// use src\Repositories\UserRepository;
// $authenticatedUser = (new UserRepository())->getById($this->getSessionData('user_id'));
// After fetching the authenticated user we will need to conditionally render parts of the navigation bar.
?>

<div class="navbar bg-indigo-500 text-primary-content">
    <div class="flex-1">
        <a class="btn btn-ghost normal-case text-xl" href="/">NewCo.</a>
    </div>

    <li class="flex-none">
        <ul class="menu menu-horizontal px-1">
            <div class="flex float-right">
                <a href="/login" class="text-white px-3 py-2 rounded-md text-sm font-medium">Login</a>
                <a href="/register" class="text-white px-3 py-2 rounded-md text-sm font-medium">Register</a>
            </div>
        </ul>
    </li>
</div>
