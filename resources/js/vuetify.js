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
    background: "#121212",      
    surface: "#303030",         
    primary: "#375A7F",         
    secondary: "#90caf9",       
    error: "#ef5350",          
    info: "#29b6f6",
    success: "#66bb6a",
    warning: "#ffa726",
  },
};

const customLightTheme = {
  dark: false,
  colors: {
    background: "#f2f5f9",     
    surface: "#ffffff",        
    primary: "#1e88e5",        
    secondary: "#90caf9",      
    error: "#e53935",
    info: "#42a5f5",
    success: "#43a047",
    warning: "#fbc02d",
  },
};

const vuetify = createVuetify({
    theme: {
        defaultTheme: "customDarkTheme",
        themes: {
            customDarkTheme,
            customLightTheme,
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