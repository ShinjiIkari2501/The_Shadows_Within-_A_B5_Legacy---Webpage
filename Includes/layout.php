<?php
/**
 * layout.php
 * Central Master Layout Wrapper Framework for 'The Shadows Within - A B5 Legacy'
 * Manages the global HTML5 delivery, structural grids, and core flash messages.
 */

// Initialize central save-state session environment securely
session_start();

/**
 * Compiles the structural template frames around the unique page body fragments.
 */
function renderLayout($pageTitle, $bodyHtmlContent) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <!-- Tactical Metadata Layer -->
        <meta charset="utf-8">
        <meta name="description" content="The Shadows within: A B5 Legacy - A Canon-Compliant Fan Adventure">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="theme-color" content="#020215">
        <meta name="author" content="Daniel Baedorf aka Shinji2501">

        <title><?php echo htmlspecialchars($pageTitle); ?></title>

        <!-- THE MASTER LINK: Loads exactly the single design file and prevents cache conflicts -->
        <?php
        $cssFile = __DIR__ . '/../CSS/B5_Design_System.css';
        $version = is_file($cssFile) ? filemtime($cssFile) : time();
        echo '<link rel="stylesheet" href="CSS/B5_Design_System.css?v=' . $version . '">' . "\n";
        // The iconic 5 from the Babylon 5 logo font (see JS/b5_five.js)
        $jsFile = __DIR__ . '/../JS/b5_five.js';
        $jsVersion = is_file($jsFile) ? filemtime($jsFile) : time();
        echo '<script defer src="JS/b5_five.js?v=' . $jsVersion . '"></script>' . "\n";
        ?>
    </head>
    <body>
    <header>
        <div>
            <a href="Index.php">
                <img src="Images/Test1.jpg" width="1450" height="300" alt="Logo Banner">
            </a>
        </div>
    </header>

    <!-- Hot-plugging the strategic left sidebar navigation file -->
    <?php require_once 'Includes/mainNavigation.php'; ?>

    <!-- Master Content Display Node -->
    <main>
        <!-- ==========================================================================
             DYNAMIC TERMINAL FLASH-MESSAGE SYSTEM (INTEGRATED)
             ========================================================================== -->

        <!-- 1. ALERT BLOCK: Severe Database Error or Security Infraction -->
        <?php if (isset($_SESSION['flash_message_error'])): ?>
            <div class="flash-message-error">
                <?php echo $_SESSION['flash_message_error']; ?>
            </div>
            <?php unset($_SESSION['flash_message_error']); ?>
        <?php endif; ?>

        <!-- 2. SUCCESS BLOCK: Verification Clearance Granted -->
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="flash-message-success">
                <?php echo $_SESSION['flash_message']; ?>
            </div>
            <?php unset($_SESSION['flash_message']); ?>
        <?php endif; ?>

        <!-- Content Injection Site -->
        <?php echo $bodyHtmlContent; ?>
    </main>

    <!-- Harmonized Station Footer Bar -->
    <footer>
        <bottomNav>
            <ul>
                <li><a href="Impressum.php">Impressum</a></li>
                <li><a href="Contact.php">Contact</a></li>
                <li><a href="Datasecurity.php">Datasecurity</a></li>
            </ul>
        </bottomNav>
        <p>&copy; 2026 Daniel Baedorf. Non-profit fan project. Babylon 5 is TM & &copy; WB Ent. Inc.</p>

    </body>
    </html>
    <?php
}
?>
