package Java_Src;

import java.io.BufferedReader;
import java.io.File;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.net.ServerSocket;
import java.net.Socket;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.sql.ResultSet;

public class Main {

    private static final String DB_URL = "jdbc:sqlite:/var/www/html/Database/b5_project.sqlite";
    private static final int PORT = 9999; // Der Kommunikationsport für PHP

    // ERSETZE DEN ANFANG DER MAIN-METHODE HIERDURCH:
    public static void main(String[] args) {
        // 1. Treiber laden
        try {
            Class.forName("org.sqlite.JDBC");
        } catch (ClassNotFoundException e) {
            System.out.println("ERROR: SQLite JDBC Treiber fehlt.");
            return;
        }

        // Falls PHP uns ein Argument übergibt, nutzen wir den Cloud-Direktmodus
        if (args.length > 0) {
            try {
                int currentNodeId = Integer.parseInt(args[0].trim());
                String sceneData = loadSceneForWeb(currentNodeId);
                System.out.print(sceneData); // Ausgabe an PHP übergeben
            } catch (NumberFormatException e) {
                System.out.print("ERROR: Ungültige Node-ID übergeben.");
            }
            return; // Prozess beenden, PHP hat die Daten erhalten!
        }
        
        // 3. Server Netzwerk-Loop
        try (ServerSocket serverSocket = new ServerSocket(PORT, 50, java.net.InetAddress.getByName("0.0.0.0"))) {
            while (true) {
                // Wartet, bis die Game-Bridge.php eine Verbindung aufbaut
                try (Socket clientSocket = serverSocket.accept();
                     BufferedReader in = new BufferedReader(new InputStreamReader(clientSocket.getInputStream()));
                     PrintWriter out = new PrintWriter(clientSocket.getOutputStream(), true)) {

                    // PHP sendet die gewünschte Node-ID (z.B. "100")
                    String inputLine = in.readLine();
                    if (inputLine != null) {
                        try {
                            int currentNodeId = Integer.parseInt(inputLine.trim());
                            // Szene aus DB laden und als JSON oder formatierten Text an PHP senden
                            String sceneData = loadSceneForWeb(currentNodeId);
                            out.println(sceneData);
                        } catch (NumberFormatException e) {
                            out.println("ERROR: Ungültige Node-ID gesendet.");
                        }
                    }
                } catch (Exception e) {
                    System.err.println("[⚠️ VERBINDUNGSFEHLER]: " + e.getMessage());
                }
            }
        } catch (Exception e) {
            System.err.println("[🚨 CRITICAL]: Server-Socket konnte nicht geöffnet werden: " + e.getMessage());
        }
    }

    private static String loadSceneForWeb(int nodeId) {
        String nodeQuery = "SELECT title, description, active_sector, is_ending FROM story_nodes WHERE node_id = ?";
        String choicesQuery = "SELECT choice_id, choice_text, next_node_id FROM story_choices WHERE from_node_id = ?";
        StringBuilder response = new StringBuilder();

        try (Connection conn = DriverManager.getConnection(DB_URL)) {
            // 1. Szene abfragen
            try (PreparedStatement pstmt = conn.prepareStatement(nodeQuery)) {
                pstmt.setInt(1, nodeId);
                try (ResultSet rs = pstmt.executeQuery()) {
                    if (rs.next()) {
                        response.append("SECTOR: ").append(rs.getString("active_sector").toUpperCase()).append("\n");
                        response.append("TITLE: ").append(rs.getString("title")).append("\n");
                        response.append("TEXT: ").append(rs.getString("description").replace("\\n", "\n")).append("\n");
                        response.append("ENDING: ").append(rs.getBoolean("is_ending")).append("\n---CHOICES---\n");
                    } else {
                        return "ERROR: Node " + nodeId + " nicht gefunden.";
                    }
                }
            }

            // 2. Antwortoptionen abfragen
            try (PreparedStatement pstmt = conn.prepareStatement(choicesQuery)) {
                pstmt.setInt(1, nodeId);
                try (ResultSet rs = pstmt.executeQuery()) {
                    while (rs.next()) {
                        response.append(rs.getInt("next_node_id"))
                                .append("|")
                                .append(rs.getString("choice_text"))
                                .append("\n");
                    }
                }
            }
        } catch (Exception e) {
            return "ERROR: Datenbankfehler: " + e.getMessage();
        }

        return response.toString();
    }
}
