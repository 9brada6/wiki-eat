const globals = {
	jquery: 'jQuery', // Ensure we use jQuery which is always available even in noConflict mode
	'popper.js': 'Popper',
};

export default {
	input: 'assets/js-backend/dist/index.js',
	output: {
		file: 'assets/js-backend/script.js',
		format: 'iife',
		name: 'WikiEat',
		globals,
	},
};
