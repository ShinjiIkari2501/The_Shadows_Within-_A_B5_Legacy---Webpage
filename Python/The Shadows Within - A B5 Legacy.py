import sys
import json
import base64

class BabylonZustand:
    def __init__(self):
        # 1. Charakter-Kernattribute (Analytische Skala)
        self.charakter_origin = ""         # 'Geheimdienst' oder 'Unterwelt'
        self.analyse_fokus = 85            # Startwert für intellektuelle Pfade
        self.lore_wissen = 80              # Startwert für Babylon 5 / Lore-Pfade
        self.psi_level = 0                 # P-Skala (P0 bis P6 freischaltbar)
        self.aktueller_screen = "START"    # Speichern des Aktuellen Ortes

        # 2. Wirtschaftssystem (An B5-Kurse angepasst)
        self.credits = 120000              # Realistische imperiale Kriegskasse

        # 3. Das Flaggschiff
        self.schiff_name = "Liburnia"      # Der Minbari-Erde-Hybrid-Prototyp
        self.schiff_erhalten = False       # Wird in Sektor 14 freigeschaltet
        self.schiff_tarnung = 35           # Prototyp-Tarnfeld (Upgradebar)

        # 4. Globale Allianz- und Beziehungs-Schnittstellen
        self.beziehungen = {
            "Lochley_B5": 50,
            "Gideon_Excalibur": 30,
            "Garibaldi_Mars": 50,
            "Ivanova_Erdflotte": 50
        }
        self.allianz_einfluss = {"Narn": 0, "Minbari": 0, "Mars": 0}

        # 5. Paranoide Bedrohungs- und Seuchen-Indikatoren
        self.bedrohung_bester = 75         # Besters Machtfaktor (Ziel: Senken)
        self.corps_schlaefer_aktivierung = 60
        self.drakh_seuche = 100            # Seuchen-Intensität auf der Erde
        self.grosse_maschine_energie = 20   # Epsilon 3 Kontrollwert

        # 6. Permanente Story-Flags (Weichenstellungen für die 5 Enden)
        self.zack_vertrauen = 0            # Zack Allans Integritäts-Bonus
        self.mystery_box_warnung = False   # Gideons Crusade-Artefakt-Flag
        self.garibaldi_kommando = False    # Garibaldis Flotten-Brücken-Status
        self.konstrukteurin_gerettet = False # Sha'In Rettungs-Status
        self.talia_persoenlichkeit = "Unterdrückt" # 'Wachsend' nach Syrius 4
        self.talia_trauma_geloest = False  # Bedingung für das Licht-Ende
        self.labor_planet_status = ""      # Status für das geheime Syrius-Finale
        self.control_entschluesselung = 0  # Fortschritt beim Hacken des Corps
        self.deserteur_gerettet = False    # Dr. Helix Status
        self.lyta_korruption = 50          # Lytas Vorlonen-Instabilität
        self.vir_entschlossenheit = 20     # Virs Weg zum Imperator
        self.vir_evolution = "Diplomat"    # Kann zum 'Meister_Stratege' reifen
        self.vir_geheimnis_gelueftet = False
        self.vintari_pfad = "Verbittert"   # Prinz Vintaris Schicksal
        self.centauri_zerstoerung = 50     # Schicksal von Centauri Prime
        self.lennier_status = "Unbekannt"  # Lenniers Sühnepfad im Schatten
        self.delenn_begegnet = False       # Allianz-Gipfel Trigger
        self.menschlicher_genpool = "Normal" # Schicksal der Telepathie

        # 7. Dynamisches Quest-Logbuch
        self.crew = []
        self.besuchte_orte = {
            "B5": False, "Centauri_Prime": False, "Narn": False,
            "Epsilon_3": False, "Minbar": False, "Mars": False,
            "Syrius": False, "Erde": False
        }

    def daten_integritaet_pruefen(self):
        """Validierung des Systemstatus"""
        return True if len(self.schiff_name) > 0 else False

# Globaler Ausgabepuffer für das Webinterface
web_output = []

def web_print(text=""):
    web_output.append(str(text))

def charakter_erstellung(welt, wahl=""):
    if wahl == "":
        web_print("\n[CHARAKTER-AUSWAHL: DIE RECHENSCHAFT DER VERGANGENHEIT]")
        web_print("Bevor du in die Schächte eintauchst, wähle deine Herkunft:")
        web_print("1 = GEHEIMDIENST-VETERAN (Hoher Analyse-Fokus, kennt militärische Protokolle)")
        web_print("2 = UNTERWELT-SCHMUGGLER (Kennt illegale Schleusen und unregistrierte Routen)")
        welt.aktueller_screen = "CHARAKTER_WAHL"
        return

