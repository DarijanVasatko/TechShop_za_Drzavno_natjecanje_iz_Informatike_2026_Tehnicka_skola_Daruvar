package com.darijanv.techshopcourier;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.content.ContextCompat;

import com.google.android.material.chip.Chip;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class OrderDetailsActivity extends AppCompatActivity {

    public static final String EXTRA_ORDER_ID = "order_id";

    private ApiService api;
    private int orderId;

    private TextView tvHeader, tvMeta, tvAddress, tvItems;
    private Chip chipStatus;
    private Button btnDelivered, btnNotDelivered;
    private ProgressBar progress;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_order_details);

        ToolbarUtil.setup(this, "Detalji narudžbe", true);

        orderId = getIntent().getIntExtra(EXTRA_ORDER_ID, -1);
        if (orderId == -1) {
            finish();
            return;
        }

        api = ApiClient.getClient(this).create(ApiService.class);

        tvHeader = findViewById(R.id.tvHeader);
        tvMeta = findViewById(R.id.tvMeta);
        tvAddress = findViewById(R.id.tvAddress);
        tvItems = findViewById(R.id.tvItems);
        chipStatus = findViewById(R.id.chipStatus);
        btnDelivered = findViewById(R.id.btnDelivered);
        btnNotDelivered = findViewById(R.id.btnNotDelivered);
        progress = findViewById(R.id.progress);

        btnDelivered.setOnClickListener(v -> {
            Intent i = new Intent(this, ConfirmDeliveryActivity.class);
            i.putExtra(ConfirmDeliveryActivity.EXTRA_ORDER_ID, orderId);
            i.putExtra(ConfirmDeliveryActivity.EXTRA_ORDER_TITLE, "Narudžba #" + orderId);
            startActivityForResult(i, 2001);
        });

        btnNotDelivered.setOnClickListener(v -> updateStatusNotDelivered(orderId));

        loadDetails(orderId);
    }

    private void setLoading(boolean loading) {
        progress.setVisibility(loading ? View.VISIBLE : View.GONE);
        btnDelivered.setEnabled(!loading);
        btnNotDelivered.setEnabled(!loading);
    }

    private void loadDetails(int id) {
        setLoading(true);
        api.getOrderDetails(id).enqueue(new Callback<OrderDetailsResponse>() {
            @Override
            public void onResponse(@NonNull Call<OrderDetailsResponse> call, @NonNull Response<OrderDetailsResponse> response) {
                setLoading(false);


                if (!response.isSuccessful()) {
                    try {
                        String errorBody = response.errorBody().string();
                        Log.e("PHP_CRASH", "SERVER ERROR: " + errorBody);
                        Toast.makeText(OrderDetailsActivity.this, "Pogreška poslužitelja: Provjeri Logcat", Toast.LENGTH_LONG).show();
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                    return;
                }


                if (response.body() == null || response.body().data == null) {
                    Toast.makeText(OrderDetailsActivity.this, "Nisu pronađeni podatci", Toast.LENGTH_SHORT).show();
                    return;
                }

                Order d = response.body().data;

                tvHeader.setText("Narudžba #" + d.id);
                tvMeta.setText(d.total + " € • " + d.date);
                tvAddress.setText(d.address == null ? "Nema adrese" : d.address);

                String status = (d.status == null) ? "Nepoznato" : d.status;
                chipStatus.setText(status);
                styleStatusChip(status);

                StringBuilder sb = new StringBuilder();
                if (d.items != null) {
                    for (OrderItem it : d.items) {
                        sb.append("• ").append(it.name).append(" x").append(it.qty).append("\n");
                    }
                }
                tvItems.setText(sb.length() == 0 ? "No items." : sb.toString());
            }

            @Override
            public void onFailure(@NonNull Call<OrderDetailsResponse> call, @NonNull Throwable t) {
                setLoading(false);
                Log.e("NETWORK_ERROR", t.getMessage());
                Toast.makeText(OrderDetailsActivity.this, "Neuspjelo: " + t.getMessage(), Toast.LENGTH_LONG).show();
            }
        });
    }

    private void styleStatusChip(String status) {
        String s = status.toLowerCase();
        if (s.contains("neusp") || s.contains("nije")) {
            chipStatus.setChipBackgroundColorResource(R.color.status_failed);
            chipStatus.setTextColor(ContextCompat.getColor(this, android.R.color.white));
        } else {
            chipStatus.setChipBackgroundColorResource(R.color.brand_primary);
            chipStatus.setTextColor(ContextCompat.getColor(this, android.R.color.white));
        }
    }

    private void updateStatusNotDelivered(int id) {
        setLoading(true);
        api.markNotDelivered(id).enqueue(new Callback<SimpleResponse>() {
            @Override
            public void onResponse(@NonNull Call<SimpleResponse> call, @NonNull Response<SimpleResponse> response) {
                setLoading(false);
                if (response.isSuccessful()) {
                    Toast.makeText(OrderDetailsActivity.this, "Narudžba je označena kao neuspjela", Toast.LENGTH_SHORT).show();
                    setResult(RESULT_OK);
                    finish();
                }
            }

            @Override
            public void onFailure(@NonNull Call<SimpleResponse> call, @NonNull Throwable t) {
                setLoading(false);
            }
        });
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (requestCode == 2001 && resultCode == RESULT_OK) {
            setResult(RESULT_OK);
            finish();
        }
    }
}