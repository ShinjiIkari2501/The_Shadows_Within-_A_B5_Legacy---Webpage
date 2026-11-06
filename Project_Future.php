<?php 
// 1. Die Layout-Zentrale laden (Sie beinhaltet bereits Header, Nav und Footer!)
// Achte auf das große "I" bei "Includes", damit XAMPP die Datei sicher findet
require_once 'Includes/layout.php'; 

// 2. Den Zwischenspeicher für den HTML-Inhalt aktivieren
ob_start(); 
?>

<!-- HIER KOMMT NUR NOCH DER REINE INHALT FÜR DEN MAIN-BEREICH -->
<h2>Strategic Development Vector & Horizons</h2>
<h3>ISA Long-Range Forecast / Systems Upgrade Roadmap</h3>

<p>
    Building a legacy takes time, precision, and dedication. As a solo developer working within personal free time, the evolution of <strong>The Shadows within: A B5 Legacy</strong> is planned across several distinct structural phases.
</p>

<div class="char-container">
    <!-- Phase 1: Expansion -->
    <div class="char-card">
        <div class="char-info">
            <h4>Phase I: Narrative Expansion & Choice Integration</h4>
            <p>The primary focus is deep-coding the interactive decision matrix. This involves fully implementing the behavioral variables for the core factions (Earthforce, Psi-Corps, and the Technomages) to ensure that every moral crossroad reliably steers the narrative toward its designated cosmic epilogue.</p>
        </div>
    </div>

    <!-- Phase 2: Audiovisual -->
    <div class="char-card">
        <div class="char-info">
            <h4>Phase II: Audiovisual Immersive Systems</h4>
            <p>Future iterations aim to enhance the atmospheric feedback of the interface. Plans include integrating a custom ambient synth score inspired by Christopher Franke's iconic arrangements, alongside localized voice-over logs to bring the encrypted sub-space transmissions to life.</p>
        </div>
    </div>

    <!-- Phase 3: Deployment -->
    <div class="char-card">
        <div class="char-info">
            <h4>Phase III: Tactical Combat & Database Launch</h4>
            <p>The long-term objective introduces a text-based, sub-light tactical simulation deck. Players will be able to actively command the <em>Liburnia</em> through hyperraum anomalies, utilizing customized engineering choices to override enemy signals in real-time.</p>
        </div>
    </div>
</div>

<?php 
// 3. Den Inhalt aus dem Zwischenspeicher in eine Variable packen
$seitenInhalt = ob_get_clean(); 

// 4. Das Layout aufrufen und den Titel der Seite + den Inhalt übergeben
renderLayout("B5 Legacy - Strategic Roadmap", $seitenInhalt); 
?>
