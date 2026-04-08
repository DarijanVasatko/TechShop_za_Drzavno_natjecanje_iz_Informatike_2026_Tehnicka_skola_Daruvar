package com.darijanv.techshopcourier;

import com.google.gson.annotations.SerializedName;

public class OrderItem {
    @SerializedName("product_id")
    public int productId;

    @SerializedName("name")
    public String name;

    @SerializedName("qty")
    public int qty;

    @SerializedName("price")
    public double price;
}