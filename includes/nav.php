<!-- check if user is logged in if so show normal nav else show only login and create account -->
<script src="scripts/nav.js"></script>
<?php if(isset($_SESSION['id'])): ?>
<nav>
        <h2>Navigation</h2>
        <button id="dropdown"><i class="fa fa-chevron-down" aria-hidden="true"></i></button>
        <button id="dropup" class="hidden"><i class="fa fa-chevron-up" aria-hidden="true"></i></button>
    <div class="toHide"> 
        <a href="index.php">Home</a>
        <!-- Video Nav -->
        <div>
            <h3>Videos</h3>
            <ul>
                <li><a href="search.php">Search My Videos</a></li>
                <li><a href="addvid.php">Add Video</a></li>
            </ul>
        </div>

        <!-- Account Nav  -->
        <div>
            <h3>Account</h3>
            <ul>
                <li><a href="logout.php">Logout</a></li>
                <li><a href="editaccount.php">Edit Account</a></li>
                <li><a href="deleteaccount.php">Delete Account</a></li>
            </ul>
        </div>
    </div>
</nav>

<?php else: ?>
<nav>
        <h2>Navigation</h2>
        <button id="dropdown"><i class="fa fa-chevron-down" aria-hidden="true"></i></button>
        <button id="dropup" class="hidden"><i class="fa fa-chevron-up" aria-hidden="true"></i></button>
        <!-- Account Nav  -->
        <div class="toHide">
            <h3>Account</h3>
            <ul>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Create Account</a></li>
            </ul>
        </div>
</nav>
<?php endif ?>