def spiel_starten(welt):
    web_print("\n======================================================")
    web_print("=== AKT I: DER FUNKE IM DRECK ========================")
    web_print("======================================================")
    web_print("\nDie Luft im Braunen Sektor von Babylon 5 schmeckt nach recyceltem Sauerstoff,")
    web_print("billigem synthetischem Kaffee und dem Dunst unzähliger Frachterkühler.")
    web_print("Hier, in den untersten Versorgungsschächten, bewegst du dich im Graubereich.")
    web_print("Jede schattige Ecke hast du in ein logisches Raster eingeordnet.")
    web_print("Es ist die einzige Art, wie du nach dem tragischen Verlust deines Partners Marcus Cole überleben konntest.")

    web_print("\nPlötzlich stolpert eine Gestalt aus einer Wartungsschleuse.")
    web_print("Ein Mann in der zerfetzten Kluft der Rangers bricht direkt vor dir zusammen.")
    web_print("Hinter ihm, am Ende des Tunnels, scannen Psi-Corps-Agenten die Gasse mit Bioscannern.")

    web_print("\nOhne ein Geräusch zu machen, aktivierst du dein illegales Chamäleon-Netz.")
    web_print("Das holografische Feld summt minimal auf. Als das Licht der Agenten über dich gleitet,")
    web_print("sehen sie nur eine leere Wand und gehen irritiert weiter.")

    web_print("\nDer Ranger keucht, Blut tritt auf seine Lippen. Er blickt dir direkt in die Augen.")
    web_print("In seinen sterbenden Augen liegt stummes Erkennen. Er presst dir einen Kristall in die Hand.")

    web_print("\nDer sterbende Ranger flüstert mit rauer, abgehackter Stimme:")
    web_print(" 'Nimm ihn... Bring ihn... persönlich zum Kommandostab... Vertrau niemandem...'")
    web_print(" 'Die Schläfer erwachen... Wir sterben... für den Einen...'")

    web_print("\nEin letztes Rasseln, dann erschlafft sein Körper. Seine Finger lösen sich.")
    web_print("Das Anla'shok-Medaillon gleitet in deine Faust. Du stehst allein im Korridor.")
    web_print("Es ist ein zutiefst intimer, lautloser Moment. Du sprichst es nicht aus - doch das Erleben")
    web_print("dieser bedingungslosen Aufopferung löst einen tiefen, unumkehrbaren Trigger in dir aus.")
    web_print("Du begreifst stumm und schmerzhaft, was für ein unerbittliches Leben Marcus damals gewählt hatte.")
    web_print("Dieser Trigger bleibt dein Geheimnis, verborgen hinter einer unnahbaren Maske.")
    # KORREKTUR: Kein automatischer Folgeaufruf mehr hier drin!

def uebergabe_sicherheitszentrale(welt):
    web_print("\n[DER WEG ZUR SICHERHEITSZENTRALE]")
    web_print("Du nutzt unregistrierte Schmuggelwege und Servicekorridore des Braunen Sektors.")
    web_print("Erst direkt vor der Luftschleuse trittst du mit eisiger Dringlichkeit hervor.")
    web_print("Die Officers lassen dich irritiert in das private Büro von Zack Allan.")

    web_print("\nOhne ein Wort der Erklärung legst du den Kristall und das Medaillon auf die Konsole.")
    web_print("Du: 'Mr. Allan. Ein Ranger ist gerade im Braunen Sektor gestorben. Das Corps jagt'")
    web_print("     'diese Daten. Es war absolut lebenswichtig für ihn, dass dieser Kristall nur'")
    web_print("     'in die Hände der Stationsleitung gelangt. Sorgen Sie persönlich dafür.'")

    web_print("\nZack Allan blickt auf das Abzeichen, nickt grimmig und greift nach den Gegenständen.")
    web_print("Er packt den Kristall in seine Manteltasche und greift nach seinem Datenpad.")
    web_print("Zack Allan dreht sich um: 'Verdammt... Wo liegt die Leiche? Sagen Sie mir, wo er--'")

    web_print("\nDoch er spricht gegen die nackte Wand. In den zwei Sekunden seiner Ablenkung hast du")
    web_print("den perfekten Moment abgepasst und bist lautlos im unruhigen Strom untergetaucht.")

    web_print("\nWenig später schließt Zack Allan die schwere Panzertür des Ratsbüros.")
    web_print("Captain Elizabeth Lochley schiebt den Kristall in das gesicherte Allianz-Terminal.")
    web_print("Die Konsole summt auf. Ein lebensgroßes Holo-Bild flackert im Raum auf: G'KAR.")
    web_print("Seine aufgezeichnete, weise hallende Stimme erfüllt ehrfürchtig den Raum:")

    web_print("\nG'Kar (Holo): 'Liebe Freunde, wenn euch diese Nachricht erreicht, ist es hoffentlich'")
    web_print("              'noch nicht zu spät. In diesem Datenkristall findet ihr verschlüsselte'")
    web_print("              'Koordinaten. Dort findet ihr die Welt einer Zivilisation, die einst genau'")
    web_print("              'dasselbe finstere Schicksal erlitt wie euer Volk. Aber seid gewarnt...'")
    web_print("              'Was ihr in den Ruinen dieser toten Welt ausgraben werdet, kann eure'")
    web_print("              'Erloesung bedeuten - doch es birgt gleichermaßen ein Wagnis mit extremen,'")
    web_print("              'unumkehrbaren Risiken. Wer das Licht aus der Schwärze holen will, muss'")
    web_print("              'bereit sein, sich an den Flammen zu burnen. Möge das Universum'")
    web_print("              'euren Seelen gnädig sein.'")

    web_print("\nDas Bild erlischt. Lochley leitet die Koordinaten sofort an das Flaggschiff weiter.")
    web_print("Noch während die EAS EXCALIBUR im Orbit ihre Triebwerke hochfährt, glüht in Captain")
    web_print("Gideons Quartier die MYSTERY BOX warnend rot auf und flüstert eine kryptische Nachricht:")
    web_print("'Ein Verrat ist im Gange... Das Licht, das ihr sucht, wird die Ketten schmieden...'")
    web_print("Gideon ignoriert die Unheimlichkeit schweren Herzens. Die Excalibur springt in den Hyperraum.")

