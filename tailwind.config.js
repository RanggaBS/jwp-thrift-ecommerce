/** @type {import('tailwindcss').Config} */
module.exports = {
	content: [
		"./*.{html,php}",
		"./search.php",
		"./admin/*.{html,php,css,js}",
		"./admin/add-product.php",
		"./account/*.php",
		"./admin/**/*.{html,php,css,js}",
		"./components/*.php",
		"./styles/*.css",
		"./styles/**/*.css",
		"./scripts/*.js",
		"./scripts/**/*.js",
	],
	theme: {
		extend: {
			colors: {
				primary: "#74270d",
				"primary-hover": "#38160a",
			},
			screens: {
				"2xs": "375px",
				xs: "480px",
			},
		},
	},
	plugins: [],
};
