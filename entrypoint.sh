#!/bin/bash
echo "Starte Babylon 5 Game-Engine Server..."
# Bindet alle .jar-Dateien aus dem lib-Verzeichnis ein
java -cp "/var/www/html:/var/www/html/Java_Src/lib/*" Java_Src.Main > /var/log/java_game.log 2>&1 &


echo "Starte Apache Webserver..."
apache2-foreground
