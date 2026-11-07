<?php
// 1. Load the layout
require_once 'Includes/layout.php';

/**
 * HOW THE PICTURES WORK
 * Put a .jpg (or .png / .webp) into the folder Images/ and the picture shows up by itself.
 * The file name only has to match one of the names in the list "images" of a character below.
 * Upper/lower case, spaces, "_" and "'" do not matter ("G_Kar.jpg", "gkar.jpg" and "GKar.JPG" are the same).
 * If no picture is found, the card shows a placeholder with the file name instead.
 */

// 2. Read the Images folder once and remember every picture: simplified name => real file name
$pictures = [];
foreach (glob(__DIR__ . '/Images/*.{jpg,jpeg,png,webp,JPG,JPEG,PNG,WEBP}', GLOB_BRACE) ?: [] as $path) {
    $file = basename($path);
    $simple = strtolower(preg_replace('/[^a-z0-9]/i', '', pathinfo($file, PATHINFO_FILENAME)));
    $pictures[$simple] = $file;
}

/** Looks for a picture for one character. Returns the file name or null. */
function findPicture(array $pictures, array $names)
{
    foreach ($names as $name) {
        $simple = strtolower(preg_replace('/[^a-z0-9]/i', '', $name));
        if (isset($pictures[$simple])) {
            return $pictures[$simple];
        }
    }
    return null;
}

