package com.darijanv.techshopcourier;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.core.content.ContextCompat;
import androidx.recyclerview.widget.RecyclerView;

import com.google.android.material.chip.Chip;

import java.util.ArrayList;
import java.util.List;

public class OrdersAdapter extends RecyclerView.Adapter<OrdersAdapter.VH> {

    public interface OnOrderClickListener {
        void onClick(Order order);
    }

    private final List<Order> items = new ArrayList<>();
    private final OnOrderClickListener listener;

    public OrdersAdapter(OnOrderClickListener listener) {
        this.listener = listener;
    }

    public void setItems(List<Order> newItems) {
        items.clear();
        if (newItems != null) items.addAll(newItems);
        notifyDataSetChanged();
    }

    @NonNull
    @Override
    public VH onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View v = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_order, parent, false);
        return new VH(v);
    }

    @Override
    public void onBindViewHolder(@NonNull VH h, int position) {
        Order o = items.get(position);

        h.tvTitle.setText("Narudžba #" + o.id);
        h.tvSubtitle.setText(o.total + " € • " + o.date);
        h.tvAddress.setText(o.address != null ? o.address : "Nema adrese");

        String status = (o.status == null) ? "Nepoznato" : o.status;
        h.chipStatus.setText(status);

        String s = status.toLowerCase();

        if (s.contains("neusp") || s.contains("nije")) {

            h.chipStatus.setChipBackgroundColorResource(R.color.status_failed_bg);
            h.chipStatus.setTextColor(
                    ContextCompat.getColor(h.itemView.getContext(), R.color.status_failed_text)
            );
        } else if (s.contains("obradi") || s.contains("na dostavi") || s.contains("pla")) {

            h.chipStatus.setChipBackgroundColorResource(R.color.status_active_bg);
            h.chipStatus.setTextColor(
                    ContextCompat.getColor(h.itemView.getContext(), R.color.status_active_text)
            );
        } else {

            h.chipStatus.setChipBackgroundColorResource(R.color.status_default_bg);
            h.chipStatus.setTextColor(
                    ContextCompat.getColor(h.itemView.getContext(), R.color.status_default_text)
            );
        }



        h.itemView.setOnClickListener(v -> {
            if (listener != null) listener.onClick(o);
        });
    }

    @Override
    public int getItemCount() {
        return items.size();
    }

    static class VH extends RecyclerView.ViewHolder {
        TextView tvTitle, tvSubtitle, tvAddress;
        Chip chipStatus;

        VH(@NonNull View itemView) {
            super(itemView);
            tvTitle = itemView.findViewById(R.id.tvTitle);
            tvSubtitle = itemView.findViewById(R.id.tvSubtitle);
            tvAddress = itemView.findViewById(R.id.tvAddress);
            chipStatus = itemView.findViewById(R.id.chipStatus);
        }
    }
}
