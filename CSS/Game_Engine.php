<?php
// Start the centralized save-state system matching Level 13 rules
session_start();

// Initialize character base variables inside the session state if not set
if (!isset($_SESSION['scene_id'])) {
    $_SESSION['scene_id'] = 1;
    $_SESSION['player_hp'] = 100;
    $_SESSION['character_origin'] = 'Intelligence';
    $_SESSION['ship_calibration'] = 3;
}

// Handle player input choices using a clean and modern PHP switch block
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['choice'])) {
    $choice = trim($_POST['choice']);
    
    // SWITCH CONTROL SYSTEM MANAGING CHRONOLOGICAL SCENE PROGRESSION
    switch ($_SESSION['scene_id']) {
        case 2: // Scene 2 Escape Paths
            switch ($choice) {
                case '1': $_SESSION['scene_id'] = 3; break; // Path 1
                case '2': $_SESSION['player_hp'] -= 25; $_SESSION['scene_id'] = 3; break; // Path 2
                case '3': $_SESSION['scene_id'] = 3; break; // Class Action
            }
            break;
            
        case 6: // Scene 6 Calibration Paths
            switch ($choice) {
                case '1': $_SESSION['ship_calibration'] = 1; $_SESSION['scene_id'] = 7; break;
                case '2': $_SESSION['ship_calibration'] = 2; $_SESSION['scene_id'] = 7; break;
                case '3': $_SESSION['ship_calibration'] = 3; $_SESSION['scene_id'] = 7; break;
            }
            break;
            
        default:
            // Linear progression for purely narrative scenes
            $_SESSION['scene_id']++;
            break;
    }
}

// central content fetcher mapping the script scene-by-scene
$scene_text = "";
$options_html = "";

switch ($_SESSION['scene_id']) {
    case 1:
        $scene_text = "SCENE 1: PROLOGUE – THE ECHO OF THE PAST\nEXT. MOON IN NO MAN'S LAND - NIGHT\n\nA lonely, dying campfire crackles in the cold wind. CITIZEN G'KAR sits motionlessly by the fire. His deep, rough voice breaks the absolute silence of the night.\n\nG'KAR: 'The universe is in a constant change, unstoppable and blind to the suffering of those who cross its paths. We believed we had won the light of peace... but the darkness does not sleep.'\n\nOut of the deep shadows, High Master ELRIC and the young TECHNOMAGE MELDOR step forward into the light.";
        $options_html = '<button type="submit" name="choice" value="next" class="btn">Proceed to Babylon 5</button>';
        break;

    case 2:
        $scene_text = "SCENE 2: THE SPARK IN THE DARK\nINT. BABYLON 5 - MAINTENANCE CORRIDORS (BROWN SECTOR) - NIGHT\n\nA man in the torn, blood-stained robes of a Ranger breaks through a hatch and collapses directly in front of the Commander!\n\nTHE RANGER: 'Take it... Bring it... personally to the command staff... Trust no one...'\n\nThe blue searchlights of the Psi-Corps bloodhounds are already breaking around the corner!\n\nCHOOSE YOUR ESCAPE PATH:";
        $options_html = '
            <button type="submit" name="choice" value="1" class="btn">Path 1: Activate Cloaking Device</button>
            <button type="submit" name="choice" value="2" class="btn">Path 2: Flee into Ventilation Shafts</button>
            <button type="submit" name="choice" value="3" class="btn">Path 3: Trigger Class Reaction</button>';
        break;

    default:
        $scene_text = "The timeline streams forward into the uncharted sectors of the alliance matrix...";
        $options_html = '<a href="?restart=1" class="btn">Reset Core Engine</a>';
        break;
}

// Emergency restart override
if (isset($_GET['restart'])) {
    session_destroy();
    header("Location: ?");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>The Shadows Within - UI Core Terminal</title>
    <style>
        body { background-color: #0d0d13; color: #00ffcc; font-family: 'Courier New', monospace; padding: 40px; margin: 0; }
        .terminal { max-width: 900px; margin: 0 auto; background: #050508; border: 2px solid #00ffcc; box-shadow: 0 0 20px rgba(0, 255, 204, 0.2); padding: 30px; border-radius: 8px; }
        #scrolling-text { min-height: 250px; white-space: pre-wrap; line-height: 1.6; font-size: 16px; margin-bottom: 20px; text-shadow: 0 0 5px rgba(0, 255, 204, 0.5); }
        .controls { display: flex; gap: 15px; margin-top: 20px; }
        .btn { background: transparent; border: 1px solid #00ffcc; color: #00ffcc; padding: 12px 20px; font-family: inherit; font-size: 14px; cursor: pointer; border-radius: 4px; transition: all 0.3s; }
        .btn:hover { background: #00ffcc; color: #0d0d13; box-shadow: 0 0 10px #00ffcc; }
        #skip-btn { border-color: #ff3366; color: #ff3366; }
        #skip-btn:hover { background: #ff3366; color: #0d0d13; box-shadow: 0 0 10px #ff3366; }
        .hidden { display: none !important; }
        .stats { font-size: 12px; color: #888; border-bottom: 1px dashed #333; padding-bottom: 10px; margin-bottom: 20px; display: flex; gap: 30px; }
    </style>
</head>
<body>

<div class="terminal">
    <div class="stats">
        <span>ORIGIN: <?php echo $_SESSION['character_origin']; ?></span>
        <span>HP: <?php echo $_SESSION['player_hp']; ?>/100</span>
        <span>SCENE ID: <?php echo $_SESSION['scene_id']; ?></span>
    </div>

    <!-- The dynamic text container where the scrolling action takes place -->
    <div id="scrolling-text"></div>

    <!-- Skip button to instantly render the remaining paragraph -->
    <button id="skip-btn" class="btn" onclick="skipScrolling()">⚡ Skip Scrolling</button>

    <!-- Choice forms handle submission back to the PHP state machine -->
    <form id="choice-form" method="POST" class="controls hidden">
        <?php echo $options_html; ?>
    </form>
</div>

<script>
    // Raw script payload injection securely managed by the server side
    const sourceText = <?php echo json_encode($scene_text); ?>;
    let currentIndex = 0;
    let intervalTimer = null;
    const speed = 25; // Speed in milliseconds per character loop

    const textContainer = document.getElementById('scrolling-text');
    const skipButton = document.getElementById('skip-btn');
    const choiceForm = document.getElementById('choice-form');

    function startTextStreaming() {
        intervalTimer = setInterval(() => {
            if (currentIndex < sourceText.length) {
                textContainer.textContent += sourceText.charAt(currentIndex);
                currentIndex++;
                // Soft autoscroll down to keep tracking the terminal bottom lines
                window.scrollTo(0, document.body.scrollHeight);
            } else {
                completeStreamingPipeline();
            }
        }, speed);
    }

    function skipScrolling() {
        clearInterval(intervalTimer);
        textContainer.textContent = sourceText;
        completeStreamingPipeline();
    }

    function completeStreamingPipeline() {
        skipButton.classList.add('hidden');
        choiceForm.classList.remove('hidden');
    }

    // Ignite the core JavaScript engine upon document readiness
    window.onload = startTextStreaming;
</script>

</body>
</html>
