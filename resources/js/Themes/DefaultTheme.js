const light = {
    dark: false,
    colors: {
        background: "#ECEFF1",
        primary: "#13294B", //blue
        secondary: "#DFA92C", //gold
        accent: "#185D33", //green
        error: "#b71c1c", //red
        "search-bg": "#FBFBFC",
        "status-occupied": "#4c3629", // light brown
        "expansion-panel-bg": "#FFFFFF", // white
    },
};

const dark = {
    dark: true,
    colors: {
        background: "#252525",
        primary: "#BAA82C", //gold
        secondary: "#2C3E50", //midnight blue
        accent: "#4C3629", //brown
        error: "#F44336", //red
        "search-bg": "#252525",
        "primary-tonal": "#BAA82C",
        "secondary-tonal": "#FFD700", //gold
        "accent-tonal": "#4C3629", //brown
        "error-tonal": "#F44336", //red
        "primary-tonal-alert": "#BAA82C",
        "secondary-tonal-alert": "#FFD700", //gold
        "accent-tonal-alert": "#4C3629", //brown
        "error-tonal-alert": "#F44336", //red
        "status-occupied": "#c1b7b1", //brown
        "expansion-panel-bg": "#252525",
    },
};

export { light, dark };
export default light;
