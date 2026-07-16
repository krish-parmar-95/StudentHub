# StudentHub — Wireframe Descriptions

**Version:** 1.0  
**Last Updated:** July 2026  
**Fidelity:** Low (structural layout only)

---

## 1. Home Page (`index.html`)

The home page serves as the main landing page and entry point for all visitors. It introduces the platform and guides users toward registration or login.

### Layout Structure

```
┌──────────────────────────────────────────────────────────────┐
│ [Skip to main content]                                       │
├──────────────────────────────────────────────────────────────┤
│ HEADER                                                       │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ 🎓 StudentHub          Home | About | Dashboard |       │ │
│ │                         Courses | Events | Contact |     │ │
│ │                         FAQ | [Login] [Register]         │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ HERO SECTION                                                 │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │                                                          │ │
│ │            Your Academic Journey Starts Here             │ │
│ │                                                          │ │
│ │    Manage courses, track progress, and stay connected    │ │
│ │          with your campus community.                     │ │
│ │                                                          │ │
│ │          [Get Started]    [Learn More]                   │ │
│ │                                                          │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ FEATURES GRID (3 columns on desktop, 1 on mobile)            │
│ ┌──────────────────┐ ┌──────────────────┐ ┌────────────────┐ │
│ │ 📚               │ │ 📊               │ │ 📅             │ │
│ │ Course Tracking  │ │ Grade Analytics  │ │ Event Calendar │ │
│ │                  │ │                  │ │                │ │
│ │ Browse and       │ │ View your GPA,   │ │ Discover       │ │
│ │ manage all your  │ │ semester grades, │ │ campus events, │ │
│ │ enrolled courses │ │ and performance  │ │ workshops, and │ │
│ │ in one place.    │ │ trends at a      │ │ register with  │ │
│ │                  │ │ glance.          │ │ one click.     │ │
│ └──────────────────┘ └──────────────────┘ └────────────────┘ │
│ ┌──────────────────┐ ┌──────────────────┐ ┌────────────────┐ │
│ │ 📝               │ │ 📋               │ │ 👤             │ │
│ │ Assignments      │ │ Attendance       │ │ Profile        │ │
│ │                  │ │                  │ │                │ │
│ │ Track deadlines, │ │ Monitor your     │ │ Manage your    │ │
│ │ submission       │ │ attendance       │ │ personal info  │ │
│ │ status, and      │ │ across all       │ │ and academic   │ │
│ │ grades.          │ │ courses.         │ │ preferences.   │ │
│ └──────────────────┘ └──────────────────┘ └────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ STATS SECTION                                                │
│ ┌────────────┐  ┌────────────┐  ┌────────────┐  ┌─────────┐ │
│ │   1,200+   │  │    50+     │  │    120+    │  │   95%   │ │
│ │  Students  │  │  Courses   │  │   Events   │  │ Rating  │ │
│ └────────────┘  └────────────┘  └────────────┘  └─────────┘ │
├──────────────────────────────────────────────────────────────┤
│ CTA SECTION                                                  │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │                                                          │ │
│ │          Ready to get started?                           │ │
│ │          Join thousands of students already using        │ │
│ │          StudentHub to manage their academic life.       │ │
│ │                                                          │ │
│ │                    [Register Now]                        │ │
│ │                                                          │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ FOOTER                                                       │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ 🎓 StudentHub    │ Quick Links │ Students  │ Support    │ │
│ │ Your all-in-one  │ Home        │ Dashboard │ Contact    │ │
│ │ academic         │ About       │ Assign.   │ FAQ        │ │
│ │ companion.       │ Courses     │ Results   │ Feedback   │ │
│ │                  │ Events      │ Attend.   │ Admin      │ │
│ ├──────────────────────────────────────────────────────────┤ │
│ │ © 2026 StudentHub. All rights reserved.   🐦 💻 💼     │ │
│ └──────────────────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────┘
```

### Components Breakdown

| Component | Description |
|-----------|-------------|
| **Header** | Sticky top bar with logo (left), navigation links (center/right), Login and Register buttons (right) |
| **Hero Section** | Full-width banner with large heading, subheading text, and two CTA buttons |
| **Features Grid** | 3×2 grid of feature cards, each with an emoji icon, title, and description |
| **Stats Section** | Horizontal row of 4 animated counter statistics |
| **CTA Section** | Highlighted call-to-action block encouraging registration |
| **Footer** | 4-column layout with brand info, quick links, student links, and support links |

---

## 2. Login Page (`pages/login.html`)

The login page features a centered authentication card for clean, focused user interaction.

### Layout Structure

