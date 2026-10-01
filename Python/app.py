import sys
import os
import subprocess
from flask import Flask, render_template_string
from flask_sockets import Sockets
from gevent import pywsgi
from geventwebsocket.handler import WebSocketHandler

app = Flask(__name__)
sockets = Sockets(app)

# HTML-Oberfläche für das iFrame (Farblich perfekt an dein B5-Allianz-Design angepasst)
HTML_TEMPLATE = """
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            background: #010203;
            color: #60acf3;
            font-family: monospace;
            padding: 15px;
            margin: 0;
            font-size: 1rem;
            line-height: 1.5;
        }
        #terminal {
            height: 400px;
            overflow-y: auto;
            padding-bottom: 20px;
            white-space: pre-wrap;
        }
        #input-area {
            display: flex;
            gap: 10px;
            background: rgba(13, 20, 59, 0.6);
            padding: 10px;
            border: 1px solid rgba(96, 172, 243, 0.4);
            border-radius: 4px;
        }
        #prompt { color: #60acf3; font-weight: bold; }
        #cmd {
            flex-grow: 1;
            background: transparent;
            border: none;
            color: #ffffff;
            font-family: inherit;
            font-size: 1rem;
            outline: none;
        }
    </style>
</head>
<body>
    <div id="terminal">[SUBLINK_CORE_TERMINAL_v2.0] • ACTIVE_FEED\n</div>
    <div id="input-area">
        <span id="prompt">cmd_vector&gt;</span>
        <input type="text" id="cmd" autofocus placeholder="Type a command and press Enter...">
    </div>

    <script>
        const term = document.getElementById('terminal');
        const cmdInput = document.getElementById('cmd');

        // Baut eine dauerhafte, asynchrone Websocket-Verbindung auf
        const wsProtocol = window.location.protocol === 'https:' ? 'wss://' : 'ws://';
        const ws = new WebSocket(wsProtocol + window.location.host + '/stream');

        ws.onmessage = function(event) {
            term.innerText += event.data;
            term.scrollTop = term.scrollHeight; // Automatischer Text-Scroll
        };

        cmdInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const val = cmdInput.value;
                term.innerText += "\\n> " + val + "\\n";
                ws.send(val); // Sendet die Eingabe direkt an das Python-input()
                cmdInput.value = '';
                term.scrollTop = term.scrollHeight;
            }
        });
    </script>
</body>
</html>
"""

@app.route('/')
def index():
    return render_template_string(HTML_TEMPLATE)

@sockets.route('/stream')
def stream_socket(ws):
    # STARTET DEIN ORIGINAL-SKRIPT (Achte auf den exakten Namen deiner Datei!)
    process = subprocess.Popen(
        ['python3', 'The Shadows Within - A B5 Legacy.py'],
        stdin=subprocess.PIPE,
        stdout=subprocess.PIPE,
        stderr=subprocess.STDOUT,
        text=True,
        bufsize=1
    )

    # Liest den Text aus deinen print()-Befehlen und schickt ihn ans iFrame
    def read_output():
        while True:
            char = process.stdout.read(1)
            if not char: break
            ws.send(char)

    import threading
    threading.Thread(target=read_output, daemon=True).start()

    # Wartet auf die Befehle aus dem iFrame und füttert sie in dein input()
    while not ws.closed:
        message = ws.receive()
        if message is not None:
            process.stdin.write(message + '\n')
            process.stdin.flush()

if __name__ == '__main__':
    server = pywsgi.WSGIServer(('0.0.0.0', int(os.environ.get('PORT', 5000))), app, handler_class=WebSocketHandler)
    server.serve_forever()