// 3. All characters, sorted into groups. 'images' = file names that are accepted (without .jpg)
$groups = [
    'Liburnia Crew' => [
        ['name' => 'The Agent (Player Character)', 'class' => 'Ranger Special Operative', 'images' => ['The_Agent', 'Agent'],
         'text' => 'A ghost within the system since Marcus Cole died at your feet. Long before he wore the colors of the Anla\'shok, the two of you ran covert operations together, and the Rangers\' file on you now reads like a ghost story. You move through the deep black with the cold patience of someone who has stopped counting the days. The Chameleon Net at your belt is illegal in every jurisdiction that matters, and it is the reason the Psi-Corps has never seen you coming.'],
        ['name' => 'Meldor', 'class' => 'Technomage', 'images' => ['Meldor_Technomage', 'Meldor'],
         'text' => 'Emerging from hiding on G\'Kar\'s urgent advice, Meldor is the quiet, analytical anchor of the Liburnia. A Technomage keeps his secrets the way others keep their coats: close and always buttoned. "The eye of the Corps is watchful," he says, and promises that his Order will give you the theater of your life. Beneath the cold logic lies a fascination with human hardware, with dirty iron, loose wires and machines that break in honest ways.'],
        ['name' => 'Sha\'In', 'class' => 'Ipsha Master Constructor', 'images' => ['Shain_Ipsha', 'Shain', 'Sha_In'],
         'text' => 'Bursting with insectoid enthusiasm and unshakable optimism, Sha\'In keeps the prototype flying. She bridges the gap between ancient order secrets and profane Earthforce hardware, coaxing volatile engine cores with her trusty universal tool, and she talks to every conduit as if it could answer. Only when the hangar doors of a hostile crater come into sight does she stop smiling.'],
        ['name' => 'Karel', 'class' => 'Helmsman / Ranger', 'images' => ['Karel_Human', 'Karel'],
         'text' => 'Fully devoted to the path of the Anla\'shok ("We serve the One"). A brilliant helmsman under pressure, though his human anatomy severely clashes with the cultural discipline of sleeping on rigid, 45-degree Minbari berths. He has never once complained about it out loud, which is why everybody knows.'],
        ['name' => 'Moka', 'class' => 'Drazi Technician / Guard', 'images' => ['Moka_Drazi', 'Moka'],
         'text' => 'Like every Drazi he takes an argument personally and a victory even more so. Moka solves structural blockades with raw force and unstable engine overloads. Dangerous alone, he forms a remarkably constructive, highly volatile synergy with Sha\'In in the cargo bays.']
    ],

        'Babylon 5 & the Alliance' => [
        ['name' => 'Captain Elizabeth Lochley', 'class' => 'Earthforce Station Commander', 'images' => ['Lochley', 'Elizabeth', 'Elizabeth_Lochley'],
         'text' => 'The iron hand of Babylon 5: by the book, by the rule, by sunrise. Lochley stayed loyal to Earthforce through the civil war and stands between its regulations and the Alliance\'s quiet requests for protection. She is hard as nails in public and quietly helpful to anyone fighting a private war. She never had trouble with Bester before Babylon 5, she simply does not like him. What he thinks of her since the Byron affair, nobody knows, and she would rather not find out. A phoenix on her flight helmet reminds her where she came from.'],
        ['name' => 'Tessa Holloran', 'class' => 'Liburnia Liaison Officer', 'images' => ['Tessa', 'Tessa_Holloran'],
         'text' => 'Tessa counts the exits of every room before she sits down, and she sits down only when the count is right. She speaks little, notices everything, and stands at the Medlab door while Captains and Ambassadors argue. When the Liburnia is ready to leave, it is Tessa who nods first.'],
        ['name' => 'Zack Allan', 'class' => 'Chief of Station Security', 'images' => ['Zack', 'Zack_Allan'],
         'text' => 'Loyal to the last button, though his uniform has never quite fit and he never stops tugging at it. Zack has seen what a man can do to himself and came back with a crooked grin, a streetwise nose for trouble and a sharper mind than he lets on. Easygoing on the outside, he remembers every fragment of every file, and he reads people better than the scanners do.'],
        ['name' => 'Dr. Sarah Chambers', 'class' => 'Medlab Physician', 'images' => ['Chambers', 'Sarah', 'Sarah_Chambers', 'Dr_Chambers'],
         'text' => 'Calm, thorough and allergic to heroics. Dr. Chambers holds the regeneration scanner over every wound twice: once for the body and once for the story you tell about it. She guards the Section 13 files and asks no question whose answer she could not afford to know.'],
        ['name' => 'Dr. Stephen Franklin', 'class' => 'Exomedical Research Lead (ISA)', 'images' => ['Franklin', 'Stephen', 'Stephen_Franklin'],
         'text' => 'Working from a hidden bunker beneath the ruins of Geneva, Franklin uncovers Section 13\'s experiments on telepaths. He has fought his own war against chemical crutches and knows exactly what a cure can cost, which is why he never promises one. He works too long, sleeps too little and still apologizes before he cuts.'],
        ['name' => 'General Susan Ivanova', 'class' => 'Earthforce Resistance Leader', 'images' => ['Ivanova', 'Susan', 'Susan_Ivanova'],
         'text' => 'Russian stubbornness, a wit that cuts as clean as her orders, and a firm rule that nothing of importance happens before the first coffee, black and strong enough to strip paint. Ivanova does not do elegant maneuvers or retreats; she leads from the front and expects the same from everyone. Those who knew her before the war say she has not laughed properly in years.'],
        ['name' => 'Michael Garibaldi', 'class' => 'Former Security Chief / Mars Insider', 'images' => ['Garibaldi', 'Michael', 'Michael_Garibaldi'],
         'text' => 'Bald, sardonic and suspicious of anything that looks too easy. Garibaldi chews a toothpick where others chew their words, orders water where others order whiskey, and worships Daffy Duck, the household god of frustration, with a devotion he otherwise reserves for old motorcycles. He would pull the universe off its hinges for his friends and his family, and he trusts them a little too much, and he knows it.'],
        ['name' => 'Lyta Alexander', 'class' => 'Rogue Telepath / Vorlon-Touched', 'images' => ['Lyta', 'Lyta_Alexander'],
         'text' => 'Since the Vorlons changed her, her power is immeasurable. Her eyes flash in an unnatural Vorlon blue and her voice arrives directly in your mind. Lyta does not care for treaties or alliances: she wants retribution, and she wants to see the Corps burn down to its foundations. "Bring me something I cannot burn," she says, "and maybe I will listen." Behind the hatred sits someone who can still be reached, but only by those who come back with proof.'],
        ['name' => 'President John Sheridan', 'class' => 'President of the Interstellar Alliance', 'images' => ['Sheridan', 'John', 'John_Sheridan'],
         'text' => 'A captain who became a President and cannot sit still: every council chamber is a cage and every desk a punishment, except when Delenn is at his side. Then, and only then, he is quiet. He collects secrets, conspiracies and unexplained mysteries the way other men collect stamps, and every rumor in his archive has a name and a date. A phantom operation like this one is exactly the kind of question he cannot leave alone.'],
        ['name' => 'Delenn', 'class' => 'Ranger One / ISA Ambassador', 'images' => ['Delenn'],
         'text' => 'She steps out of the shadows only when the night is about to fall, and she chooses her words like stones for crossing a river. Delenn sees past the equation and past the mask, and tells you so in a single gentle sentence that you will carry for days. As leader of the Anla\'shok she guides the Rangers from Minbar. Whatever she places in your hand, she means for you to understand it later.'],
        ['name' => 'Lennier', 'class' => 'Minbari Ranger', 'images' => ['Lennier'],
         'text' => 'Courteous to a fault, strict with etiquette and funnier than anyone suspects. Lennier gets a joke a day late and then laughs for an hour. He bows half a second longer to those he respects and carries something unsaid with perfect posture. "Not every road leads away," he says. "Some lead back to the place where you stood."'],
        ['name' => 'Elric', 'class' => 'Technomage', 'images' => ['Elric'],
         'text' => '"We seek no comfort, G\'Kar. We seek clarity." Elric is ageless and weary, a Technomage whom the others heed without ever having been told to. He speaks of brothers who vanished without a trace from the protection of their fortresses, and of a shift in power that the veil refuses to name. He planned to hide the Order in exile and outlast the shadows, but now coordinates with the Interstellar Alliance against the Drakh hunters.']
    ],
        
    'Epsilon III' => [
        ['name' => 'Draal', 'class' => 'Keeper of the Great Machine', 'images' => ['Draal'],
         'text' => 'A Minbari who left Minbar because he no longer belonged there, and found a home in a machine that needs a mind. His only link to the outside is Delenn, who visits him from time to time. The Zathras brothers are his source of joy and of exasperation, and what the Machine gives him in return, nobody can even guess. He speaks in shards: "You gather the pieces of a broken mirror and believe you see the whole." What he says depends on the road you have already walked.'],
        ['name' => 'Zathras', 'class' => 'Caretaker of the Great Machine', 'images' => ['Zathras'],
         'text' => '"Zathras is used to being beast of burden to other people\'s needs." One of many brothers with almost the same name, he tends the Great Machine and speaks of himself in the third person, deadpan and put-upon. Nobody listens to Zathras until it is too late. The brothers miss the one who went away, and they never say where he went.']
    ],

        'Narn & Centauri' => [
        ['name' => 'G\'Kar', 'class' => 'Narn Seer / Citizen', 'images' => ['GKar', 'G_Kar'],
         'text' => 'He sits motionless by the dying fire, his one intact eye reflecting the flames, and speaks as if the night itself were listening. His wisdom and eloquence are of a kind that most will never reach: every sentence is weighed, and every silence is part of it. The Narn who once ruled by hatred has outlived his rage, and what remains is a voice that people listen to long after it has stopped.'],
        ['name' => 'Elder of the Kha\'Ri', 'class' => 'Elder of the Narn Regime', 'images' => ['Narn_Elder', 'Elder', 'Narn_Aeltester'],
         'text' => 'One of the few old friends G\'Kar has left. The two argued about everything for decades and agreed on the things that mattered. The Elder survived the occupation by keeping silent, and he is still silent, in a long, very Narn kind of waiting in which every answer is already weighed. In the red sand of the Citadel the newly restored Kha\'Ri must decide whether you are friend or threat.'],
        ['name' => 'Emperor Londo Mollari', 'class' => 'Emperor of Centauri Prime', 'images' => ['Londo', 'Londo_Mollari'],
         'text' => 'Once a man brimming with love, for his people, his friends and life itself. Londo now faces with quiet courage the fate he chose himself. The grand gestures remain, but the laughter has grown softer. Something small and dark rides on his shoulder, and his tired eyes say something else than his mouth.'],
        ['name' => 'Vir Cotto', 'class' => 'Former Ambassador / Centauri Court', 'images' => ['Vir', 'Vir_Cotto'],
         'text' => 'Once Ambassador to Babylon 5, now at Londo\'s court. Vir talks too fast, apologizes too often, and says out loud what everyone else only thinks. He has nervous hands, kind eyes and a better ear for danger than any bodyguard. He is the only one at court who still tells Londo the truth, and the only one Londo still listens to. He keeps a quiet list of everything he was ever told to forget, and he watches the Agent from behind a marble pillar, never taking sides until the moment he does.']
    ],

    'Psi-Corps' => [
        ['name' => 'Alfred Bester', 'class' => 'Psi-Corps Director', 'images' => ['Bester', 'Alfred', 'Alfred_Bester'],
         'text' => 'Courteous, patient and smiling. Bester lowers his voice instead of raising it, wears black leather and uses first names like a blade. He believes every telepath belongs to him, as family, and he never forgets a face or forgives one. His secret research station lies deep beneath the Tharsis crater on Mars.'],
        ['name' => 'Talia Winters', 'class' => 'Former Psi-Corps Telepath', 'images' => ['Talia', 'Talia_Winters'],
         'text' => 'A friend of the station: poised, always gloved, with a practiced smile and a laugh that sounded warmer than she allowed herself to be. Those who remember her ask only one question, quietly: what did the Corps do to her?']
    ],

    'In Memoriam' => [
        ['name' => 'Marcus Cole', 'class' => 'Ranger (Anla\'shok)', 'images' => ['Marcus', 'Marcus_Cole'],
         'text' => 'Your old partner. A Ranger with a quip for every danger and a verse for every goodbye, a romantic who hid it behind a joke and a fighter who hid that behind a smile. He swore that any problem could be solved with a good cup of tea and a bad poem. His light is still burning in the people he left behind.']
    ]
];