```
┌──────────────────────────────────────────────────────────────┐
│ [Skip to main content]                                       │
├──────────────────────────────────────────────────────────────┤
│ HEADER (same as Home)                                        │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│                                                              │
│           ┌────────────────────────────────────┐             │
│           │          🔑                        │             │
│           │     Welcome Back                   │             │
│           │     Sign in to your account        │             │
│           │                                    │             │
│           │  ┌──────────────────────────────┐  │             │
│           │  │ 📧 Email Address             │  │             │
│           │  │ student@university.edu       │  │             │
│           │  └──────────────────────────────┘  │             │
│           │                                    │             │
│           │  ┌──────────────────────────────┐  │             │
│           │  │ 🔒 Password                  │  │             │
│           │  │ ••••••••••                   │  │             │
│           │  └──────────────────────────────┘  │             │
│           │                                    │             │
│           │  ☐ Remember Me    Forgot Password? │             │
│           │                                    │             │
│           │  ┌──────────────────────────────┐  │             │
│           │  │         [Sign In]            │  │             │
│           │  └──────────────────────────────┘  │             │
│           │                                    │             │
│           │  Don't have an account? Register   │             │
│           └────────────────────────────────────┘             │
│                                                              │
│                                                              │
├──────────────────────────────────────────────────────────────┤
│ FOOTER (same as Home)                                        │
└──────────────────────────────────────────────────────────────┘
```

### Components Breakdown

| Component | Description |
|-----------|-------------|
| **Auth Card** | Centered card (max-width ~450px) with subtle shadow and rounded corners |
| **Icon + Heading** | Emoji icon with "Welcome Back" heading and subtitle |
| **Email Field** | Labeled text input for email address with validation |
| **Password Field** | Labeled password input with masked characters |
| **Options Row** | Checkbox for "Remember Me" and link for "Forgot Password" |
| **Submit Button** | Full-width primary button labeled "Sign In" |
| **Register Link** | Text link at the bottom directing new users to the registration page |

---

## 3. Register Page (`pages/register.html`)

The registration page uses the same centered card pattern as login, with additional form fields.

### Layout Structure

```
┌──────────────────────────────────────────────────────────────┐
│ [Skip to main content]                                       │
├──────────────────────────────────────────────────────────────┤
│ HEADER (same as Home)                                        │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│           ┌────────────────────────────────────┐             │
│           │          📝                        │             │
│           │     Create Account                 │             │
│           │     Join StudentHub today          │             │
│           │                                    │             │
│           │  ┌──────────────────────────────┐  │             │
│           │  │ 👤 Full Name                 │  │             │
│           │  └──────────────────────────────┘  │             │
│           │                                    │             │
│           │  ┌──────────────────────────────┐  │             │
│           │  │ 📧 Email Address             │  │             │
│           │  └──────────────────────────────┘  │             │
│           │                                    │             │
│           │  ┌──────────────────────────────┐  │             │
│           │  │ 🆔 Student ID               │  │             │
│           │  └──────────────────────────────┘  │             │
│           │                                    │             │
│           │  ┌──────────────────────────────┐  │             │
│           │  │ 🎓 Program  [Dropdown ▼]     │  │             │
│           │  └──────────────────────────────┘  │             │
│           │                                    │             │
│           │  ┌──────────────────────────────┐  │             │
│           │  │ 🔒 Password                  │  │             │
│           │  └──────────────────────────────┘  │             │
│           │                                    │             │
│           │  ┌──────────────────────────────┐  │             │
│           │  │ 🔒 Confirm Password          │  │             │
│           │  └──────────────────────────────┘  │             │
│           │                                    │             │
│           │  ☐ I agree to Terms & Conditions   │             │
│           │                                    │             │
│           │  ┌──────────────────────────────┐  │             │
│           │  │      [Create Account]        │  │             │
│           │  └──────────────────────────────┘  │             │
│           │                                    │             │
│           │  Already have an account? Login    │             │
│           └────────────────────────────────────┘             │
│                                                              │
├──────────────────────────────────────────────────────────────┤
│ FOOTER (same as Home)                                        │
└──────────────────────────────────────────────────────────────┘
```

### Components Breakdown

| Component | Description |
|-----------|-------------|
| **Auth Card** | Same centered card style as login, accommodating more fields |
| **Name Field** | Text input for student's full name |
| **Email Field** | Text input for email address with format validation |
| **Student ID** | Text input for institutional student ID number |
| **Program Dropdown** | Select menu with program options (Computer Science, Engineering, etc.) |
| **Password Fields** | Two password inputs — one for password, one for confirmation |
| **Terms Checkbox** | Required agreement to terms and conditions |
| **Submit Button** | Full-width primary button labeled "Create Account" |
| **Login Link** | Text link directing existing users to the login page |

---

## 4. Dashboard Page (`pages/dashboard.html`)

