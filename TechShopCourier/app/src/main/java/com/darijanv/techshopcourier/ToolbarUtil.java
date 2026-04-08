package com.darijanv.techshopcourier;

import androidx.appcompat.app.AppCompatActivity;

import com.google.android.material.appbar.MaterialToolbar;

public class ToolbarUtil {

    public static void setup(AppCompatActivity activity, String title, boolean showBack) {
        MaterialToolbar toolbar = activity.findViewById(R.id.topAppBar);
        if (toolbar == null) return;

        toolbar.setTitle(title);

        if (showBack) {
            toolbar.setNavigationOnClickListener(v -> activity.finish());
        } else {
            toolbar.setNavigationIcon(null);
        }
    }
}