def excalibur_hentou_forschung(welt):
    web_print("\n[WELTRAUMSZENE - ORBIT ÜBER DEM HENTOU-PLANETEN]")
    web_print("Die EAS EXCALIBUR erreicht die sturmdurchpeitschten Koordinaten aus G'Kars Kristall.")
    web_print("Das Archaeologenteam bricht auf der Oberflaeche in einen alten Drakh-Komplex ein.")
    web_print("Sie bergen unzaehlige physische Artefakte, darunter einen leuchtenden Holo-Ball.")

    web_print("\n[SCHIFFSLABOR - EXCALIBUR]")
    web_print("Dr. Sarah Chambers und Max Eilerson untersuchen die Funde im Labor fieberhaft.")
    web_print("Dr. Stephen Franklin ist live ueber eine gesicherte Quantenleitung von der Erde zugeschaltet.")
    web_print("Gemeinsam analysieren sie die biochemischen Amplituden des Holo-Balls.")
    web_print("Ploetzlich herrscht eisige Stille ueber der Datenleitung. Die Erkenntnis ist markerschuetternd.")

    web_print("\nEilerson: 'Das ist biologischer Wahnsinn! Die Seuche wurde genetisch so boesartig'")
    web_print("          'konstruiert, dass das Gegenmittel bei kuenstlicher Synthese im Labor kollabiert.'")
    web_print("Chambers: 'Das Molekuel braucht eine stabilisierende Komponente waehrend der Synthese...'")
    web_print("Franklin (ueber Funk): 'Die hochgradig fokussierte, synchrone Geisteskraft von'")
    web_print("                      'Telepathen! Sie muessen die Matrix des Erregers mental fixieren.'")
    web_print("                      'Es geht physisch absolut nicht ohne das Psi-Corps!'")

    web_print("\nGideon nimmt Kurs auf die Erde, uebergibt eine Kopie an einen Sondergesandten")
    web_print("und muss das verzweifelte Ringen der Erdregierung im Senat mitverfolgen.")
    web_print("Noch bevor das Schiff Babylon 5 erreicht, kapituliert Praesidentin Roschenk schweren")
    web_print("Herzens vor dem Druck und bittet das Psi-Corps offiziell um Unterstuetzung.")

    web_print("\nDas Corps willigt ein. Das Serum wird reproduziert und auf der Erde verteilt (unter 1 Woche).")
    web_print("Einen Tag nach der letzten Lieferung startet das Corps an allen Orten die Aktivierung:")
    web_print("Die Garde besetzt alle Regierungsgebaeude. Die unwissenden Menschen auf den Strassen")
    web_print("feiern lautstark ihre Freiheit, waehrend im Hintergrund bereits die Handschellen zuschnappen.")

    web_print("\n[SZENEWECHSEL - SEKTOR BRAUN AUF BABYLON 5 / WENIGE STUNDEN SPÄTER]")
    web_print("Die Nachricht vom stummen Putsch hat die Station wie eine Schockwelle getroffen.")
    web_print("Du bewegst dich vorsichtig durch die dunklen Gassen, die Hand am PPG-Halfter.")
    web_print("Ploetzlich blockieren mehrere Sicherheitswachen den Korridor. Zack Allan tritt hervor.")

    web_print("\nZack Allan: 'Keine Bewegung. Ich verhafte dich nicht. Aber die Hoelle ist opengebrochen.'")
    web_print("Er packt dich am Arm und schiebt dich wortlos in die privaten Lifte der Hauptachse,")
    web_print("die dich direkt hoch ins Ratsbüro der Kommandozentrale bringen.")

