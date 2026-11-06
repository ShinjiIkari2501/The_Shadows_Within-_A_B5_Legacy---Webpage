<?php 
// 1. Die Layout-Zentrale laden (Sie beinhaltet bereits Header, Nav und Footer!)
require_once 'Includes/layout.php'; 

// 2. Den Zwischenspeicher für den HTML-Inhalt aktivieren
ob_start(); 
?>

<!-- HIER KOMMT NUR NOCH DER REINE INHALT FÜR DEN MAIN-BEREICH -->
<h2>Interstellar Chronology & Canonical Background</h2>
<h3>ISA Historical Archives / Centauri-Sol Tactical Record</h3>

<p>
    The road to the current geopolitical crisis did not begin in a vacuum. 
    Below is the verified chronological data stream detailing the tectonic shifts in galactic power, 
    from the shadows of the Great War to the isolated fortress of Earth in 2264.
</p>

<!-- START DES TIMELINE-STREAMS -->
<div class="timeline-stream">
            
    <!-- 2261: Das Ende des Krieges & Technologische Wende -->
    <div class="timeline-node">
        <div class="timeline-badge">2261</div>
        <div class="timeline-content">
            <h4>The Great Abzug & The Technological Leap</h4>
            <p>The Shadow War ends as the First Ones leave the galaxy. 
                The sudden availability of abandoned First One infrastructure sparks a massive clandestine reverse-engineering race. 
                Concurrently, Minbari fusion tech and Earthforce designs are merged to build the first advanced hybrid starships.</p>
        </div>
    </div>

    <!-- 2262: Die Geburtsstunde der ISA -->
    <div class="timeline-node">
        <div class="timeline-badge">2262</div>
        <div class="timeline-content">
            <h4>The Interstellar Alliance & The Centauri Destabilization</h4>
            <p>The Interstellar Alliance (ISA) is officially formed under President John Sheridan, creating a unified galactic government. 
                In the shadows of this new peace, the Drakh—the displaced, highly advanced servants of the Shadows—secretly seize control 
                of Centauri Prime, using imperial political ties to covertly undermine the Alliance's unity.</p>
        </div>
    </div>

    <!-- 2263: Ausbruch des Telepathen-Krieges -->
    <div class="timeline-node">
        <div class="timeline-badge">2263</div>
        <div class="timeline-content">
            <h4>The Telepath War & The Rogue Alliance</h4>
            <p>Open warfare erupts between rogue telepaths and the totalitarian Psi-Corps, tearing the Earth Alliance apart from within. 
                As the crisis escalates, secretive packs are formed between renegade factions and the Rangers (Anla'shok), 
                trading classified biological data for deep-space transport vectors.</p>
        </div>
    </div>

    <!-- 2264: Der Status Quo -->
    <div class="timeline-node">
        <div class="timeline-badge font-active">2264</div>
        <div class="timeline-content box-active">
            <h4>The Isolated Earth & The Biomechanical Crisis</h4>
            <p>The Psi-Corps inner circle seizes complete control of the Earth Alliance infrastructure, locking the entire Sol-System 
                behind an impenetrable planetary jam-wall. Isolated from the ISA, military shadow networks utilize highly classified Drakh 
                and Ipsha tech to merge biomechanical systems directly into human telepaths, breeding weaponized proxy assets.</p>
        </div>
    </div>

    <!-- 2265+: Der Epilog der Geschichte -->
    <div class="timeline-node">
        <div class="timeline-badge">2265+</div>
        <div class="timeline-content">
            <h4>The Horizons of the Jüngeren Rassen</h4>
            <p>The ultimate turning point for interstellar sovereignty. The structural fall of the old telepath grid leaves a massive power vacuum. 
                The path of the galaxy divides: either stabilizing through new ideological institutions or collapsing under the threat of advanced, 
                Drakh-corrupted empires by the late 2278s.</p>
        </div>
    </div>

</div>
<!-- ENDE DES TIMELINE-STREAMS -->

<?php 
// 3. Den Inhalt aus dem Zwischenspeicher in eine Variable packen
$seitenInhalt = ob_get_clean(); 

// 4. Das Layout aufrufen und den Titel der Seite + den Inhalt übergeben
renderLayout("B5 Legacy - Interstellar Chronology", $seitenInhalt); 
?>
