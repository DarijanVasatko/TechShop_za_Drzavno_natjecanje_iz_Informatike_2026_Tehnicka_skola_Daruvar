package com.darijanv.techshopcourier;

import android.content.Context;
import android.graphics.*;
import android.util.AttributeSet;
import android.view.MotionEvent;
import android.view.View;

public class SignatureView extends View {

    private final Path path = new Path();
    private final Paint paint = new Paint(Paint.ANTI_ALIAS_FLAG);

    private Bitmap backingBitmap;
    private Canvas backingCanvas;

    private float lastX, lastY;
    private boolean hasSigned = false;

    public SignatureView(Context context) {
        super(context);
        init();
    }

    public SignatureView(Context context, AttributeSet attrs) {
        super(context, attrs);
        init();
    }

    public SignatureView(Context context, AttributeSet attrs, int defStyleAttr) {
        super(context, attrs, defStyleAttr);
        init();
    }

    private void init() {
        paint.setColor(Color.BLACK);
        paint.setStyle(Paint.Style.STROKE);
        paint.setStrokeJoin(Paint.Join.ROUND);
        paint.setStrokeCap(Paint.Cap.ROUND);
        paint.setStrokeWidth(8f);
    }

    @Override
    protected void onSizeChanged(int w, int h, int oldw, int oldh) {
        super.onSizeChanged(w, h, oldw, oldh);
        if (w <= 0 || h <= 0) return;

        Bitmap old = backingBitmap;

        backingBitmap = Bitmap.createBitmap(w, h, Bitmap.Config.ARGB_8888);
        backingCanvas = new Canvas(backingBitmap);
        backingCanvas.drawColor(Color.WHITE);

        if (old != null) {
            Rect src = new Rect(0, 0, old.getWidth(), old.getHeight());
            Rect dst = new Rect(0, 0, w, h);
            backingCanvas.drawBitmap(old, src, dst, null);
            old.recycle();
        }
    }

    @Override
    protected void onDraw(Canvas canvas) {
        super.onDraw(canvas);
        if (backingBitmap != null) canvas.drawBitmap(backingBitmap, 0f, 0f, null);
        canvas.drawPath(path, paint);
    }

    @Override
    public boolean onTouchEvent(MotionEvent event) {
        float x = event.getX();
        float y = event.getY();

        switch (event.getActionMasked()) {
            case MotionEvent.ACTION_DOWN:
                if (getParent() != null) getParent().requestDisallowInterceptTouchEvent(true);
                path.moveTo(x, y);
                lastX = x;
                lastY = y;
                invalidate();
                return true;

            case MotionEvent.ACTION_MOVE:
                float midX = (lastX + x) / 2f;
                float midY = (lastY + y) / 2f;
                path.quadTo(lastX, lastY, midX, midY);
                lastX = x;
                lastY = y;
                invalidate();
                return true;

            case MotionEvent.ACTION_UP:
            case MotionEvent.ACTION_CANCEL:
                if (backingCanvas != null) {
                    backingCanvas.drawPath(path, paint);
                    hasSigned = true;
                }
                path.reset();
                invalidate();
                if (getParent() != null) getParent().requestDisallowInterceptTouchEvent(false);
                return true;
        }

        return super.onTouchEvent(event);
    }

    public void clear() {
        if (backingCanvas != null) {
            backingCanvas.drawColor(Color.WHITE, PorterDuff.Mode.SRC);
        }
        path.reset();
        hasSigned = false;
        invalidate();
    }

    public boolean hasSigned() {
        return hasSigned;
    }

    public Bitmap getSignatureBitmapCopy() {
        if (backingBitmap == null) return null;
        return backingBitmap.copy(Bitmap.Config.ARGB_8888, false);
    }
}
