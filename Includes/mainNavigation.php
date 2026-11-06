<?php
/**
 * mainNavigation.php - left navigation console + login box.
 * No inline styles: look is in CSS/B5_mainNav.css. On phones/tablets the console is folded
 * behind the menu button (checkbox + label, pure CSS, see B5_Grid.css).
 */
require_once __DIR__ . '/auth.php';

$current_page = strtolower(basename($_SERVER['SCRIPT_NAME']));
$navLinks = [
    'index.php'          => ['Index.php',          'Main Terminal'],
    'project_idea.php'   => ['Project_Idea.php',   'Project Idea'],
    'story_timeline.php' => ['Story_Timeline.php', 'Story Timeline'],
    'characters.php'     => ['Characters.php',     'Characters'],
    'gameplay.php'       => ['Gameplay.php',       'Gameplay'],
    'statistics.php'     => ['Statistics.php',     'Statistics'],
    'project_future.php' => ['Project_Future.php', 'Project Future'],
];
$isIn    = isset($_SESSION['eingeloggt']) && $_SESSION['eingeloggt'] === true;
$isAdmin = $isIn && b5_is_admin();
?>
<input type="checkbox" id="nav-toggle" class="nav-toggle-box" aria-hidden="true" tabindex="-1">
<label for="nav-toggle" class="nav-toggle">&#9776; Menu</label>
<mainNav>
    <fieldset>
        <?php if ($isIn): ?>
            <legend>System Status:</legend>
            <div class="nav-status">
                <p class="online">&#128994; UPLINK ACTIVE</p>
                <p>Commander:<br>
                    <strong><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></strong>
                    <?php if ($isAdmin): ?><span class="badge-admin">ADMIN</span><?php endif; ?>
                </p>
            </div>
            <form action="Logout.php" method="post">
                <button type="submit" name="logoutSubmit" class="formButton btn-logout">Logout</button>
            </form>
        <?php else: ?>
            <legend>ID-Card-Check</legend>
            <form action="Login.php" method="post">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" placeholder="Username" required autocomplete="username">

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Password" required autocomplete="current-password">

                <!-- Honeypot security tracking barrier -->
                <input type="text" id="eMail" name="eMail" tabindex="-1" autocomplete="off">

                <button type="submit" name="loginSubmit" class="formButton">Login</button>
            </form>
        <?php endif; ?>
    </fieldset>

    <ul>
        <?php foreach ($navLinks as $key => [$href, $label]): ?>
            <li><a href="<?php echo $href; ?>"<?php echo $current_page === $key ? ' class="active" aria-current="page"' : ''; ?>><?php echo $label; ?></a></li>
        <?php endforeach; ?>
    </ul>
</mainNav>
