<?php 
// 1. Load layout center (Includes header, nav, and footer)
require_once 'Includes/layout.php'; 

// 2. Activate output buffering for main content
ob_start(); 
?>

<style>
    /* Removes the vertical timeline alignment border on the left */
    .timeline-stream {
        border-left: none !important;
        padding-left: 0 !important;
        margin-left: 0 !important;
    }
    .timeline-node {
        border-left: none !important;
        padding-left: 10px;
    }
</style>

<!-- PURE MAIN CONTENT BLOCK START -->
<h2>Interstellar Chronology & Canonical Background</h2>
<h3>ISA Historical Archives / Centauri-Sol Tactical Record</h3>

<p>
    The road to the current geopolitical crisis did not begin in a vacuum. 
    Below is the verified chronological data stream detailing the tectonic shifts in galactic power, 
    from the very moment humanity reached for the stars to the isolated fortress of Earth in 2264.
</p>

<!-- START OF TIMELINE STREAM -->
<div class="timeline-stream">

    <!-- 2100+: Der Aufbruch der Menschheit -->
    <div class="timeline-node">
        <div class="timeline-badge">2100+</div>
        <div class="timeline-content">
            <h4>The Dawn of Interstellar Travel & Sol Colonization</h4>
            <p>Humanity breaks its planetary chains, establishing the first permanent domed outposts on Mars and mining stations across the asteroid belt. The discovery of local jump-gate technology completely reshapes the future of the species, leading to rapid expansion beyond the Sol system and the foundation of the Earth Alliance.</p>
        </div>
    </div>

    <!-- 2230s-2248: Die großen Kriege -->
    <div class="timeline-node">
        <div class="timeline-badge">2230+</div>
        <div class="timeline-content">
            <h4>The Dilgar Conflicts & The Earth-Minbari War</h4>
            <p>The Earth Alliance intervenes in the Dilgar War, establishing humanity as a significant military power on the galactic stage. Decades later, a tragic misunderstanding during first contact triggers the devastating Earth-Minbari War, bringing humanity to the absolute brink of extinction until the unexpected surrender at the Battle of the Line in 2248.</p>
        </div>
    </div>

    <!-- 2249-2257: Das Babylon-Projekt -->
    <div class="timeline-node">
        <div class="timeline-badge">2249</div>
        <div class="timeline-content">
            <h4>The Babylon Project & The Last Best Hope</h4>
            <p>In an effort to maintain peace among the star-faring races, the Babylon Project is initiated. Following the mysterious sabotage of the first three stations and the sudden vanishing of Babylon 4, <strong>Babylon 5</strong> officially goes operational in 2257 in the Epsilon Sector as a neutral haven for trade and diplomacy.</p>
        </div>
    </div>

    <!-- 2258: Die Abreise der Technomagier -->
    <div class="timeline-node">
        <div class="timeline-badge">2258</div>
        <div class="timeline-content">
            <h4>The Technomage Exodus & Cosmic Omens</h4>
            <p>Elric leads the Technomages into deep space exile to preserve their ancient knowledge from the approaching Shadow storm. Their sudden departure leaves a profound void and serves as a dire warning to the younger races about the ancient darkness awakening in the rim sectors.</p>
        </div>
    </div>
            
    <!-- 2261: Das Ende des Krieges & Clarks Sturz -->
    <div class="timeline-node">
        <div class="timeline-badge">2261</div>
        <div class="timeline-content">
            <h4>The Shadow War & The Liberation of Earth</h4>
            <p>The galaxy-spanning Shadow War concludes as the First Ones depart into the Rim. Simultaneously, Captain John Sheridan leads a unified fleet of alien races and rogue Earthforce vessels to liberate Earth from the tyrannical, totalitarian regime of President Clark, restoring democracy.</p>
        </div>
    </div>

    <!-- 2262: Die Geburtsstunde der ISA, Byron & Der Centauri-Austritt -->
    <div class="timeline-node">
        <div class="timeline-badge">2262</div>
        <div class="timeline-content">
            <h4>The Interstellar Alliance & The Telepath Crisis</h4>
            <p>The Interstellar Alliance (ISA) is officially formed under President John Sheridan. Concurrently, Byron's rogue telepath colony seeks asylum on Babylon 5, leading to a tragic showdown that deeply fractures relations. Following clandestine shipping lane attacks, the Centauri Republic officially secedes from the Alliance, sliding the sector into a tense cold war.</p>
        </div>
    </div>

    <!-- 2262: Ausbau der Machtstrukturen -->
    <div class="timeline-node">
        <div class="timeline-badge">2262</div>
        <div class="timeline-content">
            <h4>The Political Machtvakuum & Bureau 13 Operations</h4>
            <p>With Earth recovering from its recent civil war and the military focus shifted toward the Interstellar Alliance, the Psi-Corps inner circle quietly expands its influence. Clandestine departments build deep-space black sites and start restructuring Earth's domestic security apparatus away from the Alliance's sight.</p>
        </div>
    </div>

    <!-- 2263: Ausbruch des Telepathen-Krieges -->
    <div class="timeline-node">
        <div class="timeline-badge">2263</div>
        <div class="timeline-content">
            <h4>The Telepath War & Global Unrest</h4>
            <p>Open conflict erupts across the Earth Alliance as rogue telepaths openly rebel against the draconian laws of the Psi-Corps. The internal war fractures military command chains, turning Earth into a boiling pressure cooker and forcing the government to deploy unprecedented planetary security protocols.</p>
        </div>
    </div>

    <!-- 2264: Der Status Quo -->
    <div class="timeline-node">
        <div class="timeline-badge font-active">2264</div>
        <div class="timeline-content box-active">
            <h4>The Isolated Earth & The Biomechanical Crisis</h4>
            <p>The Psi-Corps inner circle seizes complete control of the Earth Alliance infrastructure, locking the entire Sol-System behind an impenetrable planetary jam-wall. Isolated from the ISA, military shadow networks utilize highly classified Drakh and Ipsha tech to merge biomechanical systems directly into human telepaths, breeding weaponized proxy assets.</p>
        </div>
    </div>

    <!-- 2265+: Der Epilog der Geschichte -->
    <div class="timeline-node">
        <div class="timeline-badge">2265+</div>
        <div class="timeline-content">
            <h4>The Horizons of the Younger Races</h4>
            <p>The ultimate turning point for interstellar sovereignty. The structural fall of the old telepath grid leaves a massive power vacuum. The path of the galaxy divides: either stabilizing through new ideological institutions or collapsing under the threat of advanced, Drakh-corrupted empires by the late 2278s.</p>
        </div>
    </div>

</div>
<!-- END OF TIMELINE STREAM -->

<?php 
// 3. Store buffer content into variable
$seitenInhalt = ob_get_clean(); 

// 4. Render page template with title and content vectors
renderLayout("B5 Legacy - Interstellar Chronology", $seitenInhalt); 
?>
