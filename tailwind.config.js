const plugin = require("tailwindcss/plugin");

module.exports = {
  mode: "jit",
  purge: ["./src/**/*.html", "./src/**/*.svg", "./src/**/*.js"],
  darkMode: false,
  theme: {
    fontFamily: {
      sans: ["Montserrat", "sans-serif"],
      serif: ["Montserrat", "serif"],
    },

    screens: {
      "3xl": { max: "1919px" },
      "2xl": { max: "1535px" },
      xl: { max: "1279px" },
      lg: { max: "1023px" },
      md: { max: "767px" },
      sm: { max: "639px" },
      xs: { max: "319px" },

      "min-3xl": { min: "1919px" },
      "min-2xl": { min: "1535px" },
      "min-xl": { min: "1279px" },
      "min-lg": { min: "1023px" },
      "min-md": { min: "767px" },
      "min-sm": { min: "639px" },
      "min-xs": { min: "319px" },
    },
    extend: {
      colors: {
        primary: "#D4392A",
        secondary: "#0F71B3",
        gray: {
          50: "#f7f7f7",
          100: "#efefef",
          200: "#dfdfdf",
          300: "#cbcbcb",
          400: "#a8a8a8",
          500: "#878787",
          600: "#6d6d6d",
          700: "#5f5f5f",
          800: "#4a4a4a",
          900: "#3d3d3d",
        },
      },
    },
  },
  variants: {
    extend: {},
  },
  plugins: [
    require("@tailwindcss/typography"),
    require("@tailwindcss/aspect-ratio"),
    require("@tailwindcss/line-clamp"),
    require("@tailwindcss/forms")({
      strategy: "class",
    }),
    plugin(function ({ addBase }) {
      const essential_base = {
        ".bg-index": { zIndex: "-1" },

        ".max-w-screen-sm": { maxWidth: "640px" },
        ".max-w-screen-md": { maxWidth: "768px" },
        ".max-w-screen-lg": { maxWidth: "1024px" },
        ".max-w-screen-xl": { maxWidth: "1280px" },
        ".max-w-screen-2xl": { maxWidth: "1536px" },

        ".min-w-screen-sm": { minWidth: "640px" },
        ".min-w-screen-md": { minWidth: "768px" },
        ".min-w-screen-lg": { minWidth: "1024px" },
        ".min-w-screen-xl": { minWidth: "1280px" },
        ".min-w-screen-2xl": { minWidth: "1536px" },

        ".w-screen-sm": { width: "640px" },
        ".w-screen-md": { width: "768px" },
        ".w-screen-lg": { width: "1024px" },
        ".w-screen-xl": { width: "1280px" },
        ".w-screen-2xl": { width: "1536px" },

        ".object-contain-": { objectFit: "contain" },
        ".object-cover-": { objectFit: "cover" },

        ".object-top-": { objectPosition: "top" },
        ".object-left-": { objectPosition: "left" },
        ".object-bottom-": { objectPosition: "bottom" },
        ".object-right-": { objectPosition: "right" },
      };

      addBase(essential_base);
    }),
  ],
};
