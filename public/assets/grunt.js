module.exports = function(grunt) {
	grunt.loadNpmTasks('grunt-css');

	// Project configuration.
	grunt.initConfig({

		min: {
			dist: {
				src: ['components/jquery/jquery.js',
				'js/jquery.dataTables.min.js',
				'components/bootstrap/docs/assets/js/bootstrap.js',
				'js/bootstrap-editable.js',
				'js/DT_bootstrap.js',
				'js/patternizer.min.js',
				'js/script.js'],
				dest: 'js/main.min.js'
			}
		},
		
		cssmin: {
			dist: {
				src: [
					'components/bootstrap/docs/assets/css/bootstrap.css',
					'components/bootstrap/docs/assets/css/bootstrap-responsive.css',
					'css/bootstrap-editable.css',
					'css/nav-fix.css',
					'css/style.css'
				],
				dest: 'css/main.min.css'
			}
		},

		jshint: {
			options: {
				curly: true,
				eqeqeq: true,
				immed: true,
				latedef: true,
				newcap: true,
				noarg: true,
				sub: true,
				undef: true,
				boss: true,
				eqnull: true,
				browser: true
			},
			globals: {
				jQuery: true
			}
		},
		uglify: {}
	});

	// Default task.
	grunt.registerTask('default', 'min cssmin');

};
