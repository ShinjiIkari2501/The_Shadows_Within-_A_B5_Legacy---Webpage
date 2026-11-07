<?php 
// 1. Load layout center (Includes header, nav, and footer)
require_once 'Includes/layout.php'; 

// 2. Activate output buffering for main content
ob_start(); 
?>

<!-- PURE MAIN CONTENT BLOCK START -->
<h2>Strategic Development Vector & Horizons</h2>
<h3>Interstellar Alliance Long-Range Forecast / Systems Upgrade Roadmap</h3>

<p>
    Every journey through the deep black starts with a single line of code. As a solo developer handling this project, my approach is simple: focusing on what matters right now without building artificial corporate roadmaps.
</p>

<div class="char-container">
    <!-- Current Mission Goal -->
    <div class="char-card">
        <div class="char-info">
            <h4>Current System Status: Under Running Construction</h4>
            <p>The core framework is fully operational. The database architecture, all major choice vectors, and the logic pathways for all 7 distinct endings have been successfully built and cross-tested. The skeleton of the Liburnia is stable in flight—now the real engineering begins.</p>
        </div>
    </div>

    <!-- The Fine-Tuning Focus -->
    <div class="char-card">
        <div class="char-info">
            <h4>Next Milestone: Dialogue Polish & Atmospheric Vectors</h4>
            <p>The project is currently in a fluid Early Access phase. The main focus is shifting entirely toward deep narrative fine-tuning, fleshing out character-specific dialogues, and overall refinement. While the past week allowed for some rare, exceptional development time during class, further progress will strictly move forward during my personal free time. Although the atmospheric soundtrack inspired by the show's iconic synth elements is currently echoing only inside the maker's head, the structural paths to integrate these auditory sub-space loops are already waiting in the code.</p>
        </div>
    </div>
</div>

<p style="margin-top: 30px;">
    The focus remains on clean code, absolute respect for the canon, and delivering a completed, meaningful experience for everyone who loves this universe. Step by step, during free hours, until the script is finished.
</p>

<?php 
// 3. Store buffer content into variable
$seitenInhalt = ob_get_clean(); 

// 4. Render page template with title and content vectors
renderLayout("B5 Legacy - Strategic Roadmap", $seitenInhalt); 
?>