The dashboard is the central student hub, displaying a sidebar navigation alongside the main content area with stats cards, tables, and quick-access links.

### Layout Structure

```
┌──────────────────────────────────────────────────────────────┐
│ [Skip to main content]                                       │
├──────────────────────────────────────────────────────────────┤
│ HEADER (same as Home)                                        │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│  DASHBOARD WELCOME BAR                                       │
│  ┌──────────────────────────────────────────────────────────┐│
│  │ Welcome back, Sarah! 👋        Semester: Fall 2026      ││
│  └──────────────────────────────────────────────────────────┘│
│                                                              │
│  ┌───────────┬──────────────────────────────────────────────┐│
│  │ SIDEBAR   │  MAIN CONTENT                               ││
│  │           │                                              ││
│  │ 📊 Over. │  STATS CARDS (4 columns)                     ││
│  │ 📚 Course│  ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐        ││
│  │ 📝 Assign│  │ 3.75 │ │ 92%  │ │  6   │ │  3   │        ││
│  │ 📋 Attend│  │ GPA  │ │Attend│ │Courses│ │ Due  │        ││
│  │ 📈 Result│  └──────┘ └──────┘ └──────┘ └──────┘        ││
│  │ 📅 Events│                                              ││
│  │ 👤 Profil│  QUICK ACCESS (icon buttons grid)            ││
│  │           │  ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐       ││
│  │           │  │  📚  │ │  📝  │ │  📋  │ │  📈  │       ││
│  │           │  │Course│ │Assign│ │Attend│ │Result│       ││
│  │           │  └──────┘ └──────┘ └──────┘ └──────┘       ││
│  │           │                                              ││
│  │           │  RECENT ACTIVITY TABLE                       ││
│  │           │  ┌──────────────────────────────────────┐    ││
│  │           │  │ Activity       │ Course   │ Date     │    ││
│  │           │  ├────────────────┼──────────┼──────────┤    ││
│  │           │  │ Quiz 3 Graded  │ CS301    │ Jul 14   │    ││
│  │           │  │ HW 5 Due       │ MTH202   │ Jul 18   │    ││
│  │           │  │ Lab Report Due │ PHY101   │ Jul 20   │    ││
│  │           │  │ Midterm Score  │ ENG105   │ Jul 12   │    ││
│  │           │  └──────────────────────────────────────┘    ││
│  │           │                                              ││
│  │           │  UPCOMING SCHEDULE                           ││
│  │           │  ┌──────────────────────────────────────┐    ││
│  │           │  │ Time    │ Course          │ Room     │    ││
│  │           │  ├─────────┼─────────────────┼──────────┤    ││
│  │           │  │ 9:00 AM │ Data Structures │ Room 301 │    ││
│  │           │  │ 11:00AM │ Calculus II     │ Room 105 │    ││
│  │           │  │ 2:00 PM │ Physics Lab     │ Lab 203  │    ││
│  │           │  └──────────────────────────────────────┘    ││
│  └───────────┴──────────────────────────────────────────────┘│
│                                                              │
├──────────────────────────────────────────────────────────────┤
│ FOOTER (same as Home)                                        │
└──────────────────────────────────────────────────────────────┘
```

### Components Breakdown

| Component | Description |
|-----------|-------------|
| **Welcome Bar** | Full-width bar with greeting (student name) and current semester label |
| **Sidebar Navigation** | Vertical nav panel (collapsible on mobile) with icon+label links to all student sections |
| **Stats Cards** | 4 summary cards showing GPA, attendance %, enrolled courses, and pending assignments |
| **Quick Access Grid** | Icon button grid linking to Courses, Assignments, Attendance, Results |
| **Recent Activity Table** | Table listing latest academic activity (grades posted, upcoming deadlines) |
| **Upcoming Schedule** | Today's class schedule with time, course name, and room number |

### Responsive Behavior

- **Desktop (769px+):** Sidebar fixed on the left, main content fills remaining width
- **Tablet (577–768px):** Sidebar collapses to icons only, main content expands
- **Mobile (≤576px):** Sidebar hidden behind toggle button, stats cards stack vertically in single column

---

## 5. Contact Page (`pages/contact.html`)

The contact page uses a two-column layout with contact information on one side and a submission form on the other.

### Layout Structure