def ratsbuero_verhoer(welt):
    web_print("\n[BABYLON 5 - RATSBÜRO DER KOMMANDOZENTRALE]")
    web_print("Captain Lochley fixiert die blinkenden Fehlerprotokolle der toten Relais.")
    web_print("Captain Matthew Gideon, frisch von der Excalibur-Mission zurückgekehrt, steht am Tisch.")
    web_print("Zack Allan schiebt dich in den Raum und schließt die schwere Panzertür.")

    web_print("\nGideon wirbelt herum, seine Augen blitzen vor Zorn: 'Special-Agent. Vor genau")
    web_print(" zwei Wochen bringen Sie uns diesen Kristall mit G'Kars Koordinaten. Wir fliegen hin,")
    web_print(" retten die Erde mit dem Serum, und jetzt, wenige Stunden später, riegelt das")
    web_print(" Corps den Planeten komplett ab! Reden Sie: What on earth is going on down there?'")

    web_print("\nDu (kühl und gefasst): 'Ich weiß exakt genauso wenig wie Sie alle hier im Raum.")
    web_print(" Über die aktuelle Blockade kann ich absolut nichts wissen.'")
    web_print("Lochley: 'Hat der Ranger im Sterben gar nichts gesagt? Kein einziges Wort?'")
    web_print("Du: 'Er keuchte nur: \"Nimm ihn... Bring ihn persönlich zum Kommandostab...\"")
    web_print("     Und seine letzten Worte waren: \"Die Schläfer erwachen... Wir sterben... für den Einen...\"'")

    web_print("\nGideon schlägt auf den Tisch: 'Schläfer? Jetzt ergibt alles einen Sinn! Unser")
    web_print(" Triumvirat hat bei der Untersuchung der Ruinen und Artefakte auf dem Planeten die furchtbare")
    web_print(" Entdeckung gemacht: Die Seuche brauchte das Corps! Das Serum wäre ohne die Geisteskraft")
    web_print(" von Tausenden Telepathen kollabiert.'")
    web_print(" 'Wir mussten Besters Leute an die Synthese-Terminals lassen, um die Menschen überhaupt vor dem")
    web_print(" Ersticken zu retten! Bester hat uns eiskalt benutzt. Er hat uns gezwungen, ihm die Erde")
    web_print(" auf einem Silbertablett zu liefern, nur um sie vor dem Sterben zu retten!'")

    web_print("\n[ALARMSIGNAL] Die Holoschirme im Ratsbüro werden per Zwangs-Code aktiviert!")
    web_print("Live aus Genf verkündet Alfred Bester inmitten der Black Omega Garde triumphierend:")
    web_print("Bester (Holo): 'Ab dem heutigen Tage steht die Erde unter der unumkehrbaren")
    web_print(" Verwaltung des Corps. Für die Normalen beginnt eine Ära als dienende Rasse.'")

    web_print("\nDas Bild bricht ab. Lochley ist aschfahl: 'Sie exekutieren Rebellen auf offener")
    web_print(" Straße und umstellen die Kirchen. Wir brauchen dringend jemanden im Schatten.'")
    web_print("Gideon packt dich am Kragen: 'Wer sind Sie wirklich? Warum gab er den Kristall ausgerechnet Ihnen?'")

    web_print("\nDu (weichst seinem Blick nicht aus): 'Weil ich Marcus Coles Partner beim Geheimdienst war,")
    web_print(" Captain. Als dieser Ranger im Schacht in meinen Armen starb, habe ich Marcus in ihm gesehen.")
    web_print(" Ich habe diesen Kristall aus Respekt vor Marcus' Vermächtnis hergebracht. Aber jetzt")
    web_print(" will ich verdammt noch mal wissen, in was für ein Komplott ich gezogen wurde. Ich will Antworten.'")

    web_print("\nZack Allan atmet tief durch: 'Marcus... Valen sei Dank. Er wusste, wem er vertraut.'")
    web_print("Gideon lässt dich los und nickt stumm: 'Respekt vor einem Toten... Das verstehe ich.'")
    web_print("Lochley: 'Wenn Sie Marcus' Partner waren, verstehen Sie die Dunkelheit. Was treibt Sie an?'")

    welt.aktueller_screen = "MOTIVATION_ABFRAGE"
    web_print("\nWähle deine Motivation:")
    if welt.charakter_origin == "Geheimdienst":
        web_print("1 = [PFLICHT] 'Weil meine Loyalität den Menschen gilt. Ich lasse das Corps nicht siegen.'")
        web_print("2 = [VERGELTUNG] 'Bester hat mir zu viel genommen. Ich will ihn bluten sehen.'")
        web_print("3 = [MARCUS' ERBE] 'Marcus hat an diese Allianz geglaubt. Ich werde sein Werk vollenden.'")
    else:
        web_print("1 = [ÜBERLEBEN] 'Wenn das Corps gewinnt, sind meine Schmuggelrouten tot.'")
        web_print("2 = [STOLZ] 'Niemand kontrolliert meine Flüge. Bester hat sich geschnitten.'")
        web_print("3 = [GEWISSEN] 'Marcus Cole hat mir einst das Leben gerettet. Ich begleiche meine Schulden.'")

