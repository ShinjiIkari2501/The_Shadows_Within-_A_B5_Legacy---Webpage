<?php
/**
 * act3_content.php - the story data for Act III (free vector hub, Mars, assault, 7 endings)
 *
 * This file only RETURNS DATA (one big array). db_migrate.php reads it once and writes it into the
 * SQLite database. To change a text later, change it here and raise B5_SCHEMA_VERSION in
 * db_migrate.php (see the comment there), or simply edit the row in the database.
 *
 * ROW FORMATS
 *  nodes    : [node_id, title, sector, is_ending, description]
 *  choices  : [choice_id, from_node, next_node, text, credits, analysis_focus, origin,
 *              requires (list), sets (list), locked_hint, route]
 *  variants : [node_id, priority, requires (list), mode ('replace'|'append'), text]
 *  updates  : [choice_id => [column => new value, ...]]  (changes to choices that already exist)
 *
 * The rule syntax of "requires" and "sets" is explained at the top of story_engine.php.
 * The texts below use <<<'TXT' ... TXT blocks ("nowdoc"): everything between the markers is plain
 * text, so quotes and apostrophes need no escaping.
 */

return [

// =============================================================================================
// NODES (scenes)
// =============================================================================================
'nodes' => [

[114, 'Scene 15: The Free Vector (Mission Hub)', 'EAS Liburnia - Tactical Hub', 0, <<<'TXT'
The tactical hub of the Liburnia glows in the calm silver-blue of a perfectly tuned bridge. SHA'IN sits at the telemetry console, the routing vectors unfolded across the main viewport.

SHA'IN:
"Delenn's data crystal gave us routes nobody is watching. The engines are ready, Commander. Where do we jump first? But remember: every jump costs time, and the Corps' sleeper network grows while we hesitate."

{{STATUS}}
TXT
],

[115, 'Scene 16: Centauri Prime - The Net of the Spider', 'Centauri Prime - Imperial Throne Room', 0, <<<'TXT'
The Liburnia touches down on the grand landing platforms of Centauri Prime. EMPEROR LONDO MOLLARI sits on his heavy throne, his eyes narrow, filled with the weariness of a man carrying chains he cannot show.

LONDO MOLLARI:
"Ah, the Alliance emissary... Sheridan sends you to beg for my royal fleet, yes? I will lend my warships, but only in exchange for an exclusive first-strike pact for the Empire. Sign, and my fleet is yours. Refuse, and you leave my palace empty-handed."

The words sound rehearsed. Something small and dark rides on the Emperor's shoulder, and his tired eyes say something else than his mouth. Londo is not the one who wants this pact.

Behind a marble pillar a nervous figure watches you in silence: VIR COTTO.
TXT
],

[116, 'Scene 17: Narn Homeworld - The Citadel of Red Sand', 'Narn Homeworld - Orbit / Central Military Citadel', 0, <<<'TXT'
The Liburnia drops out of hyperspace into the burning crimson skies of Narn. Three massive G'Quan-class cruisers swing into formation and charge their green laser batteries. A hard voice crackles over the channel.

NARN COMMANDER:
"Unknown vessel, identify yourself or be torn apart!"

Below you lie the sand-scoured halls of the Central Military Citadel, where the Elders of the newly restored Kha'Ri wait to decide whether you are friend or threat.
TXT
],

[117, 'Scene 18: The Stranded Alliance Wreck', 'Hyperspace Transit Vector - Alliance Wreck', 0, <<<'TXT'
While maneuvering through the hyperspace streams, Sha'In intercepts a faint, distorted emergency signal. A drifting, lifeless ghost ship of the Interstellar Alliance is caught in a gravitational shear wave. Meldor senses a damaged matrix aboard.

The team boards the freezing, creaking cruiser to secure resources. Suddenly a massive steel girder tears loose directly above Sha'In, while a still-powered terminal blinks with the unlocked war chest of a dead crew.
TXT
],

[118, 'Scene 22: Lyta\'s Refuge (The Last Crossroad)', 'Smuggler Outpost - Asteroid Bar', 0, <<<'TXT'
The tracking vectors lead you into a dark, smoky bar buried deep inside a hollow asteroid. LYTA ALEXANDER sits in a dim corner, her hood pulled low. As you approach her table, her head snaps up. Her eyes flash in an unnatural, terrifying Vorlon blue.

LYTA (Mental Projection):
"You want my power to tear down Bester's walls. I do not want treaties. I want retribution. I want to see this damned Corps burn, down to the foundations!"
TXT
],

[120, 'Scene 23: The Mars Coup', 'Mars - Tharsis Crater Hangar', 0, <<<'TXT'
The Liburnia glides out of the dead angle of the orbital defense satellites and settles unseen into a hidden Edgars Industries hangar. A heavily encrypted holo-signal builds in the center of the bridge: MICHAEL GARIBALDI, chewing a wooden toothpick, his eyes full of personal worry.

GARIBALDI (Hologram):
"Good to see you made it. Bester buried his secret research station deep in the Tharsis crater. A frontal attack is suicide, so you go in as a ghost, through the secondary ventilation locks. I got you the original access codes. And Commander... Talia Winters was a friend. I want to know what that damned Corps did to her."

SHA'IN:
"A megacorporation hangar nobody registered. Mr. Garibaldi knows how to make an impression. Stealth mode engaged, we go down."
TXT
],

[121, 'Scene 24: In the Bowels of the Corps', 'Mars - Tharsis Crater Lab (Section 13)', 0, <<<'TXT'
Cold sterile steel and pulsing blood-red neon rule the endless underground corridors. The air tastes of ozone, disinfectant and sedatives. Behind thick armored glass the horror of Bester's work lies bare: abducted technomages strapped into cybernetic chairs, black Drakh implants pulsing under their skin like veins, their minds feeding the worldwide sleeper program.

MELDOR:
"They have defiled their bodies. They crossed the sacred geometries of my order with the unclean technology of the Shadows."

You reach the central control room of Section 13. The master plans of the sleeper network wait behind a wall of Psi-Corps encryption.
TXT
],

[122, 'Scene 25: The Escape in the Cockpit', 'Mars Atmosphere - Black Omega Starfury', 0, <<<'TXT'
The whole complex shudders under secondary explosions. You throw Meldor into the cockpit of the second pitch-black Black Omega Starfury and strap into the first. The hangar doors are blown away by internal blasts, and both fighters shoot out into the raging red sandstorm of Mars, up into the freezing orbit.

Three interceptors lock onto your tail. The transponders still call you friend, but the flight control is getting suspicious.

COMMANDER:
"Hold on, Meldor! I have to push this crate to its absolute limits!"
TXT
],

[119, 'Scene 26: The Reunion in Hyperspace', 'Hidden Nebula - Rebel Fleet Mess Hall', 0, <<<'TXT'
The captured Starfuries are pulled in by the Liburnia's tractor beam, and the ship slips into a dense, shimmering nebula deep in no man's land, where it docks with General Susan Ivanova's rogue rebel destroyer. IVANOVA steps onto the bridge, DR. STEPHEN FRANKLIN at her side, evacuated from his Earth bunker at great risk.

Then the galley door bangs open: MICHAEL GARIBALDI charges in with a sizzling pan of real Earth food. He sneaked aboard on Mars before you ever left orbit. Around the table the old command staff breaks bread and toasts to the memory of MARCUS COLE: with a tear in the eye, but loud, warm laughter about his unreadable Ranger poems and his habit of facing an army with a wooden staff.

IVANOVA:
"He was a fool. A noble, loyal fool. We honor his sacrifice by finishing this fight. My ships are locked onto our vector. We are going to hammer Bester's net into dust."
TXT
],

[180, 'Scene 22b: The Point of No Return', 'Mars Orbit - EAS Liburnia - Command Deck', 0, <<<'TXT'
The Liburnia hangs in the shadow of the Mars moons. Garibaldi's coordinates blink in the HUD. Sha'In looks at you with a serious face.

SHA'IN:
"Once we go down into that crater, Commander, there is no road back to the other worlds. The Corps will know we have been there. Section 13 will tighten every lock, and every ally we do not have by then, we will never have."

{{LOST_LIST}}

After the Mars operation only one detour remains before the assault on Earth: Lyta's refuge in the asteroid bar.
TXT
],

[181, 'Scene 27: The War Cabinet in Hyperspace', 'EAS Liburnia - Command Deck', 0, <<<'TXT'
The final, high-risk plan to attack Earth is coordinated. The tactical holo-grid shows the planetary defense lines.

IVANOVA:
"Bester coordinates the mass control through a heavily fortified ground jamming station. If it falls, the Gold Channels light up again and his grip breaks within seconds."

GARIBALDI will take command on Ivanova's flagship in orbit to run the fleet coordination and the media network, the ultimate symbol of the bond between free Mars and Earth. Ivanova draws her PPG, ready to lead the ground infiltration.

MELDOR:
"The eye of the Corps is watchful. A transponder will not fool Bester's paranoia. But my order will give you the theater of your life."

There is a last window before the descent. Only one place is still within reach: the hideout of Lyta Alexander.
TXT
],

[141, 'Scene 17b: The Council of the Kha\'Ri', 'Narn Homeworld - Central Military Citadel', 0, <<<'TXT'
G'Kar's seal flickers as a hologram on the Narn bridge monitors. A reverent silence falls over the channel, and the cruisers grant you free passage to the Citadel.

In the red sand the ELDER OF THE REGIME listens to your warning about Bester's global threat. The newly founded Kha'Ri convenes and votes on its very first historic decree: to stand by Earth. Setting their own hardship aside, they mobilize the elite Narn Avantgarde as loyal frontline fighters.
TXT
],

[142, 'Scene 17c: The Price of Silence', 'Narn Homeworld - Underground Market', 0, <<<'TXT'
In a smoke-filled cellar below the Citadel, a one-eyed broker counts your credits twice. The Narn regulars will never fly for you, but the underground sells what the Kha'Ri will not: hardened mercenaries with their own ships and no questions about whose war this is.

BROKER:
"Pleasure doing business, outsider. They fly for you. They do not die for you."
TXT
],

[151, 'Scene 18: The Fateful Pact', 'Centauri Prime - Imperial Throne Room', 0, <<<'TXT'
You sign. Londo's smile does not reach his eyes, and for a moment something small and dark seems to shift on his shoulder. The heaviest warships of the Empire are yours for the front, and in the background the Drakh influence over Centauri Prime rises.
TXT
],

[152, 'Scene 18: The Humanitarian Pact', 'Centauri Prime - Palace Corridors', 0, <<<'TXT'
Londo throws you out of the hall. In the corridor VIR COTTO catches your sleeve, his hands trembling.

VIR COTTO:
"Not here. Not where he can hear. Londo has never told me what is wrong, Commander. He hides it from me, and I think he does it to protect me. But I know him. When he drinks deeply enough, the thing on his shoulder goes quiet for a little while, and he talks. Fragments. The old raids on your shipping lines, the seal of the Throne Regent, masters who are not Centauri. The next morning he remembers nothing, and he looks at me as if I might have heard. I never asked. I only listened, and I have always had a nose for things that do not add up. Slowly I put the pieces together. I cannot prove it, but I believe the Drakh are behind all of it. So I have been preparing, quietly, where nobody looks, with every source I have. I cannot give you the fleet. But I can give you the Ranger-guarded rescue ships we hid for the civilians. Take them. Please. Somebody has to remember what we used to be, and somebody has to save him."

The covert Centauri rescue fleet is pledged to you, and the Drakh influence at court wanes.
TXT
],

[153, 'Scene 18: The Chamberlain\'s Price', 'Centauri Prime - Palace Corridors', 0, <<<'TXT'
In Centauri Prime's palaces everything has a price, and a smuggler knows the exchange rate. A fat envelope vanishes into the sleeve of the Emperor's chamberlain, and suddenly Londo's signature appears on a very different paper: his warships will fly with the Alliance, no exclusive pact, no humiliation.

The Drakh are not fooled, though. In the background their influence over the court grows a little, and the thing on the Emperor's shoulder seems to smile. Vir Cotto only watches you from the shadows, his face unreadable.
TXT
],

[161, 'Scene 18b: The Salvage', 'EAS Liburnia - Transit', 0, <<<'TXT'
You throw yourself over Sha'In as the girder crashes down. Burns tear across your back, but she is alive. Meldor lowers his staff and looks at you for a long moment: trust has been earned.

Before leaving the wreck you crack the terminal and secure the dead crew's war chest: 25,000 credits for the alliance.
TXT
],

[170, 'Scene 22a: The Pact of Vengeance', 'Smuggler Outpost - Asteroid Bar', 0, <<<'TXT'
You nod darkly and strike into her radical campaign.

COMMANDER:
"Bester started this war, Lyta. We finish it by his own rules. The Corps will burn. Every single cell."

Lyta smiles grimly, her eyes igniting in murderous god-light. She boards the Liburnia, ready to pulverize the headquarters. With her on board, Talia's rescue fades completely into the background.
TXT
],

[172, 'Scene 22a: The Seed', 'Smuggler Outpost - Asteroid Bar', 0, <<<'TXT'
You tell her that Section 13 is real, and that you will bring proof. Lyta's Vorlon blue flickers.

LYTA:
"Proof? I have heard promises for years. Bring me something I cannot burn, and maybe I will listen. Now go."

She vanishes into the smoke. Not a refusal forever, but a door that will only open for a Commander who comes back with evidence.
TXT
],

[173, 'Scene 26b: The Path of Light', 'Smuggler Outpost - Asteroid Bar', 0, <<<'TXT'
You lay the data pad with the stolen Section 13 streams on the table and show her the logs about Talia Winters.

COMMANDER:
"Talia is no tool of the Corps any more, Lyta. She is a victim. Bester forced her into a biological switching unit. If you smash the headquarters blind, you murder a sister. We destroy the artificial network, not the people."

Lyta's eyes widen. The Vorlon blue flickers and breaks into deeply human tears. Her blind hate collapses.

LYTA:
"They did that to her? Oh, God... No. I will go with you, Commander. I will hold my power back. We save Talia and burn Bester's net without sacrificing the innocent."
TXT
],

[174, 'Scene 26b: The Total Failure', 'Smuggler Outpost - Asteroid Bar', 0, <<<'TXT'
You miss the tone and argue purely in military terms. Lyta looks at you with icy contempt.

LYTA:
"You are just as blind as the generals in Geneva. You only want to use me as a weapon. Get out of my sight!"

She refuses to board and vanishes into the shadows of the asteroid. You will have to face the finale without her Vorlon power.
TXT
],

[183, 'Scene 24b: The Hangar Showdown', 'Mars - Tharsis Crater Hangar Bay', 0, <<<'TXT'
You copy the raw files into your log. Without the keys they are only noise, but Meldor holds a fragment against the light and flinches.

MELDOR:
"There is something older than Bester at the heart of this net. Shadow-tainted. I cannot read more than that."

Then you scan the central stasis chamber. According to the Section 13 files, the biological master switch of the entire global net should lie here: Talia Winters. The massive biocrystalline capsule in front of you is empty. The life-support cables hang slack, a faint blue residual signature flickering in the lines.

MELDOR:
"The logs show a security transfer three days ago. Destination classified at the highest level. You have the cipher and the data, Commander. But the fate of Garibaldi's friend remains a mystery."

A tonne-heavy steel bulkhead slams shut behind you. Red alarm lights flood the hangar, and an enormous figure detaches from the shadows of the Black Omega Starfuries: the WARMAGE, an elite Corps enforcer with implanted combat modules and glowing runes, a biomechanical staff spitting blue plasma in his hands.

WARMAGE (Mental Projection):
"The intruder from the silent sector... You think Michael Garibaldi's cheap corporate codes could fool the eyes of the Corps? This switch unit is the property of Alfred Bester. You will not leave this crater alive."
TXT
],


// ---- Hyperspace transits: the travel time between two worlds is used for crew scenes ----
[200, 'Transit: Course for Centauri Prime', 'Hyperspace - EAS Liburnia', 0, <<<'TXT'
The Liburnia slips into the shimmering orange of hyperspace. The engines settle into a steady hum, and the long hours toward Centauri Prime begin.
TXT
],

[201, 'Transit: Course for the Narn Homeworld', 'Hyperspace - EAS Liburnia', 0, <<<'TXT'
The Liburnia slips into the shimmering orange of hyperspace. Hours remain until the crimson skies of Narn.
TXT
],

[202, 'Transit: Course for the Asteroid Bar', 'Hyperspace - EAS Liburnia', 0, <<<'TXT'
The Liburnia leaves the main lanes and follows a smuggler's route through the outer dark. Hours remain until the hollow asteroid.
TXT
],

[203, 'Transit: Course for Mars', 'Hyperspace - EAS Liburnia', 0, <<<'TXT'
The Liburnia runs dark, every unnecessary system shut down, as the last jump toward Mars begins. Nobody is in a hurry to speak.
TXT
],

[204, 'Transit: The Last Detour', 'Hyperspace - EAS Liburnia', 0, <<<'TXT'
A short jump back out to the lawless fringe. In the hold the rebel fleet's engines are still warm.
TXT
],

// ---- The endings (is_ending = 1) ----
[128, 'Ending 7: Canon Resonance - The Sub-Space Frequency from Tuzanor', 'EAS Liburnia - Command Deck / Earth Orbit', 1, <<<'TXT'
Talia Winters and Lyta Alexander step forward together. Talia draws on her Ironheart legacy and weaves an empathic shield around the minds of the human sleepers, isolating Bester's activation pulse. At the same instant Lyta unleashes her Vorlon power. Because the control signal of the drones and the Shadow technology share one frequency, she sends a vast resonance wave through Garibaldi's hijacked channels. The sleeper commands are erased, the drones deactivated, yet the natural psi gifts of humanity remain untouched. The control matrix is destroyed, the headquarters collapses, Bester is left powerless, and every main character survives.

A quiet, respectful calm settles over the bridge of the Liburnia. The lifelike hologram of PRESIDENT JOHN SHERIDAN appears, broadcasting from a room of soft Minbari light in Tuzanor.

SHERIDAN (Hologram):
"The provisional government has ordered the complete dissolution of the Corps. Even if the public will never know of your covert mission... I am quite certain that Marcus would be especially proud of you. Come home. Sheridan out."

=== FADE TO WHITE - END OF THE SHADOWS WITHIN - ENDING 7 OF 7 ===
TXT
],

[190, 'Ending 1: The Diplomatic Triumph (A Sad Canon Ending)', 'Psi-Corps HQ - Matrix Chamber / Earth Orbit', 1, <<<'TXT'
Talia Winters overwrites the codes in peace, and the sleeper commands die without a single shot. Bester is led away in chains, his thumb finally off the detonator.

Then the headquarters begins to collapse. A ceiling beam tears loose above Delenn, Ivanova and you. From the smoke steps LENNIER. He has carried his guilt through all those years. Without a word, he throws himself under the falling stone and holds it long enough for all of you to escape, paying his old debt with the only coin he has left.

Earth is free. Talia lives. But on the bridge of the Liburnia nobody celebrates. They stand at the viewport in silence, a Minbari candle burning for the one who is not coming back.

SHERIDAN (Hologram):
"Marcus would have said Lennier finally found his way home. Come home, all of you. Sheridan out."

=== END OF THE SHADOWS WITHIN - ENDING 1 OF 7 ===
TXT
],

[191, 'Ending 2: The Price of the Empire', 'Earth Orbit - Centauri Flagship', 1, <<<'TXT'
The heavy Centauri warships tear through Bester's loyalist fleet, and the bombardment is devastating. The jamming station falls, the network collapses, Earth is free.

But the price has been written in advance. On the viewscreen Londo Mollari accepts the thanks of a liberated Earth, and at his shoulder, barely visible, the shadow that is not his own smiles. The Narn sectors are handed to the Empire in the treaty you signed. The Alliance, bound by your signature, cannot object.

SHERIDAN (Hologram):
"We won the war, Commander. And I am not sure what we lost with it. The Drakh have not been defeated. They have only changed hosts."

=== END OF THE SHADOWS WITHIN - ENDING 2 OF 7 ===
TXT
],

[192, 'Ending 3: The Silent Sacrifice', 'Psi-Corps HQ - Ruins', 1, <<<'TXT'
Bester falls to Ivanova's PPG, and the master detonator never fires. But the network does not release its captive. When the neural server finally shuts down, the cables that held Talia Winters go slack, and with them her last heartbeat. She gave the world its silence back with her own life.

Garibaldi's broadcast carries the truth about Section 13 to every screen on the planet. Earth is free. In the rubble nobody speaks of victory.

SHERIDAN (Hologram):
"The Corps is dissolved, Commander. Nobody will ever know what you did or what it cost. Marcus would have been proud, and heartbroken. Come home."

=== END OF THE SHADOWS WITHIN - ENDING 3 OF 7 ===
TXT
],

[193, 'Ending 4: Firestorm (The Canon Echo)', 'Psi-Corps HQ - Geneva', 1, <<<'TXT'
Lyta does not wait for a plan. Her eyes ignite in the full terrible light of the Vorlons, and she opens her mind like a furnace door. The Corps does not collapse, it burns: the matrix chamber, the training wards, every cable and every mind connected to the net.

From the smoke steps LENNIER, the disgraced Ranger who has carried his guilt for years. He raises his hands against the fire, not to stop Lyta, but to hold the walls long enough for the others to run. Ivanova screams at you to go. You go.

Behind you the headquarters tears itself apart in one white explosion. Lyta Alexander and Lennier do not come out. Bester, however, was never in the chamber: a hidden lift carried him away minutes earlier, and Garibaldi's voice on the radio is flat and cold.

GARIBALDI:
"He ran. Let him run. I will find him."

Earth is free of the Corps, and the price was exactly what Lyta asked for. Some of the dead were never part of the war.

=== END OF THE SHADOWS WITHIN - ENDING 4 OF 7 ===
TXT
],

[194, 'Ending 5: The Shadow Director', 'Psi-Corps HQ - Bureau 13', 1, <<<'TXT'
You do not hesitate. A cold shot ends Bester's life, and before the echo has faded your old Black Watch override codes are already in the system, rewriting the master matrix. Ivanova stares at you, her weapon still raised.

COMMANDER:
"The network stays. It simply answers to somebody else now."

Within a week the Corps is officially dissolved. Unofficially, a new, invisible Bureau 13 appears on no org chart, and its director controls the dreams of humanity from the shadows. Marcus Cole would not have recognized the person who now sits at his old partner's desk.

=== END OF THE SHADOWS WITHIN - ENDING 5 OF 7 ===
TXT
],

[195, 'Ending 6: The Silence (Game Over)', 'Earth Orbit - Wreckage', 1, <<<'TXT'
The assault force is too small. Without the fleets you did not win over, the Black Omega squadrons close the ring. The Liburnia takes hit after hit, Sha'In's voice breaks over the intercom, and the last thing on the viewscreen is a sapphire-blue Earth that stays silent.

The Corps tightens its net. Nobody ever finds out how close the Alliance came to ending it.

SHERIDAN (Hologram, fading):
"If anyone hears this... the sleepers are awake. Valen forgive us."

=== END OF THE SHADOWS WITHIN - ENDING 6 OF 7 ===
TXT
],

],

// =============================================================================================
// CHOICES (buttons)
// =============================================================================================
'choices' => [


// ---- Arrival after a transit (the choice id is the same as the node id) ----
[200, 200, 115, "Drop out of hyperspace over Centauri Prime", 0, 0, null, [], [], null, null],
[201, 201, 116, "Drop out of hyperspace over Narn", 0, 0, null, [], [], null, null],
[202, 202, 118, "Drop out of hyperspace beside the asteroid", 0, 0, null, [], [], null, null],
[203, 203, 120, "Drop out of hyperspace over Mars", 0, 0, null, [], [], null, null],
[204, 204, 118, "Drop out of hyperspace beside the asteroid", 0, 0, null, [], [], null, null],

// ---- Centauri Prime (node 115) ----
[100, 115, 151, "Sign Londo's military pact (heavy Centauri warships, Drakh influence rises)", 0, 0, null,
    ['!visited_narn'], ['pact=londo', 'drakh+=15', 'alignment-=15'], null, null],
[101, 115, 151, "Sign Londo's military pact (Narn learn of the betrayal immediately)", 0, 0, null,
    ['visited_narn'], ['pact=londo', 'drakh+=15', 'alignment-=15', 'narn_state=betrayed', 'narn_avantgarde=0', 'narn_done=0'], null, null],
[102, 115, 152, "Refuse Londo and follow Vir Cotto (covert rescue fleet, Drakh influence falls)", 0, 0, null,
    ['!visited_narn'], ['pact=vir', 'drakh-=15', 'alignment+=10'], null, null],
[103, 115, 152, "Refuse Londo and follow Vir Cotto (the Narn will hear that you defied him)", 0, 0, null,
    ['visited_narn'], ['pact=vir', 'drakh-=15', 'alignment+=10', 'narn_state=allied', 'narn_avantgarde=1'], null, null],
[125, 115, 153, "Smuggler's way: bribe the chamberlain (12,000 credits) for Londo's warships WITHOUT the exclusive pact", -12000, 0, null,
    ['origin=Underworld', '!visited_narn', 'credits>=12000'], ['pact=double', 'drakh+=10', 'alignment-=10'], null, null],
[126, 115, 153, "Smuggler's way: bribe the chamberlain (12,000 credits), the Narn will never learn of the price", -12000, 0, null,
    ['origin=Underworld', 'visited_narn', 'credits>=12000'], ['pact=double', 'drakh+=10', 'alignment-=10', 'narn_state=allied', 'narn_avantgarde=1'], null, null],
[104, 151, 114, "Leave Centauri Prime", 0, 0, null, [], [], null, null],
[106, 152, 114, "Leave Centauri Prime", 0, 0, null, [], [], null, null],
[134, 153, 114, "Leave Centauri Prime", 0, 0, null, [], [], null, null],

// ---- Alliance wreck (node 117): triggered on the first departure from Narn ----
[108, 117, 161, "Throw yourself over Sha'In (-20 HP, +25,000 credits from the war chest)", 25000, 0, null,
    [], ['hp-=20', 'alignment+=5', 'wreck_style=hero', 'wreck_done=1'], null, null],
[109, 117, 161, "Order Moka to hold the girder and hack the terminal first (-10 HP, +25,000 credits)", 25000, 0, null,
    [], ['hp-=10', 'wreck_style=tactical', 'wreck_done=1'], null, null],
[110, 161, 114, "Return to the Tactical Hub", 0, 0, null, [], [], null, null],

// ---- Narn Homeworld (node 116) ----
[111, 116, 141, "Show G'Kar's seal of the Kha'Ri, the last seal of the old council", 0, 0, null,
    ['seal', '!pact'], ['narn_state=allied', 'narn_avantgarde=1', 'narn_done=1', 'alignment+=5'], null, null],
[112, 116, 141, "Tactical alliance bluff: claim backing from the Alliance that you do not have", 0, 0, null,
    ['!pact'], ['narn_state=wary', 'narn_avantgarde=1', 'narn_done=1', 'alignment-=5'], null, null],
[113, 116, 141, "Address the Kha'Ri: you refused Londo's offer and stand with Narn", 0, 0, null,
    ['pact=vir'], ['narn_state=allied', 'narn_avantgarde=1', 'narn_done=1', 'alignment+=5'], null, null],
[114, 116, 142, "Hire the Narn underground mercenaries (20,000 credits)", -20000, 0, null,
    ['pact=londo', 'origin!=Underworld', 'credits>=20000'], ['narn_state=mercs', 'narn_mercs=1', 'narn_done=1', 'alignment-=5'], null, null],
[127, 116, 142, "Smuggler's connections: hire the Narn underground mercenaries at the insider price (12,000 credits)", -12000, 0, null,
    ['pact=londo', 'origin=Underworld', 'credits>=12000'], ['narn_state=mercs', 'narn_mercs=1', 'narn_done=1', 'alignment-=5'], null, null],
[135, 116, 141, "Address the Kha'Ri: speak of the Centauri fleet and keep the price to yourself", 0, 0, null,
    ['pact=double'], ['narn_state=allied', 'narn_avantgarde=1', 'narn_done=1'], null, null],
[115, 116, 117, "Withdraw from Narn (on the way out Sha'In picks up a distress signal)", 0, 0, null, ['pact=londo', '!wreck_done'], [], null, null],
[124, 116, 114, "Withdraw to the Tactical Hub", 0, 0, null, ['pact=londo', 'wreck_done'], [], null, null],
[120, 141, 117, "Leave the Narn Homeworld", 0, 0, null, ['!wreck_done'], [], null, null],
[121, 141, 114, "Leave the Narn Homeworld", 0, 0, null, ['wreck_done'], [], null, null],
[122, 142, 117, "Leave the Narn Homeworld", 0, 0, null, ['!wreck_done'], [], null, null],
[123, 142, 114, "Leave the Narn Homeworld", 0, 0, null, ['wreck_done'], [], null, null],

// ---- Reactor repair (node 109) depends on the ship calibration from scene 6 ----
[130, 109, 110, "Sprint down to the reactor section and force the manual plasma valve override (Minbari glyph displays: -25 HP)", 0, 0, null,
    ['calibration=2'], ['hp-=25'], null, null],
[131, 109, 110, "Sprint down to the reactor section and force the manual plasma valve override (perfect hybrid protocol: -10 HP)", 0, 0, null,
    ['calibration=3'], ['hp-=10'], null, null],

// ---- Lyta's refuge (node 118) ----
[140, 118, 170, "Take her side: swear to burn the Corps with her", 0, 0, null,
    ['mars_gate=0', 'alignment<=-15'], ['lyta=1', 'lyta_state=vengeance', 'lyta_visits=1', 'alignment-=10'], null, null],
[141, 118, 172, "Tell her Section 13 is real and that you will bring proof", 0, 0, null,
    ['mars_gate=0'], ['lyta_visits=1', 'lyta_state=refused'], null, null],
[128, 118, 172, "Smuggler's way: reach her through her own contacts in the Underworld", 0, 0, null,
    ['mars_gate=0', 'origin=Underworld'], ['lyta_visits=1', 'lyta_state=refused', 'lyta_contact=1'], null, null],
[142, 118, 114, "Leave without a word", 0, 0, null, ['mars_gate=0'], [], null, null],
[143, 118, 173, "Show her the Section 13 proof: Talia is a victim, not a weapon", 0, 0, null,
    ['mars_gate=1', 'mars_data', 'lyta_visits>=1', 'alignment>=0'],
    ['lyta=1', 'lyta_state=light', 'lyta_visits=2'],
    "Requires Mars proof, an earlier meeting with Lyta and a compassionate record (alignment 0 or higher)", null],
[129, 118, 173, "Show her the Section 13 proof: your Underworld contacts vouch for you", 0, 0, null,
    ['mars_gate=1', 'mars_data', 'lyta_contact', 'alignment>=-15'],
    ['lyta=1', 'lyta_state=light', 'lyta_visits=2'], null, null],
[144, 118, 173, "Show her the Section 13 proof: Talia is a victim, not a weapon", 0, 0, null,
    ['mars_gate=1', 'mars_data', 'lyta_visits=0', 'alignment>=15'],
    ['lyta=1', 'lyta_state=light', 'lyta_visits=2'], null, null],
[145, 118, 170, "Take her side: swear to burn the Corps with her", 0, 0, null,
    ['mars_gate=1', 'alignment<=-15'], ['lyta=1', 'lyta_state=vengeance', 'lyta_visits=2', 'alignment-=10'], null, null],
[146, 118, 174, "Argue purely militarily: she is a weapon you need", 0, 0, null,
    ['mars_gate=1'], ['lyta_visits=2', 'lyta_state=failed', 'alignment-=5'], null, null],
[147, 118, 181, "Step back to the Liburnia", 0, 0, null, ['mars_gate=1'], [], null, null],
[150, 170, 114, "Return to the Tactical Hub", 0, 0, null, ['mars_gate=0'], [], null, null],
[151, 170, 181, "Return to the war cabinet", 0, 0, null, ['mars_gate=1'], [], null, null],
[152, 172, 114, "Return to the Tactical Hub", 0, 0, null, [], [], null, null],
[153, 173, 181, "Return to the war cabinet", 0, 0, null, [], [], null, null],
[154, 174, 181, "Return to the war cabinet", 0, 0, null, [], [], null, null],

// ---- Mars (nodes 180, 120, 121, 183, 122) ----
[160, 180, 203, "Descend to Mars. There is no way back to the other worlds", 0, 0, null,
    [], ['mars_gate=1', 'jumps+=1', 'threat+=3'], null, null],
[161, 180, 114, "Not yet. Return to the Tactical Hub", 0, 0, null, [], [], null, null],
[162, 120, 121, "Slip into the ventilation locks with Meldor", 0, 0, null, [], [], null, null],
[163, 121, 183, "Break the Section 13 encryption with your own analysis", 0, 0, null,
    ['focus>=40'], ['mars_data=1'], "Requires analysis focus 40 or higher", null],
[164, 121, 183, "Buy the decryption keys from a Tharsis data broker (12,000 credits)", -12000, 0, null,
    ['origin=Underworld', 'credits>=12000'], ['mars_data=1'], null, null],
[165, 121, 183, "Copy the raw files without decrypting them (corrupted, no usable proof)", 0, 0, null,
    [], ['hp-=5'], null, null],
[166, 183, 122, "Shoot the Warmage down in open combat (-30 HP, Tharsis is alarmed)", 0, 0, null,
    [], ['hp-=30', 'alignment-=5', 'warmage=fight'], null, null],
[167, 183, 122, "Use Meldor's technomage barrier and flank him with an EMP shock", 0, 0, null,
    [], ['alignment+=5', 'warmage=barrier'], null, null],
[132, 183, 122, "Smuggler's way: pay a hangar technician to seal the fire doors between you and the Warmage (8,000 credits)", -8000, 0, null,
    ['origin=Underworld', 'credits>=8000'], ['warmage=bribe'], null, null],
[133, 183, 122, "Override the hangar security with your old Black Watch codes and lock the Warmage out", 0, 0, null,
    ['focus>=60'], ['warmage=override'], "Requires analysis focus 60 or higher", null],
[168, 122, 119, "Dive into the debris field and jump toward the waiting Liburnia", 0, 0, null, [], [], null, null],

// ---- Last window (node 181) ----
[169, 181, 204, "Detour: travel to Lyta's refuge in the asteroid bar", 0, 0, null,
    ['!lyta', 'lyta_visits<2'], ['jumps+=1', 'threat+=3'], null, null],

// ---- Final strike option for ruthless commanders (node 126) ----
[139, 126, 127, "Strike 3: Execute Bester with the Black Watch override codes", 0, 0, null,
    ['alignment<=-25'], ['final=execute'], "Requires a ruthless record (alignment -25 or lower)", null],

],

// Rows that older versions of this file created and that no longer exist.
'removed' => [105, 107],

// =============================================================================================
// UPDATES of choices that already exist in the database
// =============================================================================================
'updates' => [
    // Prologue: G'Kar hands over his seal, then the character selection follows (node 10).
    1   => ['next_node_id' => 10, 'sets' => ['seal=1']],
    998 => ['next_node_id' => 101],
    999 => ['next_node_id' => 101],
    // Early decisions move the alignment (needed for Lyta, Draal and the endings).
    6   => ['sets' => ['alignment-=5']],
    7   => ['sets' => ['alignment+=10']],
    8   => ['sets' => ['alignment-=5']],
    11  => ['sets' => ['alignment-=5']],
    // Ship calibration of scene 6.
    12  => ['sets' => ['calibration=1']],
    13  => ['sets' => ['calibration=2']],
    14  => ['sets' => ['calibration=3'], 'requires' => ['focus>=60'],
            'locked_hint' => 'Requires high analysis focus (60 or higher)'],
    16  => ['sets' => ['crystal=1']],
    // Reactor repair: -30 HP for the binary protocol (and for old saves without calibration).
    18  => ['choice_text' => 'Sprint down to the reactor section and force the manual plasma valve override (rigid human protocols: -30 HP).',
            'requires' => ['calibration!=2', 'calibration!=3'], 'sets' => ['hp-=30']],
    22  => ['sets' => ['hub_seen=1']],
    // The hub.
    23  => ['choice_text' => 'Vector 1: Return to Babylon 5 Medlab (treatment and briefing replay)',
            'requires' => ['mars_gate=0'], 'locked_hint' => 'Medlab is out of reach after Mars'],
    24  => ['choice_text' => 'Vector 2: Set course for Centauri Prime Imperial Palace',
            'requires' => ['mars_gate=0', '!pact'],
            'locked_hint' => 'Centauri Prime: the pact is already decided',
            'next_node_id' => 200,
            'sets' => ['visited_centauri=1', 'jumps+=1', 'threat+=3']],
    25  => ['choice_text' => 'Vector 3: Set course for Narn Homeworld Military Citadel',
            'requires' => ['mars_gate=0', '!narn_done'],
            'locked_hint' => 'Narn Homeworld: already resolved',
            'next_node_id' => 201,
            'sets' => ['visited_narn=1', 'jumps+=1', 'threat+=3']],
    26  => ['choice_text' => 'Vector 4: Infiltrate Tharsis Crater Lab on Mars (point of no return)',
            'next_node_id' => 180],
    27  => ['choice_text' => "Vector 5: Travel to Lyta's refuge in the lawless asteroid bar",
            'requires' => ['mars_gate=0', 'lyta_visits<1'],
            'locked_hint' => 'Lyta: she will not listen again without proof from Mars',
            'next_node_id' => 202,
            'sets' => ['jumps+=1', 'threat+=3']],
    // Final approach: moved from the hub to the war cabinet (node 181).
    28  => ['from_node_id' => 181, 'next_node_id' => 123, 'choice_text' => 'Initiate the stratospheric drop over Geneva',
            'requires' => null, 'locked_hint' => null],
    29  => ['next_node_id' => 181, 'sets' => ['alignment+=5']],
    30  => ['next_node_id' => 181],
    // Final confrontation.
    36  => ['sets' => ['final=meldor']],
    37  => ['sets' => ['final=melee']],
    38  => ['route' => 'ending'],
],

// =============================================================================================
// TEXT VARIANTS (scene text that changes with earlier decisions)
// =============================================================================================
'variants' => [

// Prologue: the seal of the Kha'Ri
[100, 0, [], 'append', <<<'TXT'
Before you turn to leave, G'Kar presses something heavy into your hand: a weathered stone seal. "The seal of the Kha'Ri. I am the last of the old council who still carries it. Where the Narn doubt you, let it speak for me."
TXT
],

// Draal's cryptic verdict (scene 13) depends on the record so far
[112, 10, ['alignment>=0'], 'append', <<<'TXT'
Draal lays a cool hand on your uninjured shoulder, his voice a whisper:
"You gather the shards of a broken mirror and believe you see the whole. A noble, laborious road. Beware the chamber where the echo of old betrayal sleeps. When you reach out in the red sand to break the chains, make sure you do not become the tool of the smith. Some gates open only to lock you in deeper."
TXT
],
[112, 10, ['alignment<0'], 'append', <<<'TXT'
The lights of the Great Machine flicker in the rhythm of his words. Draal steps back, his gaze stern:
"The river divides, traveler, and you already draw the blade through the water. A throne is only ever as high as the ash it is built upon. If the hunger for control steers your mind, you will rule the worldwide net, and the net will erase your own tracks in the dust."
TXT
],

// Medlab revisit
[113, 10, ['hub_seen'], 'replace', <<<'TXT'
Dr. Sarah Chambers guides the regeneration scanner over your wounds once more, fully restoring your HP. The hologram of Franklin is gone, but the Section 13 files still glow on the main screen, and Delenn's quiet words from earlier still hang in the room. Tessa Holloran nods at you from the door: the Liburnia is waiting.
TXT
],

// Centauri Prime after Narn
[115, 10, ['visited_narn'], 'replace', <<<'TXT'
The Liburnia touches down on Centauri Prime, and the Court has already heard from Narn. EMPEROR LONDO MOLLARI sits on his heavy throne, his eyes narrow, foaming with barely concealed anger.

LONDO MOLLARI:
"You come to me with the stench of Narn still on your boots! Sheridan wants my fleet? I demand absolute colonial authority over the Narn sectors once this war is over. Sign the military pact, or leave my palace empty-handed."

The demand rings hollow. Something small and dark rides on the Emperor's shoulder, and for a heartbeat his eyes plead with you. These are the words of the Drakh keeper, not of the man who once wept over Narn.

Behind a marble pillar a nervous figure watches you: VIR COTTO.
TXT
],

// Narn after Centauri
[116, 20, ['pact=londo'], 'replace', <<<'TXT'
The Liburnia drops out of hyperspace over Narn, and every battery of the orbital grid is already locked onto you. News travels fast through subspace.

ELDER OF THE REGIME:
"You sold our sovereignty to Londo Mollari! The seal of the Kha'Ri has lost its value. The regular Narn fleet will never fly for you."

Only the shadows remain. A smuggler contact in the lower city offers the services of Narn underground mercenaries for 20,000 credits. No honour, no questions.
TXT
],
[116, 20, ['pact=vir'], 'replace', <<<'TXT'
The Liburnia drops out of hyperspace over Narn, and the cruisers of the G'Quan class part to let you through. A triumphant reception awaits in the red dust: the Narn have heard that you defied the imperial arrogance of Londo Mollari.

ELDER OF THE REGIME:
"The outsider who refused the Emperor. Come. The Kha'Ri will hear you."
TXT
],

// Narn after the smuggler's double deal with Centauri
[116, 20, ['pact=double'], 'replace', <<<'TXT'
The Liburnia drops out of hyperspace over Narn. The cruisers of the G'Quan class hold their fire: news has reached the Kha'Ri that Centauri warships will fly with the Alliance, and nobody on Narn knows what it cost.

NARN COMMANDER:
"Centauri ships under an Alliance flag. Interesting. Come down, outsider. The Elders want to hear how you managed that."
TXT
],

// Narn council result depends on the approach
[141, 10, ['narn_state=wary'], 'replace', <<<'TXT'
After a long, tedious bureaucratic process the cruisers let you land, but two interceptors shadow every step. In the red sand the Elder studies you with deep distrust.

The Kha'Ri nonetheless votes: the Narn Avantgarde will fly with you, but they will not forget the bluff.
TXT
],
[141, 20, ['pact=vir'], 'replace', <<<'TXT'
In the Citadel the Elder of the regime listens, moved by your refusal of the imperial arrogance. The newly founded Kha'Ri, in its very first official decree, mobilizes the Narn fleet for the rescue of Earth. They set their own hardship aside and send their elite Avantgarde as loyal frontline fighters.
TXT
],

[141, 20, ['pact=double'], 'replace', <<<'TXT'
In the red sand the Elder of the regime listens to your report: the Centauri fleet will fly with the Alliance. The Kha'Ri weighs it carefully, a people that has learned to distrust Centauri gifts. In the end it votes for the Earth, and the elite Narn Avantgarde joins the fleet. Nobody asks what the Emperor's chamberlain was paid.
TXT
],

// Pact results after Narn
[151, 10, ['visited_narn'], 'append', <<<'TXT'
The ink is not yet dry when the news reaches Narn by subspace. The Kha'Ri cancels the alliance in fury, and the Avantgarde is permanently gone. Only the underground, and its price, remain.
TXT
],
[152, 10, ['visited_narn'], 'append', <<<'TXT'
Word travels fast: the Narn learn that you refused the Emperor. Their trust in you is confirmed, and the Kha'Ri fleet stays loyal.
TXT
],

// Mars: what the decrypted Section 13 files reveal (only with usable data)
[183, 10, ['mars_data'], 'replace', <<<'TXT'
The decrypted master plans show what Section 13 really is. At the heart of the sleeper network sits a salvaged Shadow device: the same kind of control core that once steered the Centauri cruisers like puppets, delivered to the Corps by the Drakh. The first devices of this kind were found years ago on the Drazi homeworld, in the wreckage of the raids that the Drakh, servants of the Shadows, launched through the authority of the Throne Regent to sow discord in the Alliance. Nobody recognised what they were. Alone such a device only commands machines. Bester found a way to make it command minds. The abducted technomages, their geometry twisted with Shadow technology, serve as living processors that carry the device's signal into every sleeper agent at once. Through them Bester controls all Section 13 operatives simultaneously, and through the operatives Earth's ministries, fleets and media.

MELDOR:
"My brothers are the lock and the key. As long as the device runs, they can neither die nor wake. Break the link at its source, in the relay beneath Geneva, and the chains fall from all of them at once."

Then you scan the central stasis chamber. According to the files, the biological master switch of the net should lie here: Talia Winters. The massive biocrystalline capsule in front of you is empty. The life-support cables hang slack, a faint blue residual signature flickering in the lines. A security transfer three days ago; destination classified.

A tonne-heavy steel bulkhead slams shut behind you. Red alarm lights flood the hangar, and an enormous figure detaches from the shadows of the Black Omega Starfuries: the WARMAGE, an elite Corps enforcer with implanted combat modules and glowing runes, a biomechanical staff spitting blue plasma in his hands.

WARMAGE (Mental Projection):
"The intruder from the silent sector... You think Michael Garibaldi's cheap corporate codes could fool the eyes of the Corps? This switch unit is the property of Alfred Bester. You will not leave this crater alive."
TXT
],

// The war cabinet: the plan changes with the knowledge about the device
[181, 8, ['mars_data'], 'append', <<<'TXT'
The Section 13 files change the plan. Ivanova taps the holo-grid: the relay beneath Geneva is the one point where the Shadow device and the technomage processors meet. Destroy it before the confrontation with Bester, and every sleeper agent on Earth is cut loose in the same second.
TXT
],
[181, 8, ['!mars_data'], 'append', <<<'TXT'
Without the Mars files the plan stays blunt: smash the jamming station, and hope that whatever feeds Bester's net breaks with it.
TXT
],

// The Geneva relay is destroyed shortly before the final confrontation
[125, 10, ['mars_data'], 'append', <<<'TXT'
Ivanova's ground teams do not stop to fight. The Section 13 files show them the way to the conduit hall beneath the lounge, where the cable bundles of the sleeper net run into the relay core: a Shadow device, wrapped in the living geometry of imprisoned technomages. Ivanova plants the charges herself. The core dies in a blue-white flash, and across the planet the sleeper agents collapse awake, their strings cut.

MELDOR:
"My brothers... they wake."

Bester's global control is broken. Only his failsafe remains.
TXT
],
[125, 10, ['!mars_data'], 'append', <<<'TXT'
Without the Mars files the way to the conduit hall is a guessing game. Three wrong corridors later Meldor feels the echo of the Shadow device and leads the teams down. Ivanova's charges bring the relay core down, but too late to spare the sleepers a few terrible minutes: across the planet agents collapse in convulsions before the strings finally snap.

Bester's global control is broken. Only his failsafe remains.
TXT
],


// ---- Crew scenes during the transits (sample drafts: replace them with your own texts) ----
// The counter "jumps" says which jump this is, so a scene can belong to one specific jump.
// Add as many variants as you like; every variant whose requirements are met is shown, highest priority first.
[200, 10, ['jumps=1'], 'append', <<<'TXT'
MOKA:
"Centauri. Always Centauri. In my clan we spit when we say the name."

SHA'IN:
"You spit when you say anything, Moka."

MOKA:
"Yes. But for this one I spit with feeling."
TXT
],
[200, 5, ['visited_narn'], 'append', <<<'TXT'
MELDOR:
"You carry the dust of Narn into the Emperor's court, Commander. A court like that smells everything."
TXT
],
[201, 10, ['!pact'], 'append', <<<'TXT'
Sha'In turns G'Kar's stone seal over in her fingers.

SHA'IN:
"It is lighter than I expected. For something a whole people is meant to obey."
TXT
],
[201, 10, ['pact=londo'], 'append', <<<'TXT'
MOKA:
"So you signed. And now we ask the Narn to look away while we do it. Very good plan."

Nobody answers him. Nobody tells him he is wrong, either.
TXT
],
[201, 10, ['pact=vir'], 'append', <<<'TXT'
Sha'In has finally stopped glancing at the exit hatches. Somebody has left her a mug of tea.
TXT
],
[202, 10, [], 'append', <<<'TXT'
MELDOR:
"She burns brightly, Commander. Fires consume whatever stands too close."
TXT
],
[203, 10, [], 'append', <<<'TXT'
SHA'IN:
"If anyone wants to be afraid, now would be a good time. Afterwards there will be no time."
TXT
],
[204, 10, [], 'append', <<<'TXT'
Somewhere behind the bulkhead Ivanova is shouting at a quartermaster. The sound is oddly comforting.
TXT
],

// Lyta: the smuggler network
[172, 10, ['lyta_contact'], 'replace', <<<'TXT'
The bar owner never asks your name. A few quiet words through the right smugglers, a few favours that do not appear in any ledger, and Lyta Alexander finally turns her chair toward you.

LYTA:
"You came through my own people. That tells me you at least know how to listen. I do not believe in promises, Commander. Bring me proof from the Corps' own vaults, and I will think about it. Now go."

She dissolves into the smoke. The door is not locked, but it only opens for someone who brings evidence.
TXT
],

// The Alliance wreck: tactical variant
[161, 10, ['wreck_style=tactical'], 'replace', <<<'TXT'
You shout at Moka, who braces his shoulder under the girder while you bridge the dead terminal. The Drazi grunts, bones cracking, but holds. The war chest unlocks, 25,000 credits for the alliance, and Sha'In scrambles free with her tool bag. Meldor only nods, unreadable beneath his hood, but the nod is there.
TXT
],

// Lyta after Mars
[118, 20, ['mars_gate=1'], 'replace', <<<'TXT'
The asteroid bar is as dark as before. Lyta sits in the same corner, but this time she watches you from the start, her Vorlon blue dimmed.

LYTA:
"You went to the crater. I felt the screaming even from here. So tell me, why should I follow you?"
TXT
],
[118, 5, ['mars_gate=1', 'lyta_visits>=1'], 'append', <<<'TXT'
She remembers your first visit and the promise you made.
TXT
],

// Mars: Lyta aboard
[121, 10, ['lyta_state=vengeance'], 'append', <<<'TXT'
Lyta watches the live feed from the Liburnia, silent. She distrusts Talia Winters as a former Corps tool, so this operation is about stolen data, nothing else.
TXT
],
[126, 10, ['lyta'], 'append', <<<'TXT'
At the back of the chamber the air hums blue. LYTA ALEXANDER has come, her eyes burning with the light of the Vorlons.
TXT
],

// Last window
[181, 10, ['lyta'], 'append', <<<'TXT'
Lyta stands apart from the hologram table, arms crossed, her eyes glowing faintly. She says nothing, but she is here.
TXT
],
[181, 5, ['!lyta', 'lyta_visits>=2'], 'append', <<<'TXT'
Lyta is beyond reach now. The asteroid bar is empty.
TXT
],

// Endings
[191, 10, ['lyta_state=light'], 'append', <<<'TXT'
Lyta's light touches the court of Centauri Prime for an instant, and the shadow on Londo's shoulder flinches, but the Drakh have only learned to hide.
TXT
],
[194, 20, ['origin=Underworld'], 'replace', <<<'TXT'
You do not hesitate. A cold shot ends Bester's life, and before the echo fades your smuggler contacts have already bought the last keys to the master matrix, wiring the sleeper net into your own cartel.

COMMANDER:
"The network stays. It simply answers to somebody else now."

Within a week the Corps is officially dissolved. Unofficially, an invisible syndicate with a new, quiet name controls the dreams of humanity from the shadows, and the Commander who once ran contraband now runs the minds of billions. Marcus Cole would not have recognized the person behind the desk.

=== END OF THE SHADOWS WITHIN - ENDING 5 OF 7 ===
TXT
],
[195, 20, ['hp<=0'], 'replace', <<<'TXT'
The wounds were too deep. The reactor burns, the controls slip from your fingers, and the sapphire Earth disappears behind the glare of a breaking hull. Sha'In's voice calls your name over the intercom, again and again.

Nobody will ever know how close you came.

=== END OF THE SHADOWS WITHIN - ENDING 6 OF 7 ===
TXT
],

],

];
