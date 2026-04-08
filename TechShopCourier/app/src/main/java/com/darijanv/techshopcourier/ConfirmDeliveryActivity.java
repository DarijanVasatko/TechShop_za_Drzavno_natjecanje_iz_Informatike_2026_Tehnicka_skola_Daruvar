package com.darijanv.techshopcourier;

import android.graphics.Bitmap;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;

import java.io.File;
import java.io.FileOutputStream;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class ConfirmDeliveryActivity extends AppCompatActivity {

    public static final String EXTRA_ORDER_ID = "order_id";
    public static final String EXTRA_ORDER_TITLE = "order_title";

    private ApiService api;
    private int orderId;

    private SignatureView signatureView;
    private Button btnClear, btnConfirm;
    private ProgressBar progress;
    private TextView tvOrderInfo;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_confirm_delivery);

        ToolbarUtil.setup(this, "Courier", true);

        orderId = getIntent().getIntExtra(EXTRA_ORDER_ID, -1);
        if (orderId == -1) {
            finish();
            return;
        }

        api = ApiClient.getClient(this).create(ApiService.class);

        tvOrderInfo = findViewById(R.id.tvOrderInfo);
        signatureView = findViewById(R.id.signatureView);
        btnClear = findViewById(R.id.btnClear);
        btnConfirm = findViewById(R.id.btnConfirm);
        progress = findViewById(R.id.progress);

        String title = getIntent().getStringExtra(EXTRA_ORDER_TITLE);
        tvOrderInfo.setText(title != null ? title : ("Narudžba #" + orderId));

        btnClear.setOnClickListener(v -> signatureView.clear());

        btnConfirm.setOnClickListener(v -> {
            if (!signatureView.hasSigned()) {
                Toast.makeText(this, "Molimo podpis.", Toast.LENGTH_SHORT).show();
                return;
            }


            saveSignatureToCache();

            setLoading(true);
            api.markDelivered(orderId).enqueue(new Callback<SimpleResponse>() {
                @Override
                public void onResponse(@NonNull Call<SimpleResponse> call,
                                       @NonNull Response<SimpleResponse> response) {
                    setLoading(false);

                    if (!response.isSuccessful() || response.body() == null) {
                        Toast.makeText(ConfirmDeliveryActivity.this,
                                "API error: " + response.code(), Toast.LENGTH_LONG).show();
                        return;
                    }

                    Toast.makeText(ConfirmDeliveryActivity.this,
                            response.body().message, Toast.LENGTH_SHORT).show();

                    setResult(RESULT_OK);
                    finish();
                }

                @Override
                public void onFailure(@NonNull Call<SimpleResponse> call, @NonNull Throwable t) {
                    setLoading(false);
                    Toast.makeText(ConfirmDeliveryActivity.this,
                            "Zahtjev neuspio: " + t.getMessage(), Toast.LENGTH_LONG).show();
                }
            });
        });
    }

    private void setLoading(boolean loading) {
        progress.setVisibility(loading ? View.VISIBLE : View.GONE);
        btnConfirm.setEnabled(!loading);
        btnClear.setEnabled(!loading);
    }

    private File saveSignatureToCache() {
        try {
            Bitmap bmp = signatureView.getSignatureBitmapCopy();
            if (bmp == null) return null;

            File file = new File(getCacheDir(), "signature_order_" + orderId + ".png");
            try (FileOutputStream out = new FileOutputStream(file)) {
                bmp.compress(Bitmap.CompressFormat.PNG, 100, out);
            }
            return file;
        } catch (Exception e) {
            e.printStackTrace();
            return null;
        }
    }
}
