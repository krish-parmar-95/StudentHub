# 🎓 StudentHub — Final Integration, Deployment & Test Documentation (Practical 15)

## 📌 1. Project Overview
StudentHub is an integrated, full-stack academic management portal built using **Semantic HTML5, CSS3, JavaScript (DOM, Fetch API), PHP 8.x, and MySQL/MariaDB**.

---

## 🏗️ 2. Architectural Structure
```
student hub/
├── css/
│   ├── style.css           # Global unified styles & responsive media queries
│   └── register.css        # Registration & CAPTCHA validation styles
├── js/
│   ├── main.js             # Navigation, themes, modal popups, banner alerts
│   ├── register.js         # Frontend regex validation, strength meter, Canvas CAPTCHA
│   └── fetchdata.js        # Asynchronous JSON exploration
├── data/
│   ├── events.json         # Static test data
│   ├── students.json       # Student explorer fixtures
│   └── faqs.json           # FAQ fixtures
├── uploads/
│   └── posters/            # Validated event poster storage (Practical 12)
├── SQL/
│   └── student_hub.sql     # Database schema, procedures & seed datasets
├── api_events.php          # RESTful JSON API Endpoint (Practical 13)
├── fetch_events.html       # Zero-reload Async CRUD client (Practical 13)
├── db.php                  # Secure PDO connectivity & exceptions (Practical 8)
├── index.php               # Server-side validated registration (Practical 7)
├── register.php            # Secure registration + password hashing (Practical 9)
├── login.php               # RBAC authentication, timeout, remember-me (Practical 10)
├── logout.php              # Session destruction & cookie cleanup
├── student_dashboard.php   # Protected student workspace
├── admin_dashboard.php     # Admin analytics dashboard & audit logs (Practical 14)
├── students_crud.php       # Student CRUD + search/filter + CSV export (Practical 11)
├── events_crud.php         # Event management + poster uploads (Practical 12)
└── view_records.php        # Flat-file data inspector (Practical 7)
```

---

## 🔒 3. Security & Quality Assurance Checklist
| Security Concern | Implementation Detail | Status |
|---|---|:---:|
| **SQL Injection** | PDO Prepared Statements (`PDO::prepare()`) across all modules | ✅ PASS |
| **Cross-Site Scripting (XSS)** | `htmlspecialchars()` applied on all dynamic browser outputs | ✅ PASS |
| **Cross-Site Request Forgery (CSRF)** | Synchronizer Token Pattern using `bin2hex(random_bytes(32))` | ✅ PASS |
| **Password Storage** | `password_hash()` utilizing BCrypt algorithms with `password_verify()` | ✅ PASS |
| **Session Fixation** | `session_regenerate_id(true)` upon successful user authentication | ✅ PASS |
| **Session Inactivity Timeout** | Automated server-side 10-minute timeout eviction | ✅ PASS |
| **Role-Based Access Control (RBAC)** | Route gating ensuring admin privileges for moderation & CRUD | ✅ PASS |
| **Malicious File Uploads** | Extension whitelist validation (`jpg, png, webp`) & 2MB byte-size ceiling | ✅ PASS |

---

## 🧪 4. Complete Test Cases & Results
| Test ID | Module | Scenario | Expected Result | Actual Result | Status |
|---|---|---|---|---|:---:|
| **TC-01** | Frontend | Submit blank registration form | Highlight invalid fields with red borders | Prevented submit & marked fields | ✅ PASS |
| **TC-02** | Practical 7 | CSV & JSON Flat File Save | Data appended to respective data files | Verified entries in `.csv` & `.json` | ✅ PASS |
| **TC-03** | Practical 8 | Database connectivity check | Establish PDO link to `student_hub` | Displayed success banner & rows | ✅ PASS |
| **TC-04** | Practical 9 | Register existing email address | Duplicate email check triggers error | Shown "Email already registered" | ✅ PASS |
| **TC-05** | Practical 10 | Access dashboard without auth | Unauthorized attempt intercepted | Redirected to `login.php?msg=unauthorized` | ✅ PASS |
| **TC-06** | Practical 11 | Soft delete student record | Status marked `inactive` without data loss | Student marked inactive, row preserved | ✅ PASS |
| **TC-07** | Practical 12 | Upload `.pdf` file as event poster | Server rejects non-image extension | Rejected with explicit error message | ✅ PASS |
| **TC-08** | Practical 13 | Live search on Fetch API page | Debounced AJAX fetches matching records | Filtered results without page reload | ✅ PASS |
| **TC-09** | Practical 14 | Admin activity logging | Action written into `audit_log` table | Recorded timestamp, actor & IP | ✅ PASS |

---

## 🚀 5. Local Virtual Host Deployment (Intermediate)
To configure Apache Virtual Host:
1. Append to `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:
```apache
<VirtualHost *:80>
    ServerAdmin admin@studenthub.local
    DocumentRoot "C:/xampp/htdocs/student hub"
    ServerName studenthub.local
    <Directory "C:/xampp/htdocs/student hub">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```
2. Add to `C:\Windows\System32\drivers\etc\hosts`:
```text
127.0.0.1 studenthub.local
```
3. Restart Apache. Site is accessible at `http://studenthub.local`.