def charakter_motivation_abfrage(welt, wahl):
    if wahl == "3":
        welt.beziehungen["Ivanova_Erdflotte"] += 25
        web_print("\n-> Zack Allan: 'Er meint es ernst, Elizabeth.'")
    else:
        welt.beziehungen["Lochley_B5"] += 20
        web_print("\n-> Lochley nickt.")

    web_print(f"\nGideon: 'Gut. Meine Mystery Box hat mich gewarnt... Sheridan hat diese")
    web_print(" Krise vorausgesehen. Im geheimen Sektor 14 ließ er einen Prototyp bauen,")
    web_print(" unantastbar für die Earthforce: Die LIBURNIA. Ein Erde-Minbari-Hybrid.'")
    web_print("Lochley: 'Doch das Corps weiß davon. Ihre Chefkonstrukteurin Sha'In wurde entführt.'")
    web_print("Gideon: 'Nehmen Sie die LIANDRA. Finden Sie das Mädchen und holen Sie dieses Schiff!'")
    web_print(" 'Ab jetzt beginnt die Rettungsmission, und das Spiel startet nach unseren Regeln!'")

    web_print("\n======================================================")
    web_print("=== AKT II: DAS GALAKTISCHE SCHACHBRETT ==============")
    web_print("======================================================")
    web_print("\n[HYPERRAUM-AUSSENPOSTEN MIT DER LIANDRA]")
    web_print("Du infiltrierst das unruhige Piratenversteck im Sektor 14.")
    web_print("Sha'In ist in einer hochfrequenten Energiezelle gefangen. Zwei Wachen patrouillieren.")

    if welt.analyse_fokus >= 75:
        web_print("3 = [INTELLEKT] Die Leitungen kalkulieren und die Zelle lautlos sprengen")
    web_print("1 = Eine mentale Psi-Ablenkung riskieren")
    web_print("2 = Ein offenes, hartes Feuergefecht starten")
    welt.aktueller_screen = "QUEST_RETTUNG"

def quest_rettung_konstrukteurin(welt, wahl):
    if wahl == "3" and welt.analyse_fokus >= 75:
        web_print("\n[ANALYSE-SIEG] Ein Kurzschluss öffnet die Zelle lautlos. Kein Alarm.")
        welt.konstrukteurin_gerettet = True
    else:
        web_print("\n[KAMPF] Du feuerst dein PPG! Die Wachen fallen, aber du verlierst 15.000 Credits.")
        welt.credits -= 15000
        welt.konstrukteurin_gerettet = True

    web_print("\nSha'In springt heraus, wischt sich den Schmutz von der Wange und strahlt:")
    web_print(" 'Hallo! Oh, Valen sei Dank, meine süße LIBURNIA hat mich schon vermisst!'")
    web_print(" 'Komm schnell, wir müssen das Schiff hochfahren!'")

    welt.schiff_erhalten = True
    welt.crew.append("Sha'In")

    welt.aktueller_screen = "NAVIGATIONS_KONSOLE"
    navigations_konsole(welt, "")
def navigations_konsole(welt, wahl):
    if not welt.schiff_erhalten: return

    # Ziele ansteuern bei Eingabe
    if wahl == "1": ort_babylon_5(welt)
    elif wahl == "2":
        welt.aktueller_screen = "SUB_CENTAURI"
        web_print("\n[CENTAURI PRIME - DER KÖNIGLICHE PALAST]")
        web_print("Die brennenden Ruinen rauchen. Zwei Machtstrukturen agieren im Verborgenen.")
        web_print("1 = Vir Cotto im Palastgarten treffen\n2 = Direkt an Imperator Londo Mollari herantreten")
        return
    elif wahl == "3":
        if welt.vintari_pfad == "Drakh_Marionette":
            web_print("\n[ABSOLUTER ALLIANZ-ABBRUCH] Die Narn verweigern jede Hilfe wegen deines Londo-Paktes!")
            welt.allianz_einfluss["Narn"] = -100
        else:
            welt.aktueller_screen = "SUB_NARN"
            web_print("\n[NARN - DIE HEIMATWELT DES KHRI-RATS]")
            web_print("Ein weiser Ältester führt dich in einen Bergtempel. Wie antwortest du?")
            if welt.lore_wissen >= 80: web_print("3 = [SPIRITUELLER PFAD] Eine Rede über Tyrannei halten.")
            web_print("1 = Untergrund-Warlords anheuern (30.000 Credits)\n2 = Nur um Logistik bitten")
            return
    elif wahl == "4":
        welt.aktueller_screen = "SUB_EPSILON"
        web_print("\n[EPSILON 3 - IM PLANETENKERN DER GROSSEN MASCHINE]")
        web_print("Draal und Zathras erwarten dich. Draal fordert dich heraus:")
        web_print("1 = [PHILOSOPHISCH] Draals Prüfung annehmen (Erfordert Psi-Level P5)\n2 = Zeit-Schild um B5 legen")
        return
    elif wahl == "5": ort_minbar(welt)
    elif wahl == "6": ort_mars_erweitert(welt)
    elif wahl == "7": ort_labor_planet_syrius(welt)
    elif wahl == "8" and welt.control_entschluesselung == 999:
        welt.aktueller_screen = "ERDE_INFILTRATION"
        ort_erde_infiltration(welt, "")
        return
    elif wahl == "9":
        welt.aktueller_screen = "SUB_KRIEGERAT"
        interstellarer_kriegsrat_babcom(welt, "")
        return

    # --- DYNAMISCHER ERST-EINSTIEG ---
    if not welt.besuchte_orte["B5"] and not any(welt.besuchte_orte.values()):
        web_print("\n------------------------------------------------------")
        web_print(f"AN BORD DER '{welt.schiff_name.upper()}' - TRIEBWERKE ONLINE")
        web_print("------------------------------------------------------")
        web_print("Ivanovas Voice bricht im statischen Rauschen ab: 'Special-Agent... hören Sie...'")
        web_print("Sha'In keucht: 'Der Corps-Störsender ist zu stark! Wir müssen nach Epsilon 3!'")
        welt.beziehungen["Ivanova_Erdflotte"] += 10

    # --- STATUSBERICHT ---
    web_print("\n======================================================")
    web_print(f"=== STATUSBERICHT: AN BORD DER {welt.schiff_name.upper()} ===")
    web_print("======================================================")
    if welt.grosse_maschine_energie == 100:
        web_print(" -> 'Die Epsilon-Standleitung läuft super! Draals Energie summt perfekt durch den Rumpf.'")
    else:
        web_print(" -> 'Der Funk ist tot. Wir empfangen nur kosmisches Rauschen von der Erde.'")

    web_print("\nWÄHLE DEIN NÄCHSTES ZIEL ODER REIFE DEINE STRATEGIE:")
    web_print("1 = Sektor Babylon 5 | 2 = Centauri Prime | 3 = Heimatwelt Narn")
    web_print("4 = Epsilon 3        | 5 = Minbar-Sektor   | 6 = Mars-Sektor")
    web_print("7 = Syrius 4")
    if welt.control_entschluesselung == 999:
        web_print("8 = DIE ERDE (STRATOSPHÄREN-STURZFLUG STARTEN!)")
    else:
        web_print("8 = [GESPERRT] Die Erde (Erfordert strategischen Kriegsrat in Option 9)")
    web_print("9 = [KRIEGERISCHER RAT] Große Babcom-Konferenz")

