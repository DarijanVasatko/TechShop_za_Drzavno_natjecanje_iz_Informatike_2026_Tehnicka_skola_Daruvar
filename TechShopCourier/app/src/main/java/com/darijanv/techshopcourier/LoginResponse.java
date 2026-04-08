package com.darijanv.techshopcourier;

import com.google.gson.annotations.SerializedName;

public class LoginResponse {
    @SerializedName("message")
    public String message;

    @SerializedName("token")
    public String token;

    @SerializedName("user")
    public User user;

    public static class User {
        public int id;
        public String name;
        public String email;

        @SerializedName("is_admin")
        public int isAdmin;
    }
}