import java.net.URI;
import java.net.http.HttpClient;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.net.http.HttpConnectTimeoutException;
import java.time.Duration;

/**
 * GameEngine.java
 * Core network infrastructure and runtime controller for 'The Shadows Within - A B5 Legacy'.
 * Manages secure HTTP communication channels to fetch synchronized JSON states from the PHP REST API.
 */
public class GameEngine {

    // Central REST endpoint referencing your local server environment (Fixed URL spelling)
    private static final String API_URL = "http://localhost/The_Shadows_Within-_A_B5_Legacy---Webpage-main/Game-Bridge.php";
    private final HttpClient httpClient;

    /**
     * Initializes the standard Java HTTP Client configuration with secure timeout restrictions.
     */
    public GameEngine() {
        this.httpClient = HttpClient.newBuilder()
                .connectTimeout(Duration.ofSeconds(10))
                .build();
    }

    /**
     * Dispatches a secure telemetry sync request to fetch active scenario and save state metrics.
     * Maps perfectly to the data workflow requirements of your classroom specifications.
     *
     * @param command The explicit tactical action entered by the player (Choice-ID or Action).
     * @param currentContextState The current serialized game parameters buffer.
     * @return The raw JSON response string processed from the web server core.
     */
    public String syncGameTelemetry(String command, String currentContextState) {
        try {
            // Determine exact action mapping for the PHP Backend
            String action = command;
            String choiceId = "";

            // If the player typed a numeric Choice-ID, route it as EXECUTE_CHOICE
            if (command.matches("\\d+")) {
                action = "EXECUTE_CHOICE";
                choiceId = command;
            }

            // Build a clean, valid JSON Payload mirroring what Game-Bridge.php expects
            // Standardized here for Test-User 1 (Shinji2501) and Save-Slot 1
            String requestBody = "{"
                    + "\"action\":\"" + action + "\","
                    + "\"user_id\":1,"
                    + "\"slot_number\":1"
                    + (choiceId.isEmpty() ? "" : ",\"choice_id\":" + choiceId)
                    + "}";

            // Build the outbound transmission request configuration (Swapped to JSON Content-Type)
            HttpRequest request = HttpRequest.newBuilder()
                    .uri(URI.create(API_URL))
                    .header("Content-Type", "application/json; charset=UTF-8")
                    .POST(HttpRequest.BodyPublishers.ofString(requestBody))
                    .build();

            // Fire transmission request and catch the processing stream directly as a string handle
            HttpResponse<String> response = httpClient.send(request, HttpResponse.BodyHandlers.ofString());

            // Validate successful connection sequence status (200 = Success HTTP Connection)
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
