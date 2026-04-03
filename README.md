# 🛡️ Premium CRM & Real-Time Support Hub

Bu proje, müşteri yönetimi (CRM) ile anlık canlı destek (Live Chat) sistemini birleştiren, yüksek performanslı ve modern bir Laravel 13 uygulamasıdır. 

![Laravel](https://img.shields.io/badge/Laravel-13.0-FF2D20?style=for-the-badge&logo=laravel)
![Tailwind](https://img.shields.io/badge/Tailwind_CSS-3.0-38B2AC?style=for-the-badge&logo=tailwind-css)
![Real-time](https://img.shields.io/badge/Real--time-Reverb-blue?style=for-the-badge&logo=laravel)

---

## 🔥 Öne Çıkan Özellikler

### 💬 WhatsApp Stili Mesajlaşma Deneyimi
*   **Modern Chat Bubble:** Müşteri ve temsilci için sağ-sol hizalı, şık mesaj balonları.
*   **Presence Channel:** Kimin online, kimin offline olduğunu anlık (Real-time) görme özelliği.
*   **Typing Indicator (Yazıyor...):** Karşı taraf mesaj yazarken anlık "Yazıyor..." uyarısı.
*   **Unread Count:** Gelen kutusunda (Inbox) okunmamış mesajları anlık sayan dinamik sayaçlar.

### 👥 Akıllı Yönetim & Atama
*   **Round-Robin Auto-Assignment:** Yeni müşteri kayıt olduğunda, en az iş yükü olan (en az müşterisi olan) temsilciye otomatik atama.
*   **Department Monitoring:** Yönetici (Manager) girişiyle tüm departman trafiğini canlı takip etme.
*   **Admin Live View:** Süper Adminlerin tüm sistem yazışmalarını tek ekrandan izleyebileceği Monitoring paneli.

### 🔔 Bildirim Sistemi
*   **Toastr Live Alerts:** Kullanıcı panelin neresinde olursa olsun, yeni bir mesaj geldiğinde sağ üst köşede canlı bildirim alır.
*   **One-Click Messaging:** Müşteri listesi üzerinden tek tıkla yeni konuşma başlatma.

---

## 🛠️ Teknoloji Yığını

*   **Backend:** Laravel 13 (PHP 8.4)
*   **Real-time:** Laravel Reverb (WebSocket) & Echo
*   **Frontend:** Blade Templates & Tailwind CSS (Slate/Indigo Custom Theme)
*   **Database:** MySQL / SQLite
*   **Auth:** Laravel Breeze (Geliştirilmiş Role-based guard yapısı)

---

## 🚀 Kurulum Adımları

1.  **Depoyu Klonlayın:**
    ```bash
    git clone https://github.com/L3G0L4SSD/premium-crm.git
    ```

2.  **Bağımlılıkları Yükleyin:**
    ```bash
    composer install
    npm install
    ```

3.  **Ortam Dosyasını Yapılandırın:**
    `.env` dosyasını kopyalayın ve veritabanı bilgilerinizi girin.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Veritabanını Hazırlayın:**
    ```bash
    php artisan migrate --seed
    ```

5.  **Sunucuyu ve WebSocket'i Başlatın:**
    ```bash
    # Terminal 1 (App sunucusu)
    php artisan serve

    # Terminal 2 (WebSocket sunucusu)
    php artisan reverb:start

    # Terminal 3 (Vite / Frontend)
    npm run dev
    ```

---


---

## 📄 Lisans
Bu proje [MIT Lisansı](LICENSE) altında lisanslanmıştır.

---
*Geliştiren: [L3G0L4SSD](https://github.com/L3G0L4SSD)*
