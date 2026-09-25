<?php
// 1. Die Layout-Zentrale laden
require_once 'Includes/layout.php';

// 2. Den Zwischenspeicher für den HTML-Inhalt aktivieren
ob_start();
?>

<!-- HIER STARTET DER REINE INHALT FÜR DEN MAIN-BEREICH -->
<h2>Liburnia Crew Manifest</h2>
<h3>ISA Tactical Files / Sector Green Archive</h3>
        
<div class="char-container">
            
    <!-- 1. Der Agent -->
    <div class="char-card">
        <div class="char-image-placeholder">
            <img src="Images/The_Agent.jpg" width="150" height="150" alt="Portrait">
        </div>
        <div class="char-info">
            <h4>The Agent (Player Character)</h4>
            <p class="char-meta"><strong>Class:</strong> Ranger Special Operative</p>
            <p>A ghost within the system since the tragic loss of your brother, Marcus Cole. Driven by a ghost's determination, you operate with cold precision in the deep black. Armed with an illegal Chameleon Net, you are the shadow the Psi-Corps never sees coming.</p>
        </div>
    </div>

    <!-- 2. Meldor -->
    <div class="char-card">
        <div class="char-image-placeholder">
            <img src="Images/Meldor_Technomage.jpg" width="150" height="150" alt="Portrait">
        </div>
        <div class="char-info">
            <h4>Meldor</h4>
            <p class="char-meta"><strong>Class:</strong> Technomage</p>
            <p>Emerging from hiding on the urgent advice of G'Kar, Meldor acts as the krypto-analytical anchor of the Liburnia. Beneath his cold, stoic logic lies a profound fascination with the raw, gritty reality of manual human technology and dirty iron.</p>
        </div>
    </div>

    <!-- 3. Sha'In -->
    <div class="char-card">
        <div class="char-image-placeholder">
            <img src="Images/Shain_Ipsha.jpg" width="150" height="150" alt="Portrait">
        </div>
        <div class="char-info">
            <h4>Sha’In</h4>
            <p class="char-meta"><strong>Class:</strong> Ipsha Master Constructor</p>
            <p>Bursting with insectoid enthusiasm and unyielding optimism, Sha'In keeps the prototype flying. She bridges the gap between ancient order secrets and profane Earthforce hardware, fine-tuning volatile engine cores with her trusty universal tool.</p>
        </div>
    </div>

    <!-- 4. Karel -->
    <div class="char-card">
        <div class="char-image-placeholder">
            <img src="Images/Karel_Human.jpg" width="150" height="150" alt="Portrait">
        </div>
        <div class="char-info">
            <h4>Karel</h4>
            <p class="char-meta"><strong>Class:</strong> Helmsman / Ranger</p>
            <p>Fully devoted to the path of the Anla'shok ("We serve the One"). A brilliant helmsman under pressure, though his human anatomy severely clashes with the cultural discipline of sleeping on rigid, 45-degree Minbari berths.</p>
        </div>
    </div>

    <!-- 5. Moka -->
    <div class="char-card">
        <div class="char-image-placeholder">
            <img src="Images/Moka_Drazi.jpg" width="150" height="150" alt="Portrait">
        </div>
        <div class="char-info">
            <h4>Moka</h4>
            <p class="char-meta"><strong>Class:</strong> Drazi Technician / Guard</p>
            <p>Known for solving structural blockades with raw force and unstable engine overloads. While dangerous alone, he forms a remarkably constructive yet highly volatile synergy with Sha'In in the cargo bays.</p>
        </div>
    </div>

    <!-- 6. Captain Elizabeth Lochley -->
    <div class="char-card">
        <div class="char-image-placeholder">
            <span>LOCHLEY_COMMAND.DAT</span>
        </div>
        <div class="char-info">
            <h4>Captain Elizabeth Lochley</h4>
            <p class="char-meta"><strong>Class:</strong> Earthforce Station Commander</p>
            <p>The eiserne Hand of Babylon 5. Faced with severe sub-space distortion anomalies and phantom ships docking in secret, she maintains absolute tactical control. Lochley balances the strict regulations of Earthforce with the highly sensitive, verdeckte Schutzersuchen of the Interstellar Alliance.</p>
        </div>
    </div>

    <!-- 7. Zack Allan -->
    <div class="char-card">
        <div class="char-image-placeholder">
            <span>ALLAN_SECURITY.DAT</span>
        </div>
        <div class="char-info">
            <h4>Zack Allan</h4>
            <p class="char-meta"><strong>Class:</strong> Chief of Station Security</p>
            <p>Operating under immense pressure in the Zocalo and the lower Braun Sektor. Zack intercepts the Agent with dry humor but unwavering alertness. He handles the operational frontlines, tracking down clinical clean-ups and managing the security grids to prevent the Psi-Corps from locking down the station.</p>
        </div>
    </div>

    <!-- 8. Lyta Alexander -->
    <div class="char-card">
        <div class="char-info">
            <h4>Lyta Alexander</h4>
            <p class="char-meta"><strong>Class:</strong> Rogue P12 Telepath / Vorlon Vessel</p>
            <p>Recruited from her distant refuge to serve as the ultimate mental weapon. Lyta possesses terrifying vorlonische Urgewalt. She is the only entity capable of telepathically shattering Bester's bio-crystalline security grids, projecting a massive mental shield at the cost of intense psychological strain.</p>
        </div>
    </div>

    <!-- 9. General Susan Ivanova -->
    <div class="char-card">
        <div class="char-info">
            <h4>General Susan Ivanova</h4>
            <p class="char-meta"><strong>Class:</strong> Earthforce Resistance Leader</p>
            <p>Driven by the cold, unbeugsame Zorn of a commander determined to reclaim her home planet. Still carrying the deep trauma of Marcus Cole's sacrifice, Ivanova leads the dangerous surface assault in Geneva. Her tactical decisions during the Failsafe crisis decide whether she finds peace or lifetime isolation.</p>
        </div>
    </div>

    <!-- 10. Dr. Stephen Franklin -->
    <div class="char-card">
        <div class="char-info">
            <h4>Dr. Stephen Franklin</h4>
            <p class="char-meta"><strong>Class:</strong> Exomedical Research Lead (ISA)</p>
            <p>Operating from a hidden bunker underneath the ruins of Geneva. Franklin handles the exomedical diagnostics, uncovering Section 13's horrifying experiments on telepaths. He stands firmly by the Agent's side, driven by a deep desire to put an end to the biomechanical horrors and honor the legacy of fallen friends.</p>
        </div>
    </div>

    <!-- 11. President John Sheridan -->
    <div class="char-card">
        <div class="char-image-placeholder">
            <span>SHERIDAN_ALLIANCE.DAT</span>
        </div>
        <div class="char-info">
            <h4>President John Sheridan</h4>
            <p class="char-meta"><strong>Class:</strong> President of the Interstellar Alliance</p>
            <p>The ultimate political and military leader of the ISA. Operating from Tuzanor on Minbar, Sheridan issues the tactical mission parameters for the hybrid prototype <em>Liburnia</em>, authorizing a stealth fleet mobilization to bypass the planetary jam-wall surrounding Earth.</p>
        </div>
    </div>

    <!-- 12. Delenn -->
    <div class="char-card">
        <div class="char-image-placeholder">
            <span>DELENN_RANGER_ONE.DAT</span>
        </div>
        <div class="char-info">
            <h4>Delenn</h4>
            <p class="char-meta"><strong>Class:</strong> Ranger One / ISA Ambassador</p>
            <p>Als gewählte Anführerin der Anla'shok leitet Delenn die Geschicke der Ranger aus den Schatten von Minbar. Sie koordiniert das verdeckte Schutzersuchen der Allianz und stützt den Agenten mit ihrer unerschütterlichen Weisheit im Kampf gegen die finsteren Machenschaften des Psi-Corps.</p>
        </div>
    </div>

    <!-- 19. Elric -->
    <div class="char-card">
        <div class="char-image-placeholder">
            <span>ELRIC_ORDER.DAT</span>
        </div>
        <div class="char-info">
            <h4>Elric</h4>
            <p class="char-meta"><strong>Class:</strong> High Master of the Technomage Order</p>
            <p>The wise, weary leader who encountered G'Kar at the deep space campfire. Elric originally planned to hide the order in exile to outlast the shadows, but now coordinates with the Interstellar Alliance to protect his people from the unrelenting Drakh hunters.</p>
        </div>
    </div>

</div>

<?php
// 3. Den Inhalt aus dem Zwischenspeicher holen
$seitenInhalt = ob_get_clean();

// 4. Das Layout mit individuellem Titel rendern
renderLayout("Liburnia Crew Manifest - Tactical Files", $seitenInhalt);
?>
