package com.darijanv.techshopcourier;

import android.content.Context;
import android.content.SharedPreferences;

import java.security.cert.CertificateException;

import javax.net.ssl.SSLContext;
import javax.net.ssl.TrustManager;
import javax.net.ssl.X509TrustManager;

import okhttp3.OkHttpClient;
import okhttp3.Request;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;

public class ApiClient {
    private static Retrofit retrofit = null;

    public static Retrofit getClient(Context context) {
        if (retrofit == null) {
            try {

                final TrustManager[] trustAllCerts = new TrustManager[]{
                        new X509TrustManager() {
                            @Override
                            public void checkClientTrusted(java.security.cert.X509Certificate[] chain, String authType) throws CertificateException {}

                            @Override
                            public void checkServerTrusted(java.security.cert.X509Certificate[] chain, String authType) throws CertificateException {}

                            @Override
                            public java.security.cert.X509Certificate[] getAcceptedIssuers() {
                                return new java.security.cert.X509Certificate[]{};
                            }
                        }
                };


                final SSLContext sslContext = SSLContext.getInstance("SSL");
                sslContext.init(null, trustAllCerts, new java.security.SecureRandom());


                OkHttpClient okHttpClient = new OkHttpClient.Builder()
                        .sslSocketFactory(sslContext.getSocketFactory(), (X509TrustManager) trustAllCerts[0])
                        .hostnameVerifier((hostname, session) -> true)
                        .addInterceptor(chain -> {

                            SharedPreferences prefs = context.getApplicationContext().getSharedPreferences("prefs", Context.MODE_PRIVATE);
                            String token = prefs.getString("token", "");

                            Request original = chain.request();
                            Request.Builder requestBuilder = original.newBuilder()
                                    .header("Accept", "application/json")
                                    .header("Content-Type", "application/json")
                                    .method(original.method(), original.body());


                            if (!token.isEmpty()) {
                                requestBuilder.header("Authorization", "Bearer " + token);
                            }

                            return chain.proceed(requestBuilder.build());
                        })
                        .build();


                retrofit = new Retrofit.Builder()
                        //za live server
                        .baseUrl("https://204.168.223.158/")
                        //za doma

                        //  .baseUrl("http://192.168.50.219:8000/")
                        .client(okHttpClient)
                        .addConverterFactory(GsonConverterFactory.create())
                        .build();

            } catch (Exception e) {
                throw new RuntimeException(e);
            }
        }
        return retrofit;
    }
}