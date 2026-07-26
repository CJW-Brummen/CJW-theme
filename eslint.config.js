import js from "@eslint/js";
import globals from "globals";

export default [
    {
        ignores: [
            "**/*.min.js",
        ],
    },
    js.configs.recommended,
    {
        files: ["js/**/*.js"],
        languageOptions: {
            ecmaVersion: "latest",
            sourceType: "script",
            globals: {
                ...globals.browser,
                jQuery: "readonly",
                wp: "readonly",
            },
        },
    },
];