```
┌──────────────────────────────────────────────────────────────┐
│ [Skip to main content]                                       │
├──────────────────────────────────────────────────────────────┤
│ HEADER (same as Home)                                        │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│  PAGE TITLE SECTION                                          │
│  ┌──────────────────────────────────────────────────────────┐│
│  │              ✉️ Get In Touch                             ││
│  │    We'd love to hear from you. Reach out with any       ││
│  │    questions, concerns, or feedback.                     ││
│  └──────────────────────────────────────────────────────────┘│
│                                                              │
│  TWO-COLUMN LAYOUT                                           │
│  ┌───────────────────────┬──────────────────────────────────┐│
│  │ CONTACT INFO          │ CONTACT FORM                    ││
│  │                       │                                  ││
│  │ 📍 Address            │  ┌────────────────────────────┐  ││
│  │ 123 University Ave    │  │ Full Name *               │  ││
│  │ Campus Building B     │  │ [___________________]     │  ││
│  │ Springfield, ST 62701 │  └────────────────────────────┘  ││
│  │                       │                                  ││
│  │ 📧 Email              │  ┌────────────────────────────┐  ││
│  │ support@studenthub   │  │ Email Address *            │  ││
│  │   .edu               │  │ [___________________]     │  ││
│  │                       │  └────────────────────────────┘  ││
│  │ 📞 Phone              │                                  ││
│  │ +1 (555) 123-4567    │  ┌────────────────────────────┐  ││
│  │                       │  │ Department [Dropdown ▼]   │  ││
│  │ 🕐 Office Hours       │  └────────────────────────────┘  ││
│  │ Mon-Fri: 8AM - 5PM   │                                  ││
│  │ Sat: 9AM - 1PM       │  ┌────────────────────────────┐  ││
│  │ Sun: Closed           │  │ Subject *                 │  ││
│  │                       │  │ [___________________]     │  ││
│  │ 🌐 Social             │  └────────────────────────────┘  ││
│  │ 🐦 💻 💼              │                                  ││
│  │                       │  ┌────────────────────────────┐  ││
│  │                       │  │ Message *                 │  ││
│  │                       │  │ [                         │  ││
│  │                       │  │                           │  ││
│  │                       │  │                           │  ││
│  │                       │  │ ________________________] │  ││
│  │                       │  └────────────────────────────┘  ││
│  │                       │                                  ││
│  │                       │  ┌────────────────────────────┐  ││
│  │                       │  │     [Send Message]        │  ││
│  │                       │  └────────────────────────────┘  ││
│  └───────────────────────┴──────────────────────────────────┘│
│                                                              │
│  MAP SECTION (optional)                                      │
│  ┌──────────────────────────────────────────────────────────┐│
│  │                                                          ││
│  │               [Embedded Campus Map]                      ││
│  │                                                          ││
│  └──────────────────────────────────────────────────────────┘│
│                                                              │
├──────────────────────────────────────────────────────────────┤
│ FOOTER (same as Home)                                        │
└──────────────────────────────────────────────────────────────┘
```

### Components Breakdown

| Component | Description |
|-----------|-------------|
| **Page Title** | Centered heading with emoji icon and descriptive subtitle |
| **Contact Info Column** | Left column (40% width) with address, email, phone, office hours, and social links |
| **Contact Form Column** | Right column (60% width) with labeled form fields and submit button |
| **Name Field** | Required text input with label |
| **Email Field** | Required email input with validation |
| **Department Dropdown** | Select menu (Academic Affairs, Technical Support, Admissions, etc.) |
| **Subject Field** | Required text input for message subject |
| **Message Textarea** | Required multi-line text area for the message body |
| **Submit Button** | Primary button labeled "Send Message" |
| **Map Section** | Optional embedded map showing campus location |

### Responsive Behavior

- **Desktop:** Side-by-side two-column layout (40/60 split)
- **Tablet:** Stacked layout — contact info on top, form below
- **Mobile:** Full-width stacked layout with larger touch targets on form inputs

---

## Design Notes

### Consistent Elements Across All Pages

1. **Skip Link:** Every page starts with a visually hidden "Skip to main content" link that becomes visible on keyboard focus
2. **Header:** Persistent header with logo and navigation (hamburger menu on mobile)
3. **Footer:** Consistent 4-column footer with brand, quick links, student links, and support links
4. **Container:** All content wrapped in a max-width container (~1200px) centered on the page

### Color Palette Guidelines

| Role | Usage |
|------|-------|
| **Primary** | Buttons, links, active nav items, hero background |
| **Secondary** | Secondary buttons, hover states, accent elements |
| **Success (Green)** | Good attendance, passed grades, confirmations |
| **Warning (Yellow)** | Moderate attendance, upcoming deadlines |
| **Danger (Red)** | Low attendance, overdue assignments, errors |
| **Neutral (Gray)** | Text, borders, backgrounds, disabled states |

### Typography Guidelines

| Element | Size | Weight |
|---------|------|--------|
| h1 | 2.5rem | Bold |
| h2 | 2rem | Semi-bold |
| h3 | 1.5rem | Semi-bold |
| Body | 1rem | Normal |
| Small | 0.875rem | Normal |

---

*Document maintained by the StudentHub Development Team.*
