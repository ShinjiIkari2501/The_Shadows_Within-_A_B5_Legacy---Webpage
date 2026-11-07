/* ==========================================================================
   b5_five.js - die ikonische "5" aus dem Babylon-5-Schriftzug
   In der Schrift "Babylon5 Station Bold" ergibt das Zeichen (c) die 5 mit dem Ring aus dem Logo.
   Dieses Skript tauscht automatisch aus:
     1. jede 5 in allen Ueberschriften (h1-h4, legend, Spiel-Titel, Spielort, Timeline-Badges)
     2. die 5 in "Babylon 5" und "B5" im normalen Text (auch in Texten, die das Spiel nachlaedt)
   Andere Zahlen im Text (z.B. 5.000 Credits) bleiben unveraendert.
   Fuer Screenreader bleibt jede getauschte 5 als "5" vorlesbar. Die Groesse steht in der CSS (.b5five).
   ========================================================================== */
(function () {
    'use strict';

    var ICON = '©';
    var HEADINGS = 'h1,h2,h3,h4,legend,.main-title,.timeline-badge,#terminal-title,#terminal-location';
    var SKIP = 'script,style,textarea,input,select,option,code,pre,title,.b5five';
    var BODY_RE = /(?:Babylon[  ]+|\bB)5(?![0-9A-Za-z_])/gi;   /* "Babylon 5", "B5" - die 5 ist das letzte Zeichen des Treffers */
    var HEAD_RE = /5/g;                                        /* in Ueberschriften: jede 5 */

    function makeIcon(inHeading) {
        var s = document.createElement('span');
        s.className = inHeading ? 'b5five head' : 'b5five';
        s.setAttribute('role', 'img');
        s.setAttribute('aria-label', '5');
        s.textContent = ICON;
        return s;
    }

    function fixTextNode(node) {
        var parent = node.parentNode;
        if (!parent || parent.nodeType !== 1 || node.nodeValue.indexOf('5') === -1) { return; }
        if (parent.closest(SKIP)) { return; }
        var inHeading = !!parent.closest(HEADINGS);
        var re = inHeading ? HEAD_RE : BODY_RE;
        var text = node.nodeValue, last = 0, m, frag = null;
        re.lastIndex = 0;
        while ((m = re.exec(text)) !== null) {
            var pos = m.index + m[0].length - 1;               /* Position der 5 */
            if (!frag) { frag = document.createDocumentFragment(); }
            frag.appendChild(document.createTextNode(text.slice(last, pos)));
            frag.appendChild(makeIcon(inHeading));
            last = pos + 1;
        }
        if (frag) {
            frag.appendChild(document.createTextNode(text.slice(last)));
            parent.replaceChild(frag, node);
        }
    }

    function fixTree(root) {
        if (!root) { return; }
        if (root.nodeType === 3) { fixTextNode(root); return; }
        if (root.nodeType !== 1 || root.closest(SKIP)) { return; }
        var walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, null);
        var list = [], n;
        while ((n = walker.nextNode())) { if (n.nodeValue.indexOf('5') !== -1) { list.push(n); } }
        list.forEach(fixTextNode);
    }

    var queue = [], scheduled = false, observer = null;
    function flush() {
        scheduled = false;
        var items = queue; queue = [];
        if (observer) { observer.disconnect(); }                /* unsere eigenen Aenderungen nicht erneut verarbeiten */
        items.forEach(fixTree);
        if (observer) { observer.observe(document.body, { childList: true, characterData: true, subtree: true }); }
    }
    function schedule(node) {
        queue.push(node);
        if (!scheduled) { scheduled = true; (window.requestAnimationFrame || setTimeout)(flush); }
    }

    function start() {
        fixTree(document.body);
        if (window.MutationObserver) {
            observer = new MutationObserver(function (records) {
                records.forEach(function (r) {
                    if (r.type === 'characterData') { schedule(r.target); }
                    else { r.addedNodes.forEach(schedule); }
                });
            });
            observer.observe(document.body, { childList: true, characterData: true, subtree: true });
        }
    }

    if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', start); } else { start(); }
}());
