package com.darijanv.techshopcourier;

import java.util.List;

public class OrderDetails {
    public int id;
    public String status;
    public double total;
    public String created_at;
    public String address;
    public List<OrderItem> items;
}
