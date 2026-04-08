<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'TechShop')</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

 
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">


    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <style>
        body { background-color: #f8f9fa; }

   
        .toast-top-center {
            position: fixed;
            top: 70px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1080;
            pointer-events: none;
        }
        .toast {
            pointer-events: auto;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,.2);
        }

     
        .navbar .container-fluid { max-width: 1300px; }

        .mega-dropdown {
            left: 0;
            right: 0;
            border-top: 3px solid #0d6efd;
            animation: fadeIn .15s ease-in-out;
            z-index: 1060;
        }

        #profileMenu {
            z-index: 1050;
        }

        #profileDropdown {
            z-index: 1040;
        }

        .hover-card {
            transition: .2s;
        }
        .hover-card:hover {
            background: #f0f7ff;
            transform: translateY(-3px);
        }

        .icon-wrapper {
            width: 40px;
            height: 40px;
            background: rgba(13,110,253,.1);
            color: #0d6efd;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: none; }
        }
    </style>
</head>

<body>
<div id="app">

    @include('partials.navbar')

    <main class="py-4">
        @yield('content')
    </main>
</div>

<div class="toast-top-center">
    <div id="globalToast"
         class="toast align-items-center text-white bg-primary fade"
         role="alert" aria-live="assertive" aria-atomic="true">

        <div class="toast-header bg-dark text-white">
            <i class="bi bi-info-circle me-2"></i>
            <strong class="me-auto">TechShop</strong>
            <button type="button" class="btn-close btn-close-white"
                    data-bs-dismiss="toast"></button>
        </div>

        <div class="toast-body">Info poruka</div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
(() => {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    function updateCartBadge(count) {
        const badge = document.getElementById('cart-count');
        if (!badge) return;
        badge.textContent = count;
        badge.classList.toggle('d-none', count <= 0);
    }

    function showToast(message, type = 'info') {
        const toastEl = document.getElementById('globalToast');
        if (!toastEl) return;

        const body = toastEl.querySelector('.toast-body');
        const icon = toastEl.querySelector('.toast-header i');

        const map = {
            success: ['bg-success', 'bi-check-circle'],
            error: ['bg-danger', 'bi-exclamation-triangle'],
            warning: ['bg-warning text-dark', 'bi-exclamation-triangle'],
            info: ['bg-primary', 'bi-info-circle']
        };

        toastEl.classList.remove('bg-success','bg-danger','bg-primary','bg-warning','text-dark','text-white');
        const classes = map[type] || map.info;
        classes[0].split(' ').forEach(c => toastEl.classList.add(c));
        if (type !== 'warning') toastEl.classList.add('text-white');
        icon.className = `bi ${map[type][1]} me-2`;
        body.textContent = message;

        new bootstrap.Toast(toastEl, { delay: 3500 }).show();
    }

    document.addEventListener('submit', async e => {
        const form = e.target;
        if (!form.classList.contains('js-add-to-cart')) return;

        e.preventDefault();

        const fd = new FormData(form);
        if (!fd.has('quantity')) fd.set('quantity', 1);

        try {
            const res = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                body: fd
            });

            const data = await res.json();

            if (!res.ok) {
                showToast(data.message || 'Greška prilikom dodavanja', data.type || 'warning');
                return;
            }

            updateCartBadge(data.count || 0);
            showToast(data.message || 'Dodano u košaricu', 'success');

        } catch {
            showToast('Greška prilikom dodavanja', 'error');
        }
    });
})();
</script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const kat = document.querySelector(".kategorije-dropdown");
    if (!kat || window.innerWidth < 992) return;

    const menu = kat.querySelector(".dropdown-menu");
    if (!menu) return; 

    const profileDropdown = document.getElementById("profileDropdown");

    kat.addEventListener("mouseenter", () => {
        const profileMenu = document.getElementById("profileMenu");
        if (profileMenu) profileMenu.style.display = "none";
        if (profileDropdown) profileDropdown.style.zIndex = "auto";
        kat.classList.add("show");
        menu.classList.add("show");
    });

    kat.addEventListener("mouseleave", () => {
        kat.classList.remove("show");
        menu.classList.remove("show");
        if (profileDropdown) profileDropdown.style.zIndex = "";
    });
});
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const profileToggle = document.querySelector('#userMenu');

    if (!profileToggle) return;

    new bootstrap.Dropdown(profileToggle);

    profileToggle.addEventListener('click', () => {
        console.log('Profile dropdown clicked');
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const toggle = document.getElementById("profileToggle");
    const menu = document.getElementById("profileMenu");

    if (!toggle || !menu) return;

    toggle.addEventListener("click", (e) => {
        e.stopPropagation();
        menu.style.display =
            menu.style.display === "block" ? "none" : "block";
    });

    document.addEventListener("click", () => {
        menu.style.display = "none";
    });
});
</script>


 @include('partials.footer')
</body>
</html>
