# StudentHub — Functional Requirements Document

**Version:** 1.0  
**Date:** July 2026  
**Status:** Active Development

---

## 1. Project Overview

**StudentHub** is a web-based academic portal designed for students to manage their coursework, track attendance, view examination results, register for campus events, and communicate with faculty and administration. The platform serves as a centralized hub where students can access all academic and extracurricular information in one place, reducing the need to navigate multiple disparate systems.

The portal aims to streamline the student experience by providing an intuitive, accessible, and responsive interface that works seamlessly across desktops, tablets, and mobile devices.

### 1.1 Goals

- Provide a single point of access for all academic information
- Enable students to stay on top of assignments and deadlines
- Allow real-time attendance and grade tracking
- Facilitate event discovery and campus engagement
- Offer administrative tools for managing users, courses, and events
- Ensure accessibility for all users regardless of ability

### 1.2 Scope

This document covers the front-end implementation of the StudentHub portal built with HTML5, CSS3, and vanilla JavaScript. The current scope includes static pages with simulated data. Backend integration is planned for a future phase.

---

## 2. User Roles

### 2.1 Student (Primary User)

The student is the primary user of the platform. Students can:

- **View Courses:** Browse the course catalog, see enrolled courses, and access course details including syllabus, schedule, and instructor information
- **Track Assignments:** View upcoming and past assignments with due dates, submission status, and grade feedback
- **Monitor Attendance:** Check attendance records across all enrolled courses, view percentage breakdowns, and identify courses with low attendance
- **View Results:** Access examination results, semester GPAs, cumulative GPA, and individual subject grades
- **Discover Events:** Browse upcoming campus events, workshops, seminars, and extracurricular activities, and register for events of interest
- **Manage Profile:** Update personal information, contact details, profile picture, and notification preferences

### 2.2 Admin

The admin manages the platform and its content. Admins can:

- **Manage Users:** View registered students, activate/deactivate accounts, and manage user permissions
- **Manage Courses:** Add, edit, or remove courses from the catalog, assign faculty, and set schedules
- **Manage Events:** Create and publish campus events, manage registrations, and send notifications
- **View Analytics:** Access platform usage statistics, enrollment numbers, attendance trends, and performance metrics

### 2.3 Faculty (Future Scope)

Faculty support is planned for a future release and will include:

- Posting assignments and course materials
- Recording attendance
- Entering grades and providing feedback
- Communicating with enrolled students

---

## 3. Functional Requirements

### FR-01: User Registration

- Users can create a new account by providing full name, email address, student ID, password, and program of study
- Email format validation must be enforced
- Password must meet minimum strength requirements (8+ characters, mix of letters and numbers)
- Registration form must include clear labels and error messaging
- Successful registration redirects to the login page

### FR-02: User Login

- Registered users can log in using email and password
- Login form includes a "Remember Me" option
- Failed login attempts display a descriptive error message
- A "Forgot Password" link is available (UI only in current scope)

### FR-03: Dashboard

- After login, students see a personalized dashboard with an academic overview
- Dashboard displays: current GPA, attendance percentage, upcoming assignments count, and enrolled courses count
- Quick-access cards link to Courses, Assignments, Attendance, Results, and Events
- Recent activity feed shows latest grades, announcements, and deadlines
- Current semester schedule is displayed in a summary view

### FR-04: Course Catalog

- Students can browse all available courses in a searchable catalog
- Each course card displays: course name, code, instructor, credits, schedule, and enrollment status
- Courses can be filtered by department, semester, or credit hours
- Detailed course view shows full syllabus, prerequisites, and enrolled student count

### FR-05: Assignment Tracking

- Students can view all assignments across enrolled courses
- Each assignment shows: title, course name, due date, status (pending, submitted, graded), and grade (if available)
- Assignments are sorted by due date with overdue items highlighted
- Filter options: All, Pending, Submitted, Graded, Overdue

### FR-06: Attendance Monitoring

- Students can view attendance records for each enrolled course
- Attendance summary shows: total classes, classes attended, classes missed, and attendance percentage
- Color-coded indicators: green (≥75%), yellow (60–74%), red (<60%)
- Monthly or weekly attendance breakdown is available

### FR-07: Result / Grade Viewing

- Students can view results for current and past semesters
- Results table includes: subject name, code, credits, grade, and grade points
- Semester GPA and cumulative GPA are prominently displayed
- Visual grade distribution chart for performance overview

### FR-08: Event Discovery and Registration

- Students can browse upcoming campus events in a card-based layout
- Each event displays: title, date, time, location, category, and description
- Events can be filtered by category (academic, cultural, sports, workshop)
- Students can register for events with a single click
- Registered events appear in the student's dashboard

### FR-09: Profile Management

- Students can view and edit their profile information
- Editable fields: display name, phone number, address, bio, and profile picture
- Read-only fields: student ID, email, enrollment date, and program
- Profile page shows academic summary statistics

### FR-10: Contact Form