// 4. Build the HTML of all cards
ob_start();
?>

<style>
    .char-group {
        margin-top: 50px !important;  /* Schiebt die Überschrift nach unten weg von der Vorgruppe */
        margin-bottom: 20px !important; /* Sorgt für einen sauberen Abstand zu den Karten darunter */
    }
    /* Verhindert, dass die allererste Überschrift ganz oben verschoben wird */
    h3.char-group:first-of-type {
        margin-top: 20px !important;
    }
</style>

<h2>Liburnia Crew Manifest</h2>
<h3>ISA Tactical Files / Sector Green Archive</h3>

<?php foreach ($groups as $groupName => $characters): ?>
<h3 class="char-group"><?= htmlspecialchars($groupName) ?></h3>
<div class="char-container">
    <?php foreach ($characters as $c):
        $picture = findPicture($pictures, $c['images']);
    ?>
    <div class="char-card">
        <div class="char-image-placeholder">
            <?php if ($picture): ?>
                <img src="Images/<?= htmlspecialchars(rawurlencode($picture)) ?>" alt="Portrait of <?= htmlspecialchars($c['name']) ?>" loading="lazy">
            <?php else: ?>
                <span><?= htmlspecialchars(strtoupper($c['images'][count($c['images']) > 1 ? 1 : 0])) ?><br>.jpg</span>
            <?php endif; ?>
        </div>
        <div class="char-info">
            <h4><?= htmlspecialchars($c['name']) ?></h4>
            <p class="char-meta"><strong>Class:</strong> <?= htmlspecialchars($c['class']) ?></p>
            <p><?= htmlspecialchars($c['text']) ?></p>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endforeach; ?>

<?php
// 5. Get the content out of the buffer and render the layout
$pageContent = ob_get_clean();
renderLayout("Liburnia Crew Manifest - Tactical Files", $pageContent);
