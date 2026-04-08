package com.darijanv.techshopcourier;

import com.google.gson.annotations.SerializedName;
import java.util.List; // Don't forget this import!

public class Order {
    @SerializedName("Narudzba_ID")
    public int id;

    @SerializedName("Datum_narudzbe")
    public String date;

    @SerializedName("Ukupni_iznos")
    public double total;

    @SerializedName("Adresa_dostave")
    public String address;

    @SerializedName("Status")
    public String status;

    @SerializedName("detalji")
    public List<OrderItem> items;
}