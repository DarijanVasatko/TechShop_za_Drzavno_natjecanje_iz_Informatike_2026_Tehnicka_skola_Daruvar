package com.darijanv.techshopcourier;

import com.google.gson.annotations.SerializedName;

public class LoginRequest {
    @SerializedName("email")
    private String email;

    @SerializedName("password")
    private String lozinka;

    public LoginRequest(String email, String lozinka) {
        this.email = email;
        this.lozinka = lozinka;
    }
}