def sub_epsilon_entscheidung(welt, wahl):
    welt.besuchte_orte["Epsilon_3"] = True
    if wahl == "1" and welt.psi_level >= 5:
        welt.grosse_maschine_energie = 100
        web_print("\n[DRAALS MAIESTÄTISCHER ERFOLG - DIE ERDEN-BRÜCKE STEHT!]")
        web_print("Ivanovas Funk bricht glasklar durch: 'Die Leitung steht! Rettet uns!'")
    else:
        welt.grosse_maschine_energie -= 40; welt.lyta_korruption -= 20
        web_print("\nAuf das Upgrade verzichtet oder Prüfung fehlgeschlagen.")
    welt.aktueller_screen = "NAVIGATIONS_KONSOLE"
    navigations_konsole(welt, "")
def sub_centauri_entscheidung(welt, wahl):
    welt.besuchte_orte["Centauri_Prime"] = True
    if wahl == "1":
        welt.vir_entschlossenheit += 50
        welt.vir_evolution = "Meister_Stratege"
        welt.vintari_pfad = "Allianz_Schüler"
        web_print("\n[VIR-PFAD - DIE REBELLESCHE FREUNDSCHAFT]")
        web_print("Vir nickt: 'Ich helfe euch im Geheimen über verdeckte Relais!'")
    else:
        welt.vintari_pfad = "Drakh_Marionette"
        welt.centauri_zerstoerung += 30
        welt.bedrohung_bester += 25
        web_print("\n[LONDO-KONFRONTATION - DER EISKALTE MONARCH]")
        web_print("Londo Mollari blickt dich mit rücksichtsloser Härte an. Ein Keeper zwingt ihn dazu.")
    welt.aktueller_screen = "NAVIGATIONS_KONSOLE"
    navigations_konsole(welt, "")

def sub_narn_entscheidung(welt, wahl):
    welt.besuchte_orte["Narn"] = True
    if wahl == "3" and welt.lore_wissen >= 80:
        welt.allianz_einfluss["Narn"] = 100; welt.allianz_einfluss["Minbari"] += 25; welt.bedrohung_bester -= 25
        web_print("\n[SPIRITUELLER TRIUMPH] Der Rat ist ergriffen. Die G'Quan-Kreuzer fliegen für das Licht!")
    elif wahl == "1" and welt.credits >= 30000:
        welt.credits -= 30000; welt.allianz_einfluss["Narn"] = 50; welt.allianz_einfluss["Minbari"] -= 30
        web_print("\nUntergrund-Warlords angeheuert.")
    else:
        welt.allianz_einfluss["Narn"] = 20; welt.drakh_seuche -= 15
        web_print("\nNur logistische Güter angefordert.")
    welt.aktueller_screen = "NAVIGATIONS_KONSOLE"
    navigations_konsole(welt, "")
