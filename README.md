# 🛡️ Premium CRM & Real-Time Support Hub

Bu proje, müşteri yönetimi (CRM) ile anlık canlı destek (Live Chat) sistemini birleştiren, yüksek performanslı ve modern bir Laravel 13 uygulamasıdır. 

![Laravel](https://img.shields.io/badge/Laravel-13.0-FF2D20?style=for-the-badge&logo=laravel)
![Tailwind](https://img.shields.io/badge/Tailwind_CSS-3.0-38B2AC?style=for-the-badge&logo=tailwind-css)
![Real-time](https://img.shields.io/badge/Real--time-Reverb-blue?style=for-the-badge&logo=laravel)

---
## 🛠️ Teknoloji Yığını
| Katman | Teknoloji | Notlar |
| :--- | :--- | :--- |
| **Backend** | **Laravel 13** | En güncel Laravel sürümü kullanılmaktadır. |
| **Frontend** | **Blade, Tailwind CSS 4, Alpine.js** | Modern ve hızlı bir arayüz yapısı. |
| **Gerçek Zamanlı** | **Laravel Reverb & Echo** | WebSocket üzerinden canlı mesajlaşma. |
| **Kimlik Doğrulama** | **Laravel Breeze** | Admin, Personel ve Müşteri için özelleştirilmiş giriş sistemleri. |
| **Veritabanı** | **MySQL/SQLite** | İlişkisel veritabanı (Eloquent ORM ile). |

## 🏗️ Veritabanı ve Model Yapısı
Proje, karmaşık ilişkileri yönetmek için şu modelleri kullanmaktadır:

*   **User:** Yönetici (Admin), Yönetici (Manager) ve Personel (Employee) kullanıcılarını tutar.
*   **Customer:** CRM'e kayıtlı müşterileri temsil eder. Kendi özel giriş sistemine sahiptir.
*   **Role & Department:** Kullanıcıların yetki seviyelerini ve bağlı oldukları departmanları belirler.
*   **Conversation & Message:** Mesajlaşma sisteminin temelini oluşturur. Bir konuşma bir müşteri ve bir personel arasındadır.

## 🌟 Önemli Özellikler

### 1. Rol Tabanlı Erişim Kontrolü (RBAC)
*   **Admin:** Tüm sistemi izleyebilir, kullanıcıları yönetebilir ve her şeyi monitor edebilir.
*   **Manager:** Kendi ekibini ve kullanıcılarını yönetebilir.
*   **Employee (Personel):** Müşterilerle iletişim kurar ve talepleri yönetir.
*   **Customer (Müşteri):** Kendi paneline giriş yaparak mesaj gönderebilir ve profilini güncelleyebilir.

### 2. Gerçek Zamanlı Mesajlaşma Sistemi
*   **WhatsApp Benzeri Arayüz:** Chat balonları, okundu bilgisi (read_at) ve zaman damgaları.
*   **Canlı Bildirimler:** Mesaj geldiğinde sayfa yenilenmeden Toastr ile bildirim gösterilir.
*   **Yazıyor/Çevrimiçi Durumu:** (Geliştirilmekte olan özellikler arasında).

### 3. Panel Yönetimi
*   **Müşteri Paneli:** `/customer/dashboard` üzerinden erişilen, sade ve işlevsel arayüz.
*   **Yönetim Paneli:** Personel ve adminler için `/admin` ve `/manager` rotaları altında toplanmış detaylı istatistikler ve yönetim araçları.

## 📂 Dosya Yapısı Analizi
*   `app/Http/Controllers/`: İş mantığının (business logic) ayrıştırıldığı yer. Admin, Manager ve Customer için ayrı klasörlerde toplanmıştır.
*   `routes/web.php`: Uygulamanın tüm giriş noktalarını ve middleware (koruma) katmanlarını tanımlar.
*   `resources/views/`: Blade şablonları ile oluşturulmuş, kullanıcı arayüzü dosyaları.
*   `database/migrations/`: Veritabanı şemasının tarihsel gelişimi ve tablo yapıları.

## 🏁 Sonuç ve Öneriler
Proje, sağlam bir temel üzerine (Laravel 13 + Reverb) inşa edilmiştir. Mevcut yapı hem ölçeklenebilir hem de oldukça moderndir. 

> [!TIP]
> **Gelecek Adımlar:** Sisteme dosya paylaşımı özelliği, müşteri notları ve gelişmiş raporlama (grafiklerle desteklenmiş) eklenebilir.



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