- A contact page allows students to reach out to administration
- Form fields: name, email, subject, department (dropdown), and message
- Form validation ensures all required fields are completed
- Contact information (email, phone, office hours, address) is displayed alongside the form

### FR-11: FAQ Section

- A searchable FAQ page with categorized questions and answers
- Categories include: Admissions, Courses, Technical Support, Campus Life, and Fees
- Accordion-style expandable answers for clean presentation
- Search bar filters questions in real time

### FR-12: Feedback Submission

- Students can submit feedback about courses, faculty, or the platform
- Feedback form includes: category, subject, rating (star-based), and detailed comments
- Anonymous submission option is available
- Confirmation message is displayed after successful submission

### FR-13: Admin Panel

- Admins can access a management dashboard with platform-wide statistics
- User management table with search, filter, and status toggle
- Course management with add/edit capabilities
- Event management with creation and registration tracking
- Analytics overview with key metrics and charts

---

## 4. Non-Functional Requirements

### NFR-01: Responsive Design

- The portal must be fully responsive across all device sizes
- Breakpoints: mobile (≤576px), tablet (577px–768px), desktop (769px+)
- Navigation must collapse to a hamburger menu on mobile devices
- All content must be readable without horizontal scrolling

### NFR-02: Accessibility (WCAG 2.1 AA)

- All pages must meet WCAG 2.1 Level AA compliance
- Semantic HTML5 elements must be used throughout (header, nav, main, section, article, aside, footer)
- All images must have descriptive alt text
- All form inputs must have associated label elements
- Skip-to-content links must be present on every page
- ARIA labels must be used for interactive elements and navigation landmarks
- Color contrast ratios must meet 4.5:1 minimum for normal text
- All functionality must be keyboard accessible

### NFR-03: Performance

- Pages must load within 2 seconds on a standard broadband connection
- CSS and JavaScript files must be optimized and minimal
- Images should be appropriately sized and compressed
- No external CDN dependencies for core functionality

### NFR-04: Cross-Browser Compatibility

- The portal must function correctly on the latest versions of:
  - Google Chrome
  - Mozilla Firefox
  - Microsoft Edge
  - Apple Safari
- Graceful degradation for older browser versions

### NFR-05: Code Quality

- Valid HTML5 markup (W3C compliant)
- Well-organized CSS with consistent naming conventions
- Clean, commented JavaScript with no global namespace pollution
- Consistent file and folder structure

---

## 5. Navigation Flow

```
Home Page
  ├── Register → Login → Dashboard
  │                         ├── Courses
  │                         ├── Assignments
  │                         ├── Attendance
  │                         ├── Results
  │                         ├── Events
  │                         └── Profile
  ├── About
  ├── Courses (Public Catalog)
  ├── Events (Public Listing)
  ├── Contact
  ├── FAQ
  └── Feedback
```

### Primary User Flow

1. **New User:** Home → Register → Login → Dashboard → Explore Features
2. **Returning User:** Home → Login → Dashboard → Access Academic Tools
3. **Visitor:** Home → About / Courses / Events / Contact / FAQ (public pages)

---

## 6. Page Inventory (15 Pages)

The StudentHub portal consists of a minimum of **15 pages** to meet project requirements:

| #  | Page         | File Path               | Access Level |
|----|--------------|-------------------------|--------------|
| 1  | Home         | `index.html`            | Public       |
| 2  | About        | `pages/about.html`      | Public       |
| 3  | Login        | `pages/login.html`      | Public       |
| 4  | Register     | `pages/register.html`   | Public       |
| 5  | Dashboard    | `pages/dashboard.html`  | Student      |
| 6  | Courses      | `pages/courses.html`    | Public       |
| 7  | Assignments  | `pages/assignment.html`  | Student      |
| 8  | Attendance   | `pages/attendance.html` | Student      |
| 9  | Results      | `pages/result.html`     | Student      |
| 10 | Events       | `pages/events.html`     | Public       |
| 11 | Profile      | `pages/profile.html`    | Student      |
| 12 | Contact      | `pages/contact.html`    | Public       |
| 13 | FAQ          | `pages/faq.html`        | Public       |
| 14 | Feedback     | `pages/feedback.html`   | Public       |
| 15 | Admin Panel  | `pages/admin.html`      | Admin        |

---

## 7. Technology Stack

| Technology | Purpose                        |
|------------|--------------------------------|
| HTML5      | Page structure and semantics   |
| CSS3       | Styling and responsive layout  |
| JavaScript | Interactivity and DOM handling |

No external libraries, frameworks, or CDN dependencies are used. The portal is built entirely with vanilla web technologies.

---

## 8. Future Enhancements

- Backend integration (Node.js / Python)
- Database support (PostgreSQL / MongoDB)
- Faculty user role with grading interface
- Real-time notifications and messaging
- Calendar integration for assignments and events
- Dark mode theme toggle
- Multi-language support (i18n)
- Progressive Web App (PWA) capabilities

---

*Document maintained by the StudentHub Development Team.*
