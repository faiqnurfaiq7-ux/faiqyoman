<div class="sidebar">
    <h2>Dashboard Kasir</h2>
    <a href="#" class="menu-btn kasir">🧾 Kasir</a>
    <a href="#" class="menu-btn produk">📦 Produk</a>
    <a href="#" class="menu-btn pelanggan">👥 Pelanggan</a>
    <a href="#" class="menu-btn laporan">📊 Laporan</a>
    <form action="#" method="POST">
        @csrf
        <button type="submit" class="menu-btn logout">🚪 Logout</button>
    </form>
</div>

<style>
    * {
        box-sizing: border-box;
        font-family: 'Segoe UI', sans-serif;
    }

    body {
        margin: 0;
        display: flex;
        background-color: #f5f6fa;
    }

    /* Sidebar */
    .sidebar {
        width: 230px;
        background-color: #fff;
        height: 100vh;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        padding: 20px;
        display: flex;
        flex-direction: column;
        align-items: stretch;
    }

    .sidebar h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    .menu-btn {
        display: block;
        padding: 12px;
        margin: 6px 0;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        cursor: pointer;
        text-align: left;
        text-decoration: none;
        transition: transform 0.2s;
    }

    .menu-btn:hover {
        transform: scale(1.05);
    }

    .kasir { background-color: #2d7ef7; }
    .produk { background-color: #28c76f; }
    .pelanggan { background-color: #f5b800; }
    .laporan { background-color: #a855f7; }
    .logout { background-color: #ff4d6d; margin-top: auto; }
</style>