def interstellarer_kriegsrat_babcom(welt, strategie):
    if strategie == "":
        web_print("\n=== INTERSTELLARER KRIEGERISCHER RAT: BABCOM-KONFERENZ ===")
        web_print("Lochley und Gideon fordern eine Invasions-Strategie für das Erd-Kapitel:")
        web_print("1 = [DIE PHALANX-STRATEGIE] Voller Flottenaufmarsch als physischer Schild.")
        web_print("2 = [DIE CHAMELEON-STRATEGIE] Maximal verdeckter Einflug der Liburnia.")
        web_print("3 = [DIE BRUTALE ZERSTÖRUNG] Söldner und Narn-Kreuzer starten einen Keil-Angriff.")
        return

    if strategie == "3" and welt.allianz_einfluss["Narn"] == -100:
        web_print("\n[FEHLSCHLAG] Du hast keine schweren Narn-Kreuzer für diese Strategie!")
        welt.aktueller_screen = "NAVIGATIONS_KONSOLE"
        navigations_konsole(welt, "")
        return

    if strategie == "1": welt.bedrohung_bester -= 15
    elif strategie == "2": welt.bedrohung_bester += 10
    elif strategie == "3": welt.bedrohung_bester -= 30

    web_print("\nGalen tritt aus den Schatten: 'Der Stratosphären-Sturzflug wird euer Schicksal besiegeln.'")
    welt.control_entschluesselung = 999
    welt.lore_wissen += 15
    welt.aktueller_screen = "NAVIGATIONS_KONSOLE"
    navigations_konsole(welt, "")

def ort_babylon_5(welt): welt.besuchte_orte["B5"] = True; web_print("\n[B5] Vorräte gesichert."); navigations_konsole(welt, "")
def ort_minbar(welt): welt.besuchte_orte["Minbar"] = True; web_print("\n[MINBAR] Kriegsrat beendet."); navigations_konsole(welt, "")
def ort_mars_erweitert(welt): welt.besuchte_orte["Mars"] = True; web_print("\n[MARS] Daten extrahiert."); navigations_konsole(welt, "")
def ort_labor_planet_syrius(welt): welt.besuchte_orte["Syrius"] = True; web_print("\n[SYRIUS 4] Datenbank gehackt."); navigations_konsole(welt, "")

def ort_erde_infiltration(welt, wahl):
    if wahl == "":
        web_print("\n======================================================")
        web_print("=== AKT V: DAS ERDKAPITEL - DER INVASIONS-EINFLUG ===")
        web_print("======================================================")
        web_print("Die LIBURNIA fällt aus dem Hyperraum. Ivanova und Franklin erwarten dich im Bunker.")
        web_print("\n[DER STRATOSPHÄREN-STURZ] Wähle den Sturzwinkel:")
        web_print("1 = Den Sturzwinkel exakt berechnen (Erfordert Analyse-Fokus)\n2 = Glasdach mit PPG durchschlagen")
        return

    if wahl == "1" and welt.analyse_fokus >= 80: welt.bedrohung_bester -= 20
    else: welt.bedrohung_bester += 15

    web_print("\nIhr steht im innersten Kern des Psi-Corps über Besters Kontrollraum!")
    welt.aktueller_screen = "FINALE_SHOWDOWN"
    showdown_triologie(welt, "")
def showdown_triologie(welt, aktion):
    if aktion == "":
        web_print("\n======================================================")
        web_print("=== FINALE: DER TAG DER ABRECHNUNG ===================")
        web_print("======================================================")
        if welt.allianz_einfluss["Narn"] == -100:
            web_print("\n[TAKTIK-DESASTER] Die Ranger tragen die doppelte Last ohne die Narn!")
            welt.bedrohung_bester += 35
        elif welt.allianz_einfluss["Narn"] == 100:
            web_print("\n[FLOTTEN-SYNERGIE] Ein schweres Narn-Geschwader flankiert das Schlachtfeld!")
            welt.bedrohung_bester -= 30

        web_print("\nAlfred Bester zieht sein PPG und stürzt sich im Nahkampf auf DICH!")
        web_print("1 = Trugbild erzeugen (Chamäleon-Netz)\n2 = Körperliches Ringen am Boden")
        return

    if aktion == "1" and welt.analyse_fokus >= 80:
        web_print("\n[ERFOLG] Du überlistest Bester."); welt.bedrohung_bester -= 40
    else:
        web_print("\n[HARTER NAHKAMPF] Bester leistet erbitterten Widerstand."); welt.bedrohung_bester -= 15

    if welt.beziehungen["Ivanova_Erdflotte"] >= 60 and welt.bedrohung_bester <= 20:
        web_print("\n[TRIUMPH] Talias Sperre bricht! Bester flieht.")
        welt.talia_trauma_geloest = True
    else:
        web_print("\nTalia drückt eiskalt ab. Ein bitterer Verrat.")

    web_print("\nDas Terminal liegt offen vor dir. Wähle das Schicksal des Regierungsnetzwerks:")
    web_print("1 = Das Netzwerk komplett löschen (Weg des Lichts)\n2 = Die Administrator-Rechte auf die LIBURNIA überschreiben")
    welt.aktueller_screen = "EPILOG_AUSWERTUNG"

def epilog_generieren(welt, wahl_macht):
    if wahl_macht == "2":
        web_print("\n=== ENDE 5: DER AUFSTIEG DES NEUEN TYRANNEN ===")
    else:
        web_print("\n=== ENDE 1: DER PFAD DES LICHTS ===")

    web_print("\nProjekt gesichert. Danke fürs Mitspielen, Commander!")
    web_print("\n[Tippe 'neu' ein, um ein neues Abenteuer zu starten]")
    welt.aktueller_screen = "GAME_OVER"

