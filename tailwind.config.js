module.exports = {
	content: [
		'./DistributionPackages/Neniri.App/Resources/Private/Partials/*/*.html',
		'./DistributionPackages/Neniri.App/Resources/Private/Layouts/*.html',
		'./DistributionPackages/Neniri.App/Resources/Private/Templates/*/*.html',
		'./DistributionPackages/Neniri.App/Resources/Private/Templates/Backend/*/*.html',
		'./DistributionPackages/Neniri.App/Resources/Public/scripts/app.js',
	],
	theme: {
		screens: {
			'sm': {'max': '767px'},
			'md': {'max': '1023px'},
			'lg': {'max': '1279px'},
		},
		container: {
			padding: '1.75rem',
			screens: {
				sm: "100%",
				md: "100%",
				lg: "997px",
				xl: "997px"
			}
		},
		extend: {
			zIndex: {
				"-1": "-1",
			},
		},
	},
	plugins: [
		require("@tailwindcss/forms")({
			strategy: 'class', // only generate global styles
		}),
	],
}
