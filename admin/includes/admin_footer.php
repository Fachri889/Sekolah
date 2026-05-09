    </div><!-- end admin-content -->
</div><!-- end admin-main -->

<!-- Sidebar overlay for mobile -->
<div id="sidebarOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:99;" onclick="toggleSidebar()"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.toggle('show');
    overlay.style.display = sidebar.classList.contains('show') ? 'block' : 'none';
}

// Auto dismiss alerts
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(a => {
        try { bootstrap.Alert.getOrCreateInstance(a).close(); } catch(e) {}
    });
}, 4000);

// Logout confirmation
document.querySelectorAll('.btn-logout').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const logoutUrl = this.getAttribute('href');
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda akan keluar dari panel admin!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Keluar!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            background: '#fff',
            borderRadius: '15px'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = logoutUrl;
            }
        });
    });
});

// Upgrade Delete confirmation to SweetAlert2
document.querySelectorAll('.btn-hapus').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const deleteUrl = this.getAttribute('href');
        
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = deleteUrl;
            }
        });
    });
});
</script>
</body>
</html>