# --- ZUSTANDSMASCHINEN-ROUTER ---
def spiel_verarbeiten(welt, wahl):
    scr = welt.aktueller_screen

    if scr == "START":
        charakter_erstellung(welt, "")
        return

        elif scr == "CHARAKTER_WAHL":
            if wahl == "1" or wahl == "2":
                if wahl == "1":
                    welt.charakter_origin = "Geheimdienst"
                    welt.analyse_fokus += 10
                    welt.lore_wissen += 5
                    web_print("\n-> Du bist ein Phantom des ehemaligen Earthforce-Geheimdienstes.")
                else:
                    welt.charakter_origin = "Unterwelt"
                    welt.credits += 25000
                    web_print("\n-> Du bist ein Geist des Braunen Sektors, ein Meister unregistrierter Fracht.")

                # Die komplette Vorgeschichte wird in einem Rutsch generiert:
                spiel_starten(welt)
                uebergabe_sicherheitszentrale(welt)
                excalibur_hentou_forschung(welt)

                # WICHTIG: Ruft den Dialog auf, der am Ende die Motivations-Optionen anzeigt!
                ratsbuero_verhoer(welt)

                welt.aktueller_screen = "MOTIVATION_ABFRAGE"
                return

            return
        else:
            # Falls der Spieler etwas Falsches eingibt, zeigen wir das Menü einfach noch mal
            web_print(f"\nUnbekannter Vektor: '{wahl}'. Bitte wähle deine Herkunft:")
            charakter_erstellung(welt, "")
            return

    elif scr == "MOTIVATION_ABFRAGE":
        charakter_motivation_abfrage(welt, wahl)
        return

    elif scr == "QUEST_RETTUNG":
        quest_rettung_konstrukteurin(welt, wahl)
        return

    elif scr == "NAVIGATIONS_KONSOLE":
        navigations_konsole(welt, wahl)
        return

    elif scr == "SUB_CENTAURI": sub_centauri_entscheidung(welt, wahl)
    elif scr == "SUB_NARN": sub_narn_entscheidung(welt, wahl)
    elif scr == "SUB_EPSILON": sub_epsilon_entscheidung(welt, wahl)
    elif scr == "SUB_KRIEGERAT": interstellarer_kriegsrat_babcom(welt, wahl)
    elif scr == "ERDE_INFILTRATION": ort_erde_infiltration(welt, wahl)
    elif scr == "FINALE_SHOWDOWN": showdown_triologie(welt, wahl)
    elif scr == "EPILOG_AUSWERTUNG": epilog_generieren(welt, wahl)
    elif scr == "GAME_OVER":
        if wahl.lower() == "neu":
            welt.__init__()
            charakter_erstellung(welt, "")

def main():
    # 1. Parameter aus der Linux-Shell auslesen (Indizes 1 und 2 explizit gesetzt)
    spieler_eingabe = sys.argv[1].strip() if len(sys.argv) > 1 else ""
    alter_zustand_b64 = sys.argv[2].strip() if len(sys.argv) > 2 else ""

    welt = BabylonZustand()

    # DIAGNOSE-LOG: Zeigt dir im Terminal, was Python vom Browser empfängt
    # web_print(f"[Debug-Terminal]: Empfangen -> Befehl: '{spieler_eingabe}', Zustand-Länge: {len(alter_zustand_b64)}")

    # 2. Spielstand laden
    if alter_zustand_b64 and spieler_eingabe.lower() != "neu":
        try:
            decoded = base64.b64decode(alter_zustand_b64).decode('utf-8')
            welt.__dict__.update(json.loads(decoded))
        except Exception as e:
            # JETZT NEU: Wenn Linux beim Entschlüsseln patzt, wird der Fehler DIREKT auf den Schirm gedruckt!
            web_print(f"<span style='color: #f7768e;'>[Kritischer Reaktor-Fehler]: Spielstand-Wiederherstellung fehlgeschlagen: {str(e)}</span>")
            spieler_eingabe = "neu"

    # 3. Den aktuellen Spielzug verarbeiten
    if spieler_eingabe.lower() == "neu":
        welt = BabylonZustand()
        welt.aktueller_screen = "START"
        spiel_verarbeiten(welt, "")
    elif not alter_zustand_b64 and welt.aktueller_screen == "START" and (spieler_eingabe == "1" or spieler_eingabe == "2"):
        welt.aktueller_screen = "CHARAKTER_WAHL"
        spiel_verarbeiten(welt, spieler_eingabe)
    elif not alter_zustand_b64:
        welt.aktueller_screen = "START"
        spiel_verarbeiten(welt, "")
    else:
        spiel_verarbeiten(welt, spieler_eingabe)

    # 4. Den neuen Zustand für den nächsten Zug einfrieren
    neuer_zustand_b64 = base64.b64encode(json.dumps(welt.__dict__).encode('utf-8')).decode('utf-8')

    # 5. JSON-Paket an Game-Bridge.php übergeben
    print(json.dumps({"text": "\n".join(web_output), "zustand": neuer_zustand_b64}))

if __name__ == "__main__":
    main()
