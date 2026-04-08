package com.darijanv.techshopcourier;

import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

import androidx.activity.result.ActivityResultLauncher;
import androidx.activity.result.contract.ActivityResultContracts;
import android.content.Intent;


public class MainActivity extends AppCompatActivity {

    private ApiService apiService;
    private OrdersAdapter adapter;

    private final Handler handler = new Handler(Looper.getMainLooper());
    private final int REFRESH_MS = 5000;


    private int lastTopOrderId = -1;
    private boolean firstLoad = true;

    private final Runnable refreshRunnable = new Runnable() {
        @Override
        public void run() {
            loadOrders();
            handler.postDelayed(this, REFRESH_MS);
        }
    };

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        ToolbarUtil.setup(this, "Courier", false);

        apiService = ApiClient.getClient(this).create(ApiService.class);

        RecyclerView rv = findViewById(R.id.rvOrders);
        rv.setLayoutManager(new LinearLayoutManager(this));
        adapter = new OrdersAdapter(order -> {
            android.content.Intent i = new android.content.Intent(MainActivity.this, OrderDetailsActivity.class);
            i.putExtra(OrderDetailsActivity.EXTRA_ORDER_ID, order.id);
            detailsLauncher.launch(i);

        });
        rv.setAdapter(adapter);
    }

    @Override
    protected void onStart() {
        super.onStart();

        handler.post(refreshRunnable);
    }

    @Override
    protected void onStop() {
        super.onStop();

        handler.removeCallbacks(refreshRunnable);
    }

    private void loadOrders() {
        apiService.getOrders().enqueue(new Callback<OrdersResponse>() {
            @Override
            public void onResponse(@NonNull Call<OrdersResponse> call, @NonNull Response<OrdersResponse> response) {

                if (!response.isSuccessful()) {
                    Toast.makeText(MainActivity.this, "Server Error: " + response.code(), Toast.LENGTH_SHORT).show();
                    return;
                }

                if (response.body() != null) {
                    List<Order> orders = response.body().data;


                    if (orders == null || orders.isEmpty()) {

                        Toast.makeText(MainActivity.this, "Nema narudžbi na čekanju u bazi podataka.", Toast.LENGTH_SHORT).show();
                    }

                    adapter.setItems(orders);


                    if (orders != null && !orders.isEmpty()) {
                        int topId = orders.get(0).id;
                        if (!firstLoad && topId != lastTopOrderId) {
                            Toast.makeText(MainActivity.this, "Nova narudžba: #" + topId, Toast.LENGTH_SHORT).show();
                        }
                        lastTopOrderId = topId;
                        firstLoad = false;
                    }
                }
            }

            @Override
            public void onFailure(@NonNull Call<OrdersResponse> call, @NonNull Throwable t) {

                Toast.makeText(MainActivity.this, "Mrežna pogreška:" + t.getMessage(), Toast.LENGTH_LONG).show();
            }
        });
    }

    private final ActivityResultLauncher<Intent> detailsLauncher =
            registerForActivityResult(new ActivityResultContracts.StartActivityForResult(), result -> {
                loadOrders();
            });

}
