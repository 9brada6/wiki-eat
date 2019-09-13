const presets = [
	[
	  "@babel/env",
	  {
		loose: true,
		modules: false,
		useBuiltIns: "usage",
	  },
	],
  ];

  module.exports = { presets };