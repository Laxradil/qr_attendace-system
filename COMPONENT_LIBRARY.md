# Admin UI Component Library

## Modern Glass-Morphism Design System for QR Attendance Admin

### Colors (CSS Variables)
- `--bg`: #020510 (Main background)
- `--purple`: #8b5cff (Primary accent)
- `--blue`: #43a6ff (Info accent)
- `--green`: #18f08b (Success accent)
- `--red`: #ff3d72 (Danger accent)
- `--yellow`: #ffc75a (Warning accent)
- `--text`: #f0f4ff (Primary text)
- `--muted`: #9ba8cc (Secondary text)

### Component Classes

#### Stats Grid
```html
<div class="stats stats-4">
  <a href="#" class="stat glass">
    <div class="stat-icon blue">👥</div>
    <div class="stat-body">
      <strong>{{ $count }}</strong>
      <span>Label</span>
      <div class="trend up">↑ Metric</div>
    </div>
  </a>
</div>
```

#### Card Layout
```html
<div class="card glass">
  <div class="section-head">
    <h3>📊 Title</h3>
    <a href="#">View All →</a>
  </div>
  <!-- Content -->
</div>
```

#### Table
```html
<div class="table-wrap glass">
  <table>
    <thead><tr><th>Header</th></tr></thead>
    <tbody><!-- Rows --></tbody>
  </table>
  <div class="footer-bar">
    <span>Info</span>
    <div class="pager"><!-- Pagination --></div>
  </div>
</div>
```

#### Buttons
- `.btn` - Default button
- `.btn.primary` - Primary action (purple gradient)
- `.btn.danger` - Delete/danger action (red)
- `.btn.slim` / `.btn.sm` - Small buttons

#### Pills/Badges
- `.pill` - Status indicator
- `.pill.green` - Success status
- `.pill.red` - Error/danger status
- `.pill.yellow` - Warning status
- `.pill.blue` - Info status
- `.pill.purple` - Custom status

#### User Cell
```html
<div class="user-cell">
  <span class="small-avatar">AB</span>
  <span>Name</span>
</div>
```

#### Dashboard Layout
```html
<div class="dashboard">
  <div class="dash-left"><!-- Left column --></div>
  <div class="dash-right"><!-- Right column --></div>
</div>
```

#### Grid Layouts
- `.g2` - 2 column grid
- `.g3` - 3 column grid
- `.g-6-4` - 6:4 ratio grid

#### Form Elements
- `.fi` - Form input/textarea
- `.fi-label` - Form label

### Navigation (in Sidebar)
- Active links use gradient background: `linear-gradient(135deg,rgba(139,92,255,.88),rgba(67,166,255,.5))`
- Nav icons: `.nav-icon` with hover effects
- Badges: `.nav-badge` (red background for notifications)

### Responsive Breakpoints
- `@media(max-width:1200px)` - Collapsed sidebar
- `@media(max-width:760px)` - Mobile layout

### Animation Classes
- `@keyframes fadein` - Page transition fade-in effect
- `@keyframes orb-float` - Background orb floating animation
- Toast animations included in layout CSS
