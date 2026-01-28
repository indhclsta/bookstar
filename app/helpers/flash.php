<?php

class Flash
{
    public static function success($message)
    {
        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => $message
        ];
    }

    public static function error($message)
    {
        $_SESSION['flash'] = [
            'type' => 'error',
            'message' => $message
        ];
    }

    /* =======================
       CONFIRM LOGOUT
    ======================== */
    public static function confirmLogout($logoutUrl)
    {
        $url = json_encode($logoutUrl);

        echo "
        <script>
            Swal.fire({
                title: 'Yakin mau logout?',
                text: 'Kamu harus login lagi kalau keluar 😅',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#3b82f6'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = $url;
                }
            });
        </script>
        ";
    }

    /* =======================
       RENDER FLASH
    ======================== */
    public static function render()
    {
        if (!isset($_SESSION['flash'])) return;

        $type = $_SESSION['flash']['type'];
        $message = json_encode($_SESSION['flash']['message']);

        unset($_SESSION['flash']);

        echo "
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: '$type',
                title: $message,
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });
        </script>
        ";
    }
}
