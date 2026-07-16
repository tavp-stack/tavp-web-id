/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './themes/**/*.volt',
    './public/**/*.html',
    './resources/**/*.php',
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        "background": "#0d131f",
        "on-background": "#dde2f3",
        "surface": "#0d131f",
        "surface-container-lowest": "#080e1a",
        "surface-container-low": "#161c27",
        "surface-container": "#1a202c",
        "surface-container-high": "#242a36",
        "surface-container-highest": "#2f3542",
        "surface-variant": "#2f3542",
        "surface-bright": "#333946",
        "on-surface": "#dde2f3",
        "on-surface-variant": "#c5c6cd",
        "primary": "#bdc7dc",
        "on-primary": "#273141",
        "primary-container": "#2d3748",
        "secondary": "#e6c446",
        "on-secondary": "#3b2f00",
        "secondary-container": "#ac8e0a",
        "tertiary": "#bcc7dd",
        "on-tertiary-container": "#95a0b5",
        "outline": "#8f9097",
        "outline-variant": "#45474c",
        "error": "#ffb4ab"
      },
      borderRadius: { "DEFAULT": "0.125rem", "lg": "0.25rem", "xl": "0.5rem", "full": "0.75rem" },
      spacing: { "gutter": "1.5rem", "component-padding-y": "0.75rem", "component-padding-x": "1rem", "base": "4px", "container-margin": "2rem" },
      fontFamily: {
        "headline-xl": ["Geist"],
        "headline-lg": ["Geist"],
        "body-md": ["Inter"],
        "code-sm": ["JetBrains Mono"],
        "label-caps": ["JetBrains Mono"]
      },
      fontSize: {
        "headline-xl": ["40px", {"lineHeight": "48px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
        "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
        "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
        "code-sm": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
        "label-caps": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600"}]
      }
    }
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
}
