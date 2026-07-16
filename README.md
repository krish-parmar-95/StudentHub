# 🎓 StudentHub — Student Academic Portal

**Your all-in-one academic companion.** Manage courses, track progress, and stay connected with your campus community.

![Status](https://img.shields.io/badge/status-active%20development-brightgreen)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?logo=javascript&logoColor=black)
![License](https://img.shields.io/badge/license-MIT-blue)

---

## 📖 Description

StudentHub is a web-based academic portal designed for students to manage their coursework, track attendance, view examination results, register for campus events, and communicate with faculty and administration. Built with semantic HTML5, modern CSS3, and vanilla JavaScript, the portal provides a responsive, accessible, and intuitive interface that works across all devices.

This project demonstrates front-end web development best practices including semantic markup, WCAG 2.1 AA accessibility compliance, responsive design, and clean code organization — all without relying on external frameworks or libraries.

---

## ✨ Features

- **📊 Dashboard** — Personalized academic overview with GPA, attendance, and upcoming deadlines
- **📚 Course Catalog** — Browse, search, and filter available courses with detailed information
- **📝 Assignment Tracker** — View all assignments with due dates, submission status, and grades
- **📋 Attendance Monitor** — Track attendance percentages across all enrolled courses
- **📈 Results Viewer** — Access semester-wise grades, GPA, and performance analytics
- **📅 Event Discovery** — Browse and register for campus events, workshops, and seminars
- **👤 Profile Management** — Update personal information and academic preferences
- **✉️ Contact Form** — Reach out to administration with questions or concerns
- **❓ FAQ Section** — Searchable, categorized frequently asked questions
- **💬 Feedback System** — Submit ratings and feedback about courses and the platform
- **⚙️ Admin Panel** — Manage users, courses, events, and view platform analytics
- **♿ Accessibility** — WCAG 2.1 AA compliant with semantic HTML, ARIA labels, and keyboard navigation
- **📱 Responsive Design** — Fully responsive layout optimized for mobile, tablet, and desktop
- **🔑 Authentication UI** — Login and registration pages with form validation

---

## 🛠️ Tech Stack

| Technology | Purpose |
|------------|---------|
| **HTML5** | Semantic page structure and content markup |
| **CSS3** | Styling, layouts (Flexbox/Grid), animations, and responsive design |
| **JavaScript** | DOM manipulation, form validation, interactivity, and navigation |

> **No external dependencies.** This project is built entirely with vanilla web technologies — no frameworks, libraries, or CDN resources required.

---

## 📁 Folder Structure

```
student-hub/
├── index.html              # Home page (landing page)
├── README.md               # Project documentation (this file)
│
├── css/
│   └── style.css           # Global stylesheet
│
├── js/
│   └── main.js             # Global JavaScript
│
├── pages/
│   ├── about.html          # About the platform
│   ├── login.html          # Student login
│   ├── register.html       # New student registration
│   ├── dashboard.html      # Student dashboard
│   ├── courses.html        # Course catalog
│   ├── assignment.html     # Assignment tracker
│   ├── attendance.html     # Attendance records
│   ├── result.html         # Exam results & grades
│   ├── events.html         # Campus events
│   ├── profile.html        # Student profile
│   ├── contact.html        # Contact form
│   ├── faq.html            # Frequently asked questions
│   ├── feedback.html       # Feedback submission
│   └── admin.html          # Admin management panel
│
├── images/
│   └── (project images)    # Image assets
│
└── docs/
    ├── requirements.md     # Functional requirements
    ├── sitemap.md          # Site map & page hierarchy
    └── wireframes.md       # Wireframe descriptions
```

---

## 🚀 How to Run

StudentHub is a static front-end project — no build tools, servers, or installations are required.

### Quick Start

1. **Clone or download** the repository:
   ```bash
   git clone https://github.com/your-username/student-hub.git
   ```

2. **Open the project** in your file explorer or code editor:
   ```bash
   cd student-hub
   ```

3. **Launch the site** by opening `index.html` in your web browser:
   - **Double-click** `index.html` in your file explorer, OR
   - **Right-click** → Open with → your preferred browser, OR
   - Use a live server extension in VS Code for auto-reload:
     ```bash
     # If you have the Live Server extension in VS Code:
     # Right-click index.html → "Open with Live Server"
     ```

4. **Navigate** using the top navigation bar to explore all pages.

> **Tip:** For the best development experience, use [VS Code](https://code.visualstudio.com/) with the [Live Server](https://marketplace.visualstudio.com/items?itemName=ritwickdey.LiveServer) extension for automatic browser refresh on file changes.

---

## 📄 Pages

| # | Page | Path | Description |
|---|------|------|-------------|
| 1 | Home | `index.html` | Landing page with hero, features, stats, and CTA |
| 2 | About | `pages/about.html` | Platform mission, team, and statistics |
| 3 | Login | `pages/login.html` | Student authentication form |
| 4 | Register | `pages/register.html` | New account registration form |
| 5 | Dashboard | `pages/dashboard.html` | Academic overview with stats and activity |
| 6 | Courses | `pages/courses.html` | Searchable course catalog |
| 7 | Assignments | `pages/assignment.html` | Assignment list with due dates and status |
| 8 | Attendance | `pages/attendance.html` | Per-course attendance tracking |
| 9 | Results | `pages/result.html` | Semester grades and GPA |
| 10 | Events | `pages/events.html` | Campus events and registration |
| 11 | Profile | `pages/profile.html` | Student profile management |
| 12 | Contact | `pages/contact.html` | Contact form and info |
| 13 | FAQ | `pages/faq.html` | Categorized Q&A |
| 14 | Feedback | `pages/feedback.html` | Feedback and rating submission |
| 15 | Admin | `pages/admin.html` | Platform management panel |

---

## 🤝 Contributing

Contributions are welcome! Here's how you can help improve StudentHub:

### Getting Started

1. **Fork** the repository on GitHub
2. **Clone** your fork locally:
   ```bash
   git clone https://github.com/your-username/student-hub.git
   ```
3. **Create a feature branch:**
   ```bash
   git checkout -b feature/your-feature-name
   ```
4. **Make your changes** following the guidelines below
5. **Commit** with a descriptive message:
   ```bash
   git commit -m "Add: description of your changes"
   ```
6. **Push** to your fork:
   ```bash
   git push origin feature/your-feature-name
   ```
7. **Open a Pull Request** against the `main` branch

### Guidelines

- **HTML:** Use semantic HTML5 elements. Every page must have exactly one `<h1>` tag with proper heading hierarchy. Include skip-to-content links and ARIA labels.
- **CSS:** Follow the existing naming conventions. Use CSS custom properties for colors and spacing. Ensure responsive design across all breakpoints.
- **JavaScript:** Write clean, commented vanilla JS. Avoid global namespace pollution. No external libraries or frameworks.
- **Accessibility:** All new content must meet WCAG 2.1 AA standards. Test with keyboard navigation and screen readers.
- **Content:** Use realistic sample data — no "Lorem ipsum" placeholder text.
- **Icons:** Use emoji icons (🎓 📚 📊 etc.) instead of icon libraries.

### Reporting Issues

- Use GitHub Issues to report bugs or suggest features
- Include browser version and steps to reproduce for bug reports
- Tag issues appropriately (bug, enhancement, accessibility, etc.)

---

## 📋 Browser Support

| Browser | Version |
|---------|---------|
| Chrome | Latest |
| Firefox | Latest |
| Edge | Latest |
| Safari | Latest |

---

## 📜 License

This project is licensed under the **MIT License**.

```
MIT License

Copyright (c) 2026 StudentHub

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

---

<p align="center">
  Made with ❤️ by the StudentHub Development Team
</p>
#   k r i s h  
 