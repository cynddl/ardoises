//grunt.loadNpmTasks('grunt-yui-compressor');

module.exports = function(grunt) {
	grunt.loadNpmTasks('grunt-yui-compressor');

  // Project configuration.
  grunt.initConfig({
    lint: {
      files: ['grunt.js', 'js/*.js']
    },
		
		min: {
			dist: {
				src: ['js/jquery-1.8.1.js', 'js/jquery.dataTables.min.js', 'js/bootstrap.js', 'js/DT_bootstrap.js', 'js/patternizer.min.js', 'js/script.js'],
				dest: 'js/main.min.js'
			}
		},
		
		cssmin: {
			dist: {
				src: ['css/bootstrap.css', 'css/nav-fix.css', 'css/style.css'],
				dest: 'css/main.min.css'
			}
		},
		
    watch: {
      files: '<config:lint.files>',
      tasks: 'lint qunit'
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
  grunt.registerTask('default', 'lint concat min');

};
