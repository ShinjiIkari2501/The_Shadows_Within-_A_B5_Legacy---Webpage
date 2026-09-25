<?php
// Ermittelt vollautomatisch den Namen der aktuell geladenen Datei (z.B. index.php)
$current_page = basename($_SERVER['SCRIPT_NAME']);
?>
<nav class="main-nav">
    <ul>
        <li>
            <a href="index.php" class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                🛰️ Main Terminal
            </a>
        </li>
        <li>
            <a href="Gameplay.php" class="<?php echo ($current_page == 'Gameplay.php') ? 'active' : ''; ?>">
                🎮 Tactical Deck
            </a>
        </li>
        <li>
            <a href="Characters.php" class="<?php echo ($current_page == 'Characters.php') ? 'active' : ''; ?>">
                👥 Crew Manifest
            </a>
        </li>
        <li>
            <a href="Story_Timeline.php" class="<?php echo ($current_page == 'Story_Timeline.php') ? 'active' : ''; ?>">
                ⏳ Chronology
            </a>
        </li>
        <li>
            <a href="Contact.php" class="<?php echo ($current_page == 'Contact.php') ? 'active' : ''; ?>">
                📡 Com-Array
            </a>
        </li>
    </ul>
</nav>
