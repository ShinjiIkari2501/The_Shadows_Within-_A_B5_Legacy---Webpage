/**
 * GameItem.java
 * Core Data Class representing an item or equipment asset in 'The Shadows Within - A B5 Legacy'.
 * Maps directly to the 'inventory_items' JSON array structure from the REST API.
 */
public class GameItem {
    private int itemId;
    private String itemName;
    private String itemType;

    // Default constructor
    public GameItem() {}

    // Overloaded constructor
    public GameItem(int itemId, String itemName, String itemType) {
        this.itemId = itemId;
        this.itemName = itemName;
        this.itemType = itemType;
    }

    // Getters and Setters
    public int getItemId() { return itemId; }
    public void setItemId(int itemId) { this.itemId = itemId; }

    public String getItemName() { return itemName; }
    public void setItemName(String itemName) { this.itemName = itemName; }

    public String getItemType() { return itemType; }
    public void setItemType(String itemType) { this.itemType = itemType; }

    public void displayItemProperties() {
        System.out.println(" >> [ITEM ASSIGNED] ID: " + itemId + " | " + itemName + " (" + itemType + ")");
    }
}
