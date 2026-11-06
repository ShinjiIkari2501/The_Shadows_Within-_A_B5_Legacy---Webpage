package Java_Src;

import java.net.URI;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.net.http.HttpConnectTimeoutException;
import java.time.Duration;

/**
 * GameEngine.java - KORRIGIERTE VERSION
 * Core network infrastructure and runtime controller for 'The Shadows Within - A B5 Legacy'.
 */
public class GameEngine {

    // KORREKTUR: Bereinigter, relativer Pfad für deine lokale Server-Infrastruktur
    private static final String API_URL = "http://localhost/Game-Bridge.php";
    private final HttpClient httpClient;

    public GameEngine() {
        this.httpClient = HttpClient.newBuilder()
                .connectTimeout(Duration.ofSeconds(10))
                .build();
    }

    public String syncGameTelemetry(String command, String currentContextState) {
        try {
            String action = command;
            String choiceId = "";

            if (command.matches("\\d+")) {
                action = "EXECUTE_CHOICE";
                choiceId = command;
            }

            String requestBody = "{"
                    + "\"action\":\"" + action + "\","
                    + "\"user_id\":1,"
                    + "\"slot_number\":1"
                    + (choiceId.isEmpty() ? "" : ",\"choice_id\":" + choiceId)
                    + "}";

            HttpRequest request = HttpRequest.newBuilder()
                    .uri(URI.create(API_URL))
                    .header("Content-Type", "application/json; charset=UTF-8")
                    .POST(HttpRequest.BodyPublishers.ofString(requestBody))
                    .build();

            HttpResponse<String> response = httpClient.send(request, HttpResponse.BodyHandlers.ofString());

            if (response.statusCode() == 200) {
                return response.body();
            } else {
                System.out.println(" >> [🚨 TERMINAL COMMUNICATIONS RECOIL] Server Status Code: " + response.statusCode());
                return null;
            }

        } catch (HttpConnectTimeoutException e) {
            System.out.println(" >> [🚨 TRANS-SPACE TIMEOUT]: The link connection to the local PHP reactor core timed out.");
            return null;
        } catch (Exception e) {
            System.out.println(" >> [🚨 CRITICAL TELEMETRY ERROR]: Core synchronization sequence disrupted: " + e.getMessage());
            return null;
        }
    }
}
