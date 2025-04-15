import "vuetify/styles";
import { createVuetify } from "vuetify";
import * as components_vuetify from "vuetify/components";
import * as directives from "vuetify/directives";

import { VNumberInput } from 'vuetify/labs/VNumberInput';

import '@mdi/font/css/materialdesignicons.css';

import { aliases, mdi } from 'vuetify/iconsets/mdi'

const customDarkTheme = {
    dark: true,
    colors: {
        background: "#15202b",
        surface: "#15202b",
        primary: "#3f51b5",
        secondary: "#03dac6",
        error: "#ff5722",
    },
};

const vuetify = createVuetify({
    theme: {
        defaultTheme: "customDarkTheme",
        themes: {
            customDarkTheme,
        },
    },
    icons: {
        defaultSet: 'mdi',
        aliases,
        sets: {
            mdi,
        },
    },
    components: {
        ...components_vuetify,
        VNumberInput,
    },
    directives,
});

export default vuetify;