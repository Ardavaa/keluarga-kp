# Proposed Role Division for 3 Developers (`keluarga-kp`)

To successfully deliver this project, the workload can be split logically among the three developers by separating the responsibilities into **Frontend & UI/UX**, **Backend & Data Integration**, and **Data Visualization & Logic**. 

Below is a proposed division of roles tailored to the architecture and current state of the dashboard:

---

## Developer 1: Frontend & UI/UX Specialist
**Focus:** Visual Identity, Responsive Layouts, and Dashboard Aesthetics (Tailwind CSS v4 + Alpine.js + Blade).

This developer handles the user-facing chrome, responsive layouts, components, and ensures the application follows the Telkom University design guidelines while executing the "De-AI-ify" UI strategy (achieving clean, professional designs over generic gradients/glows).

### Key Responsibilities:
*   **Layout & Shell:** Managing the left sidebar layout (`app.blade.php`), sidebar drawer transitions via Alpine.js, mobile responsiveness, and desktop layout sizes.
*   **Design Token Configuration:** Modifying `@theme` tokens in `resources/css/app.css` (fonts, custom TelU color shades, borders, shadows).
*   **UI Components:** Building and polishing reusable Blade components:
    *   `x-kpi-card` (metrics cards for dashboard and profile details).
    *   `x-lecturer-card` (profile preview cards).
    *   `x-research-group-badge` (styling for CITI, DSIS, SEAL badges).
    *   `x-page-header` & `x-export-buttons` (alignment, layout headers, and button layouts).
*   **Layout Styling:** Re-styling existing pages to match the flat, clean "De-AI-ified" philosophy (removing excessive gradients, pulse icons, round avatar glow, and replacing them with flat borders, solid red brand accents, and clean lists).

---

## Developer 2: Backend & Data Integration Engineer
**Focus:** Database Schema, Data Import Scripts, External Exports, and Core Integration (Laravel Models + Migrations + Package Integrations).

This developer manages the system's database structure, data integrity, CLI tools, and file generation.

### Key Responsibilities:
*   **Migrations & Schema:** Maintaining tables (`lecturers`, `publications`, `profiles`, etc.) and adjusting constraints/indexes.
*   **Model Relationships:** Maintaining Eloquent relationships in `app/Models/` (especially dual FK relations like recommendations and co-authorship pairings).
*   **Excel/PDF Generation:** Implementing export classes in `app/Exports/` using `maatwebsite/excel` and building inline CSS print layouts in `resources/views/pdf/` using `barryvdh/laravel-dompdf`.
*   **Scraper Data Integration (Fase 7):** Leading the integration of real scraped data:
    *   Developing and testing CLI scripts like `php artisan import:lecturers`.
    *   Setting up raw PostgreSQL database links or data synchronization pipelines between the python scraper outputs and Laravel.

---

## Developer 3: Data Visualization & Query Logic Engineer
**Focus:** Charts, Interactive Network Graphs, Controller Queries, and Recommendations Logic (Chart.js + vis-network + Controller logic).

This developer acts as the bridge, retrieving data from models, parsing it into JSON graphs/datasets, and configuring frontend visualization engines.

### Key Responsibilities:
*   **Dominant Topics (Chart.js):** Customizing `TopicController`, compiling counts of AI categories, and building the doughnut/bar Chart.js widgets in `topics.blade.php`.
*   **Collaboration Graph (vis-network):** Managing `CollaborationController` query logic, mapping database pairs to node degrees and edge weights, and configuring the interactive canvas physics and group colors in `collaborations.blade.php`.
*   **Recommendations Logic:** Handling the mapping of recommendations in `RecommendationController`, ordering similarity scores, and displaying mutual keyword explanations.
*   **Directory Search & Filter Queries:** Writing optimized Eloquent database queries in `LecturerController@index` to handle global search queries, sorting dropdowns, and combined multi-attribute dropdown filters.

---

## Collaboration Matrix & File Responsibilities

| Feature Area | Dev 1 (Frontend) | Dev 2 (Data/Backend) | Dev 3 (Viz/Logic) |
|---|---|---|---|
| **Main Dashboard** | Card structures & list layouts | Exports (Excel/PDF sheets) | Query compilation & KPI metric calculations |
| **Lecturer Directory** | Filter layout alignment, cards grid | Filter queries, database indexing | Controller sorting & filtering logic |
| **Profile Detail** | Academic JFA detail design, icons | Profile links logic, publications list | Eager-load optimization (`with()`) |
| **Expertise Map** | Alpine KK tab component | Database migrations and seeds | Expertise grouping controller logic |
| **Dominant Topics** | Legend lists and table styling | Excel/PDF reports | Chart.js datasets & JS integration |
| **Collaboration Graph** | Legend badges and CSS containers | Excel/PDF export datasets | vis-network node/edge JSON preparation |
| **Recommendations** | UI match cards layout | Database tables & seeding | Scoring hierarchy controller logic |
