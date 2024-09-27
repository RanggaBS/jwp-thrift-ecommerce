/** @type {import('tailwindcss').Config} */
module.exports = {
	content: ["./*.html", "./styles/*.css", "./scripts/*.js"],
	theme: {
		extend: {
			colors: {
				primary: "#2F1107",
				"primary-hover": "#230d05",
			},
		},
	},
	plugins: [],
};
