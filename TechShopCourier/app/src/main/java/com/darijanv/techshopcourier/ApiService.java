package com.darijanv.techshopcourier;

import retrofit2.Call;
import retrofit2.http.Body;
import retrofit2.http.GET;
import retrofit2.http.POST;
import retrofit2.http.Path;

public interface ApiService {

    @GET("api/driver/orders")
    Call<OrdersResponse> getOrders();

    @GET("api/driver/orders/{id}")
    Call<OrderDetailsResponse> getOrderDetails(@Path("id") int id);

    @POST("api/driver/orders/{id}/delivered")
    Call<SimpleResponse> markDelivered(@Path("id") int id);

    @POST("api/driver/orders/{id}/not-delivered")
    Call<SimpleResponse> markNotDelivered(@Path("id") int id);

    @POST("api/login")
    Call<LoginResponse> login(@Body LoginRequest request);

}




