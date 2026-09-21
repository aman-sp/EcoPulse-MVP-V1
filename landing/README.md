# EcoPulse Marketing Website

> India's first carbon intelligence platform for hospitals — marketing & landing page.

## 🚀 Live Site

Hosted on GitHub Pages: **https://[your-username].github.io/ecopulse-landing**

## 📁 Structure

```
landing/
├── index.html          # Main landing page
├── assets/
│   ├── css/style.css   # All styles
│   ├── js/main.js      # Animations, counters, form
│   └── images/logo.jpg # EcoPulse logo
└── README.md
```

## ⚙️ Setup

### 1. Configure the Contact Form (Formspree)
1. Go to [formspree.io](https://formspree.io) and create a free account
2. Create a new form and copy your Form ID (looks like `xpwdabcd`)
3. In `index.html`, find:
   ```html
   action="https://formspree.io/f/YOUR_FORMSPREE_ID"
   ```
   Replace `YOUR_FORMSPREE_ID` with your actual ID.

### 2. Update WhatsApp Number
In `index.html`, replace all instances of `919999999999` with your actual WhatsApp number (country code + number, no spaces or +).

### 3. Update Contact Email
Replace `hello@ecopulse.in` with your actual email address.

### 4. Update App Login URLs
When deploying the backend app, replace:
```
http://localhost/EcoPulse/public/hospital/login
http://localhost/EcoPulse/public/admin/login
```
with your actual production URLs.

## 🌐 Deploy to GitHub Pages

1. Create a new GitHub repository (e.g., `ecopulse-landing`)
2. Copy the `landing/` folder contents to the repo root (or use the `landing/` folder directly)
3. Go to **Settings → Pages**
4. Set Source to `main` branch, `/root` (or `/landing` folder)
5. Your site will be live at `https://[username].github.io/[repo-name]`

## 🎨 Brand Colors

| Token | Hex | Usage |
|-------|-----|-------|
| Primary Green | `#10B981` | Buttons, highlights |
| Dark Green | `#059669` | Hover states |
| Teal | `#0D9488` | Gradients |
| Amber | `#F59E0B` | Stars, accents |
| Dark BG | `#0F172A` | Dark sections |

## 📦 Dependencies (All CDN — No npm needed)

- Google Fonts: Inter
- No other runtime dependencies

## 🔧 Customization

- **Stats numbers**: Edit `data-target` attributes on `.counter` elements in `index.html`
- **Testimonials**: Update the 3 testimonial cards in the `#testimonials` section
- **Logo**: Replace `assets/images/logo.jpg` with your own file
