<?php 
// 1. Load layout center (Includes header, nav, and footer)
require_once 'Includes/layout.php'; 

// 2. Activate output buffering for main content
ob_start(); 
?>

<!-- PURE MAIN CONTENT BLOCK START -->
<h2>Project Vision & Features</h2>

<p>
    <strong>The Shadows within: A B5 Legacy</strong> is a tactical narrative simulation built to test your choices and loyalty in the year 2264. Every decision you make echoes through the system—locking or unlocking entire fleets, changing the political layout of the Interstellar Alliance, and leading to completely different epilogues.
</p>

<p>
    <strong>The Liburnia – A Living Hybrid Vessel:</strong> Your home in the deep black is an Earth-Ipsha hybrid prototype. Its biomechanical cores and crystalline matrix react directly to your modifications. You will manage a crew where distinct cultural traits collide—balancing the raw force of Drazi tech with precise Ipsha engineering, while coping with encrypted BabCom arrays and rigid Minbari berths.
</p>

<p>
    <strong>Character-Driven Synergies:</strong> The crew members possess unique dynamics and internal logic. You will witness the ancient Technomage Meldor becoming fascinated by the gritty, profane beauty of manual human relay systems, balanced by the vibrant engineering of Sha’In. Your interactions directly influence their trust index—surviving the shadows requires respecting both philosophy and hard iron.
</p>

<p>
    <strong>Behind the Terminal – The Maker's Mark:</strong> This project is the realization of a lifelong passion for space, Sci-Fi, and J. Michael Straczynski's universe. When hardware limits or system constraints try to hold things down, my directive has always been: <em>"How do we get it done anyway?"</em>
</p>

<p>
    Starting my training as a software developer in January 2026, I kicked off this project only four months later in May 2026. What began as a simple, raw Python script soon evolved into a massive, multi-layered development journey. Step by step, I pushed the project forward—moving from basic HTML to complex PHP, deploying the first active test phases, and completely re-engineering the entire game architecture into a fully database-driven system using MySQL/MariaDB. Along the way, shifting technical requirements forced server migrations, database refactoring, and a precise, detailed overhaul of the entire web interface.
</p>

<p>
    Beside Babylon 5, universes like <strong>Mass Effect</strong> were a massive influence on my life. Seeing how a great Sci-Fi world can come alive through code inspired me deeply—and looking at the many parallels, Mass Effect definitely owes a lot to Babylon 5 as well! Today, after months of continuous hard work, refactoring, and a deep dedication to the smallest detail, this modernized BabCom terminal is finally stable, optimized, and officially ready for its <strong>Early Access Phase</strong>.
</p>

<p>
    The goal wasn't to write a corporate resume—it was simply to create something meaningful that lasts. <strong>This terminal is my personal gift to everyone who shares this love for the stars—it will always remain 100% free, built by a fan, for the fans to enjoy and this is my way to show my respect to all of those great creators, actors and the rest of the teams that inspired me and dedicated to those who already passed.</strong>
</p>

<?php 
// 3. Store buffer content into variable
$seitenInhalt = ob_get_clean(); 

// 4. Render page template with title and content vectors
renderLayout("B5 Legacy - Project Vision & Features", $seitenInhalt); 
?>
